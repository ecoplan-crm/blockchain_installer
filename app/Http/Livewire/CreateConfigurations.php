<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Config;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Facades\File;


class CreateConfigurations extends EcoplanComponent
{
    public $dockerComposeTestNet = "";
    public $composeTestNet = "";
    public $composeCa = "";
    public $configtx = "";
    public $message = "";

    public function render()
    {
        return view('livewire.create-configurations');
    }

    public function mount()
    {
        $this->dockerComposeTestNet = session('dockerComposeTestNet', $this->dockerComposeTestNet);
        $this->composeTestNet = session('composeTestNet', $this->composeTestNet);
        $this->composeCa = session('composeCa', $this->composeCa);
        $this->configtx = session('configtx', $this->configtx);
    }

    public function updateSessionParameters()
    {
        session(['dockerComposeTestNet' => $this->dockerComposeTestNet]);
        session(['composeTestNet' => $this->composeTestNet]);
        session(['composeCa' => $this->composeCa]);
        session(['configtx' => $this->configtx]);
    }

    public function rules()
    {
        return [
            'dockerComposeTestNet' => ['required'],
            'composeTestNet' => ['required'],
            'composeCa' => ['required'],
            'configtx' => ['required'],
        ];
    }

    public function next()
    {
        if (session('newNetwork')) {
            $this->validate();
        }
        return redirect()->to('/startNetwork');
    }

    public function back()
    {
        if (session("newNetwork")) {
            return redirect()->to('/enterParameters');
        } else {
            return redirect()->to('/?ignoreSessionCookie');
        }

    }

    public function clearInstallationPath()
    {
        $directoryPath = Config::get('ecoplan.network_directory');

        if (File::exists($directoryPath)) {
            File::deleteDirectory($directoryPath);
            if (!File::exists($directoryPath)) {
                session(['message' => 'Verzeichnis wurde gelöscht']);
            } else {
                session(['message' => 'Beim Löschen ist ein Fehler aufgetreten. Hat das Verzeichnis die notwendigen Berechtigungen? Ggf. auf der Startseite das Netzwerk zurücksetzen!']);
            }

        } else {
            session(['message' => Config::get('ecoplan.network_directory') . ' Verzeichnis wurde nicht gefunden']);
        }
    }

    public function createConfigurations()
    {
        $peerCount = session('peerCount');

        //Auflistung aller Peers und der Cli
        $dockerComposeTestNet = self::generateDockerComposeTestNet($peerCount);

        //Auflistung aller Peers und des orderers und der Cli
        $composeTestNet = self::generateComposeTestNet($peerCount);

        //Alle CAs (je einer für jede Org und einen Orderer-CA)
        $composeCa = self::generateComposeCa($peerCount);

        //Auflistung der Organisationen und des orderers
        $configtx = self::generateConfigtx($peerCount);

        //In Datei schreiben
        $path = Config::get('ecoplan.network_directory');

        $dockerComposeTestNet = Yaml::dump($dockerComposeTestNet, 5, 2);
        file_put_contents($path . '/compose/docker/docker-compose-test-net.yaml', $dockerComposeTestNet);

        $composeTestNet = Yaml::dump($composeTestNet, 5, 2);
        $composeTestNet = str_replace('null', '', $composeTestNet);
        $composeTestNet = str_replace("'", '', $composeTestNet);
        $composeTestNet = str_replace("3.7", "'3.7'", $composeTestNet);
        file_put_contents($path . '/compose/compose-test-net.yaml', $composeTestNet);

        $composeCa = Yaml::dump($composeCa, 5, 2);
        file_put_contents($path . '/compose/compose-ca.yaml', $composeCa);

        file_put_contents($path . '/configtx/configtx.yaml', $configtx);

        $this->dockerComposeTestNet = $dockerComposeTestNet;
        $this->composeTestNet = $composeTestNet;
        $this->composeCa = $composeCa;
        $this->configtx = $configtx;

        $this->updateSessionParameters();

        $this->message = "Konfigurationsdateien wurden erzeugt";
    }

