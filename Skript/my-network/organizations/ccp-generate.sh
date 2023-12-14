#!/bin/bash

function one_line_pem {
    echo "`awk 'NF {sub(/\\n/, ""); printf "%s\\\\\\\n",$0;}' $1`"
}

function json_ccp {
    local CP=$(one_line_pem $3)
    sed -e "s/\${ORG}/$1/" \
        -e "s/\${CAPORT}/$2/" \
        -e "s#\${CAPEM}#$CP#" \
        -e "s#\${PEERSTRINGJSON}#$peerStringJson#" \
        -e "s#\${PEERCONFIGJSON}#$peerConfigJson#" \
        organizations/ccp-template.json
}

function yaml_ccp {
    local CP=$(one_line_pem $3)
    sed -e "s/\${ORG}/$1/" \
        -e "s/\${CAPORT}/$2/" \
        -e "s#\${CAPEM}#$CP#" \
        -e "s#\${PEERSTRINGYAML}#$peerStringYaml#" \
        -e "s#\${PEERCONFIGYAML}#$peerConfigYaml#" \
        organizations/ccp-template.yaml | sed -e $'s/\\\\n/\\\n          /g'
}

ORG=1
CAPORT=7054
PEERPEM=organizations/peerOrganizations/org1.example.com/tlsca/tlsca.org1.example.com-cert.pem
CAPEM=organizations/peerOrganizations/org1.example.com/ca/ca.org1.example.com-cert.pem

if [ -n "$1" ]; then
    ORG_COUNT=$1
else
    errorln "Anzahl Orgs wurde nicht an ccp-generate übergeben. Prozess wird abgebrochen"
    exit 1
fi

if [ -n "$2" ]; then
    PEER_COUNT=$2
else
    errorln "Anzahl Peers wurde nicht an ccp-generate übergeben. Prozess wird abgebrochen"
    exit 1
fi

PEERPEMONELINE=$(one_line_pem $PEERPEM)

peerStringJson='';
peerConfigJson='';
peerStringYaml='';
peerConfigYaml='';

for ((i=0 ; i < ${PEER_COUNT}; i++)); do

    PORT=$(($i * 1000 + 7051))

    peerStringJson+="\n                \"peer$i.org1.example.com\","
    peerConfigJson+="\n        \"peer$i.org1.example.com\": {\n            \"url\": \"grpcs://localhost:$PORT\",\n            \"tlsCACerts\": {\n                \"pem\": \"$PEERPEMONELINE\"\n            },\n            \"grpcOptions\": {\n                \"ssl-target-name-override\": \"peer$i.org1.example.com\",\n                \"hostnameOverride\": \"peer$i.org1.example.com\"\n            }\n        },"
    peerStringYaml+="\n    - peer$i.org1.example.com"
    peerConfigYaml+="\n  peer$i.org1.example.com:\n    url: grpcs://localhost:$PORT\n    tlsCACerts:\n      pem: |\n          $PEERPEMONELINE\n    grpcOptions:\n      ssl-target-name-override: peer$i.org1.example.com\n      hostnameOverride: peer$i.org1.example.com"

done  

export peerStringJson=${peerStringJson%?}
export peerConfigJson=${peerConfigJson%?}

export peerStringYaml="$peerStringYaml"
export peerConfigYaml="$peerConfigYaml"

echo "$(json_ccp $ORG $CAPORT $CAPEM)" > organizations/peerOrganizations/org1.example.com/connection-org1.json
echo "$(yaml_ccp $ORG $CAPORT $CAPEM)" > organizations/peerOrganizations/org1.example.com/connection-org1.yaml