    public function generateDockerComposeTestNet($peerNo)
    {
        $value = [
            'version' => '3.7',
            'services' => []
        ];

        for ($i = 0; $i < $peerNo; $i++) {

            $value['services'] += [
                'peer' . $i . '.org1.example.com' => [
                    'container_name' => 'peer' . $i . '.org1.example.com',
                    'image' => 'hyperledger/fabric-peer:2.4.9',
                    'labels' => ['service' => 'hyperledger-fabric'],
                    'environment' => [
                        'CORE_VM_ENDPOINT=unix:///host/var/run/docker.sock',
                        'CORE_VM_DOCKER_HOSTCONFIG_NETWORKMODE=fabric_test',
                    ],
                    'volumes' => [
                        './docker/peercfg:/etc/hyperledger/peercfg',
                        '${DOCKER_SOCK}:/host/var/run/docker.sock',
                    ],
                ]
            ];
        }

        $value['services'] += [
            'cli' => [
                'container_name' => 'cli',
                'image' => 'hyperledger/fabric-tools:2.4.9',
                'volumes' => ['./docker/peercfg:/etc/hyperledger/peercfg'],
            ]
        ];

        return $value;
    }

    public function generateComposeTestNet($peerNo)
    {
        $value = [
            'version' => '3.7',

            'volumes' => null,

            'networks' => [
                'test' => [
                    'name' => 'fabric_test',
                ],
            ],

            'services' => null
        ];

        //Orderer-Service
        $value['volumes'] = ['orderer.example.com' => null];
        $value['services'] = self::ordererService();

        //Peers-Service
        for ($i = 0; $i < $peerNo; $i++) {
            $value['volumes'] += ['peer' . $i . '.org1.example.com' => null];
            $value['services'] += self::peerService($i);
        }

        //CLI-Service
        $value['services'] += self::cliService();
        for ($i = 0; $i < $peerNo; $i++) {
            array_push($value['services']['cli']['depends_on'], 'peer' . $i . '.org1.example.com');
        }

        return $value;
    }

    private function ordererService()
    {
        $return = [
            'orderer.example.com' => [
                'container_name' => 'orderer.example.com',
                'image' => 'hyperledger/fabric-orderer:2.4.9',
                'labels' => [
                    'service' => 'hyperledger-fabric',
                ],
                'environment' => [
                    'FABRIC_LOGGING_SPEC=INFO',
                    'ORDERER_GENERAL_LISTENADDRESS=0.0.0.0',
                    'ORDERER_GENERAL_LISTENPORT=7050',
                    'ORDERER_GENERAL_LOCALMSPID=OrdererMSP',
                    'ORDERER_GENERAL_LOCALMSPDIR=/var/hyperledger/orderer/msp',
                    'ORDERER_GENERAL_TLS_ENABLED=true',
                    'ORDERER_GENERAL_TLS_PRIVATEKEY=/var/hyperledger/orderer/tls/server.key',
                    'ORDERER_GENERAL_TLS_CERTIFICATE=/var/hyperledger/orderer/tls/server.crt',
                    'ORDERER_GENERAL_TLS_ROOTCAS=[/var/hyperledger/orderer/tls/ca.crt]',
                    'ORDERER_GENERAL_CLUSTER_CLIENTCERTIFICATE=/var/hyperledger/orderer/tls/server.crt',
                    'ORDERER_GENERAL_CLUSTER_CLIENTPRIVATEKEY=/var/hyperledger/orderer/tls/server.key',
                    'ORDERER_GENERAL_CLUSTER_ROOTCAS=[/var/hyperledger/orderer/tls/ca.crt]',
                    'ORDERER_GENERAL_BOOTSTRAPMETHOD=none',
                    'ORDERER_CHANNELPARTICIPATION_ENABLED=true',
                    'ORDERER_ADMIN_TLS_ENABLED=true',
                    'ORDERER_ADMIN_TLS_CERTIFICATE=/var/hyperledger/orderer/tls/server.crt',
                    'ORDERER_ADMIN_TLS_PRIVATEKEY=/var/hyperledger/orderer/tls/server.key',
                    'ORDERER_ADMIN_TLS_ROOTCAS=[/var/hyperledger/orderer/tls/ca.crt]',
                    'ORDERER_ADMIN_TLS_CLIENTROOTCAS=[/var/hyperledger/orderer/tls/ca.crt]',
                    'ORDERER_ADMIN_LISTENADDRESS=0.0.0.0:7053',
                    'ORDERER_OPERATIONS_LISTENADDRESS=orderer.example.com:9443',
                    'ORDERER_METRICS_PROVIDER=prometheus',
                ],
                'working_dir' => '/root',
                'command' => 'orderer',
                'volumes' => [
                    '../organizations/ordererOrganizations/example.com/orderers/orderer.example.com/msp:/var/hyperledger/orderer/msp',
                    '../organizations/ordererOrganizations/example.com/orderers/orderer.example.com/tls/:/var/hyperledger/orderer/tls',
                    'orderer.example.com:/var/hyperledger/production/orderer',
                ],
                'ports' => [
                    '7050:7050',
                    '7053:7053',
                    '9443:9443',
                ],
                'networks' => [
                    'test',
                ],
            ]
        ];

        return $return;
    }

    private function peerService($peerNo)
    {

        $port2 = 9444 + $peerNo;
        $port = 7051 + (1000 * $peerNo);

        $return = [
            'peer' . $peerNo . '.org1.example.com' => [
                'container_name' => 'peer' . $peerNo . '.org1.example.com',
                'image' => 'hyperledger/fabric-peer:2.4.9',
                'labels' => [
                    'service' => 'hyperledger-fabric',
                ],
                'environment' => [
                    'FABRIC_CFG_PATH=/etc/hyperledger/peercfg',
                    'FABRIC_LOGGING_SPEC=INFO',
                    'CORE_PEER_TLS_ENABLED=true',
                    'CORE_PEER_PROFILE_ENABLED=false',
                    'CORE_PEER_TLS_CERT_FILE=/etc/hyperledger/fabric/tls/server.crt',
                    'CORE_PEER_TLS_KEY_FILE=/etc/hyperledger/fabric/tls/server.key',
                    'CORE_PEER_TLS_ROOTCERT_FILE=/etc/hyperledger/fabric/tls/ca.crt',
                    'CORE_PEER_ID=peer' . $peerNo . '.org1.example.com',
                    'CORE_PEER_ADDRESS=peer' . $peerNo . '.org1.example.com:' . ($port),
                    'CORE_PEER_LISTENADDRESS=0.0.0.0:' . ($port),
                    'CORE_PEER_CHAINCODEADDRESS=peer' . $peerNo . '.org1.example.com:' . ($port + 1),
                    'CORE_PEER_CHAINCODELISTENADDRESS=0.0.0.0:' . ($port + 1),
                    'CORE_PEER_GOSSIP_BOOTSTRAP=peer' . $peerNo . '.org1.example.com:' . ($port),
                    'CORE_PEER_GOSSIP_EXTERNALENDPOINT=peer' . $peerNo . '.org1.example.com:' . ($port),
                    'CORE_PEER_LOCALMSPID=Org1MSP',
                    'CORE_PEER_MSPCONFIGPATH=/etc/hyperledger/fabric/msp',
                    'CORE_OPERATIONS_LISTENADDRESS=peer' . $peerNo . '.org1.example.com:' . ($port2),
                    'CORE_METRICS_PROVIDER=prometheus',
                    'CHAINCODE_AS_A_SERVICE_BUILDER_CONFIG={"peername":"peer' . $peerNo . 'org1"}',
                    'CORE_CHAINCODE_EXECUTETIMEOUT=300s'
                ],
                'volumes' => [
                    '../organizations/peerOrganizations/org1.example.com/peers/peer' . $peerNo . '.org1.example.com:/etc/hyperledger/fabric',
                    'peer' . $peerNo . '.org1.example.com:/var/hyperledger/production'
                ],
                'working_dir' => '/root',
                'command' => 'peer node start',
                'ports' => [
                    ($port) . ':' . ($port),
                    ($port2) . ':' . ($port2),
                ],
                'networks' => [
                    'test',
                ],
            ]
        ];

        return $return;
    }


    private function cliService()
    {
        return [
            'cli' => [
                'container_name' => 'cli',
                'image' => 'hyperledger/fabric-tools:2.4.9',
                'labels' => [
                    'service' => 'hyperledger-fabric',
                ],
                'tty' => true,
                'stdin_open' => true,
                'environment' => [
                    'GOPATH=/opt/gopath',
                    'FABRIC_LOGGING_SPEC=INFO',
                    'FABRIC_CFG_PATH=/etc/hyperledger/peercfg',
                ],
                'working_dir' => '/opt/gopath/src/github.com/hyperledger/fabric/peer',
                'command' => '/bin/bash',
                'volumes' => [
                    '../organizations:/opt/gopath/src/github.com/hyperledger/fabric/peer/organizations',
                    '../scripts:/opt/gopath/src/github.com/hyperledger/fabric/peer/scripts/'
                ],
                'depends_on' => [],
                'networks' => [
                    'test',
                ],
            ],
        ];
    }

    public function generateComposeCa($peerNo)
    {
        $value = [
            'version' => '3.7',
            'networks' => [
                'test' => [
                    'name' => 'fabric_test',
                ],
            ],
            'services' => [
                'ca_org1' => [
                    'image' => 'hyperledger/fabric-ca:1.5.2',
                    'labels' => [
                        'service' => 'hyperledger-fabric',
                    ],
                    'environment' => [
                        'FABRIC_CA_HOME=/etc/hyperledger/fabric-ca-server',
                        'FABRIC_CA_SERVER_CA_NAME=ca-org1',
                        'FABRIC_CA_SERVER_TLS_ENABLED=true',
                        'FABRIC_CA_SERVER_PORT=7054',
                        'FABRIC_CA_SERVER_OPERATIONS_LISTENADDRESS=0.0.0.0:17054'
                    ],
                    'ports' => [
                        '7054:7054',
                        '17054:17054',
                    ],
                    'command' => "sh -c 'fabric-ca-server start -b admin:adminpw -d'",
                    'volumes' => [
                        '../organizations/fabric-ca/org1:/etc/hyperledger/fabric-ca-server',
                    ],
                    'container_name' => 'ca_org1',
                    'networks' => [
                        'test',
                    ],
                ],

                'ca_orderer' => [
                    'image' => 'hyperledger/fabric-ca:1.5.2',
                    'labels' => [
                        'service' => 'hyperledger-fabric',
                    ],
                    'environment' => [
                        'FABRIC_CA_HOME=/etc/hyperledger/fabric-ca-server',
                        'FABRIC_CA_SERVER_CA_NAME=ca-orderer',
                        'FABRIC_CA_SERVER_TLS_ENABLED=true',
                        'FABRIC_CA_SERVER_PORT=9054',
                        'FABRIC_CA_SERVER_OPERATIONS_LISTENADDRESS=0.0.0.0:19054',
                    ],
                    'ports' => [
                        '9054:9054',
                        '19054:19054',
                    ],
                    'command' => "sh -c 'fabric-ca-server start -b admin:adminpw -d'",
                    'volumes' => [
                        '../organizations/fabric-ca/ordererOrg:/etc/hyperledger/fabric-ca-server',
                    ],
                    'container_name' => 'ca_orderer',
                    'networks' => [
                        'test',
                    ],
                ],
            ],
        ];

        return $value;
    }

    public function generateConfigtx($peerNo)
    {
        return "
Organizations:

    - &OrdererOrg

        Name: OrdererOrg

        ID: OrdererMSP

        MSPDir: ../organizations/ordererOrganizations/example.com/msp

        Policies:
            Readers:
                Type: Signature
                Rule: \"OR('OrdererMSP.member')\"
            Writers:
                Type: Signature
                Rule: \"OR('OrdererMSP.member')\"
            Admins:
                Type: Signature
                Rule: \"OR('OrdererMSP.admin')\"

        OrdererEndpoints:
        - orderer.example.com:7050

    - &Org1

        Name: Org1MSP

        ID: Org1MSP

        MSPDir: ../organizations/peerOrganizations/org1.example.com/msp

        Policies:
            Readers:
                Type: Signature
                Rule: \"OR('Org1MSP.admin', 'Org1MSP.peer', 'Org1MSP.client')\"
            Writers:
                Type: Signature
                Rule: \"OR('Org1MSP.admin', 'Org1MSP.client')\"
            Admins:
                Type: Signature
                Rule: \"OR('Org1MSP.admin')\"
            Endorsement:
                Type: Signature
                Rule: \"OR('Org1MSP.peer')\"
Capabilities:

    Channel: &ChannelCapabilities
        V2_0: true

    Orderer: &OrdererCapabilities
        V2_0: true

    Application: &ApplicationCapabilities
        V2_0: true

Application: &ApplicationDefaults

    Organizations:

    Policies:
        Readers:
            Type: ImplicitMeta
            Rule: \"ANY Readers\"
        Writers:
            Type: ImplicitMeta
            Rule: \"ANY Writers\"
        Admins:
            Type: ImplicitMeta
            Rule: \"MAJORITY Admins\"
        LifecycleEndorsement:
            Type: ImplicitMeta
            Rule: \"MAJORITY Endorsement\"
        Endorsement:
            Type: ImplicitMeta
            Rule: \"MAJORITY Endorsement\"

    Capabilities:
        <<: *ApplicationCapabilities

Orderer: &OrdererDefaults

    OrdererType: etcdraft

    Addresses:
        - orderer.example.com:7050

    EtcdRaft:
        Consenters:
        - Host: orderer.example.com
          Port: 7050
          ClientTLSCert: ../organizations/ordererOrganizations/example.com/orderers/orderer.example.com/tls/server.crt
          ServerTLSCert: ../organizations/ordererOrganizations/example.com/orderers/orderer.example.com/tls/server.crt

    BatchTimeout: 2s

    BatchSize:
        MaxMessageCount: 10
        AbsoluteMaxBytes: 99 MB
        PreferredMaxBytes: 512 KB

    Organizations:

    Policies:
        Readers:
            Type: ImplicitMeta
            Rule: \"ANY Readers\"
        Writers:
            Type: ImplicitMeta
            Rule: \"ANY Writers\"
        Admins:
            Type: ImplicitMeta
            Rule: \"MAJORITY Admins\"
        BlockValidation:
            Type: ImplicitMeta
            Rule: \"ANY Writers\"

Channel: &ChannelDefaults

    Policies:
        Readers:
            Type: ImplicitMeta
            Rule: \"ANY Readers\"
        Writers:
            Type: ImplicitMeta
            Rule: \"ANY Writers\"
        Admins:
            Type: ImplicitMeta
            Rule: \"MAJORITY Admins\"
    Capabilities:
        <<: *ChannelCapabilities

Profiles:

    TwoOrgsApplicationGenesis:
        <<: *ChannelDefaults
        Orderer:
            <<: *OrdererDefaults
            Organizations:
                - *OrdererOrg
            Capabilities: *OrdererCapabilities
        Application:
            <<: *ApplicationDefaults
            Organizations:
                - *Org1
            Capabilities: *ApplicationCapabilities
";
    }
}
