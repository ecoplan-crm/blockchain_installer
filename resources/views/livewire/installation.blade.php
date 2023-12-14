<div>
    <div class="space-y-12">
        <div class="mx-auto max-w-4xl border-b border-gray-900/10 pb-12">
            <h2 class="text-base font-semibold leading-7 text-gray-900">Technik und Installationsschritte</h2>
            <div class="mt-1 text-sm leading-6 text-gray-600">
                Die Installation eines Hyperledger Fabric Netzwerkes kann grundsätzlich auf verschiedenen
                Betriebssystemen erfolgen. Für die vorgegebene Installationsroutine gibt es jedoch Einschränkungen. Der
                Hauptcomputer muss eine Ubuntu 22.x-Maschine sein. Der Betrieb ist auch auf virtuellen Computern
                möglich. Die mit zusätzlichen Peers beitretenden Computer müssen ebenfalls mit Ubuntu 22.x betrieben
                werden. Hier ist jedoch auch Ubuntu unter WSL2 möglich.

                <img class="max-w-[500px]" src="/images/Hauptrechner_ZusatzPeers.jpg">

                Die Hardwareanforderungen hängen von der Größe
                des Netzwerks und der erwarteten Transaktionslast ab. In der Regel benötigen sie Server oder virtuelle
                Maschinen, um die verschiedenen Knoten (Peers und Orderer) auszuführen. Diese Server sollten über
                ausreichend Rechenleistung, Speicherplatz und Netzwerkbandbreite verfügen, um den Betrieb des
                Testnetzwerks zu unterstützen. Netzwerkinfrastruktur: Ein funktionierendes Netzwerk ist erforderlich,
                damit die Knoten miteinander kommunizieren können. Dies umfasst die Konfiguration von Firewall-Regeln,
                Ports und Netzwerkverbindungen.
            </div>

            <div class="block text-sm font-medium leading-6 text-gray-900 mt-5">
                Technische Voraussetzungen
            </div>


            <div class="mt-1 text-sm leading-6 text-gray-600">
                Spezielle Anforderung an den Hauptcomputer:
                Es muss eine echte Ubuntu-Maschine sein (kein WSL2).
            </div>

            <div class="mt-1 text-sm leading-6 text-gray-600">
                Generelle Anforderungen (an Hauptcomputer und alle weiteren PC’s mit Peers):

                <ul class="list-disc pl-5">
                    <li>Ubuntu 22.x</li>
                    <li>Git</li>
                    <li>Docker (mindestens 20.10.25)</li>
                    <li>Docker-Compose (mindestens 1.29.2)</li>
                    <li>Hyperledger Fabric 2.4.9</li>
                    <li>Php 8.1 (inkl. der Erweiterungen xml, curl, cli, zip)</li>
                    <li>Node.js</li>
                    <li>Jq</li>
                    <li>Java (OpenJDK 8)</li>
                    <li>Nvm (0.39.4)</li>
                    <li>Node 18.x</li>
                    <li>Npm 10.x</li>
                    <li>Composer</li>
                </ul>

                Über das mitgelieferte Installationsskript werden die oben genannten Komponenten automatisch
                installiert, so dass lediglich eine Ubuntu 22.x Umgebung notwendig ist.
            </div>
        </div>
        <div class="border-b border-gray-900/10 pb-12">
            <!--<h2 class="text-base font-semibold leading-7 text-gray-900 mt-5">Installationsschritte</h2>-->
            <img src="/images/Installationsschritte.png">
            <div class="mt-1 text-sm leading-6 text-gray-600">

                <ol class="custom-list-decimal">
                    <li>
                        Im ersten Schritt „<u>Parameter Erfassung</u>“ werden diverse Parameter für die Konfiguration
                        der Blockchain abgefragt. Z.B. die Anzahl der Peers, die auf dem Blockchain-Server (Ubuntu)
                        gestartet
                        werden sollen. Auf dem Hauptrechner (Ubuntu) müssen mindestens zwei Peers laufen. Der Name des
                        Kanals (Channel) der erstellt werden soll, sowie der Chaincode-Name und die Version.
                    </li>
                    <li>
                        Im zweiten Schritt „<u>Erzeuge Konfiguration</u>“ werden die benötigten
                        Standard-Konfigurationsdateien für die Installation der Hyperledger Fabric Blockchain erzeugt.
                        Mit den generierten Konfigurationsdateien wird das Fabric Netzwerk gestartet. Das Hyperledger
                        Fabric Netzwerk basiert auf Docker-Containern. Docker ist ein beliebtes Open-Source-Tool, das
                        eine portable und konsistente Laufzeitumgebung für Softwareanwendungen bietet. Docker verwendet
                        dabei Container als isolierte Umgebungen im Benutzerraum, die auf Betriebssystemebene ausgeführt
                        werden und das Dateisystem sowie die Systemressourcen gemeinsam nutzen.
                    </li>
                    <li>
                        Mit dem Schritt „<u>Starte Netzwerk</u>“ wird das Hyperledger Fabric Blockchain-Netzwerk
                        aktiviert.
                        Über die im vorherigen Schritt erzeugten Konfigurationsdateien werden alle Docker-Container
                        gestartet. Dadurch wird das Hyper Ledger Blockchain-Netzwerk automatisch hochgefahren. Die
                        Docker-Container bauen die notwendigen Verbindungen zueinander auf. Die Peers treten dem
                        definierten Channel bei.
                    </li>
                    <li>
                        Nach Start des Netzwerks sind alle Komponenten aktiv, aber enthalten noch keine
                        Anwendungslogik (Chaincode). In der Phase „<u>Deploy Chaincode</u>“ wird der Anwendungscode nun
                        auf die erstellten Peers verteilt und instanziiert.
                    </li>
                    <li>
                        Über diesem Installationsschritt kann die Anzahl der „<u>Peers</u>“ bestimmt werden, die von
                        anderen Computern dem Blockchain-Netzwerk beitreten sollen. Der Hauptrechner (Ubuntu) enthält bereits im
                        Standard zwei Peers. Für die Anzahl der definierten Peers, die dem Netzwerk zusätzlich beitreten
                        sollen, werden von der Installationsroutine getrennte ZIP-Pakete für die Installation
                        bereitgestellt. Die Pakete können dann auf die anvisierten Computer verteilt werden. Dieser
                        Prozess wird über den Befehl „Blockchain beitreten“ über das Hauptmenü der Installation
                        angestoßen.
                    </li>
                    <li>
                        Ist der Chaincode (Anwendungscode) erfolgreich auf die Peers verteilt und instanziiert, kann
                        über eine <u>Testoberfläche</u> die Hyperledger Fabric Blockchain in Betrieb genommen werden. Es
                        können Testwerte auf die Blockchain geschrieben und gelesen werden.
                    </li>
                </ol>
            </div>
        </div>

        <div class="border-b border-gray-900/10 pb-12">
            <!--<h2 class="text-base font-semibold leading-7 text-gray-900 mt-5">Installationsschritte</h2>-->
            <img class="max-w-[500px]" src="/images/Beitreten.png">
            <br>
            <div class="mt-1 text-sm leading-6 text-gray-600">

                Ist das Hyperledger Fabric Blockchain Netzwerk erfolgreich auf dem Hauptrechner aufgesetzt, können
                weitere Peers von anderen Computern über diesen Menüpunkt dem Blockchain-Netzwerk beitreten.

                <ol class="custom-list-decimal">
                    <li>
                        Im ersten Schritt „<u>Konfiguration</u>“ werden die aus dem jeweiligen ZIP-Paket entpackten
                        Konfigurationsdateien hochgeladen.
                    </li>
                    <li>
                        Im Anschluss kann der Peer in das aktive „<u>Netzwerk</u>“ eingegliedert werden.
                    </li>
                    <li>
                        Um die entsprechende Funktionalität des Peers zu ermöglichen, wird an diese Stelle der
                        „<u>Chaincode</u>“ installiert und instanziiert.
                    </li>
                    <li>
                        Über die <u>Testoberfläche</u> können nun Testwerte auf die Blockchain geschrieben und gelesen
                        werden.
                    </li>
                </ol>
            </div>
        </div>
    </div>

    <div class="border-b border-gray-900/10 pb-12">
            <h2 class="text-base font-semibold leading-7 text-gray-900 mt-5">Fehlersuche</h2>
            <br>
            <div class="mt-1 text-sm leading-6 text-gray-600">

                Nachfolgend werden Fehler aufgeführt, die während der Benutzung auftreten können. Es wird eine mögliche Ursache aufgeführt und eine Empfehlung zur Lösung des Problems vorgeschlagen.
                <div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <table class="min-w-full divide-y divide-gray-300">
          <thead>
            <tr>
              <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Fehler</th>
              <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Ursache</th>
              <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Lösung</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr>
              <td class="py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-0 align-baseline">Nachdem das „Netzwerk starten“-Skript erfolgreich beendet wurde, laufen noch nicht alle Docker-Container (z.B. CA Orderer oder CA Org)</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Vor Beginn des Prozesses wurde das Netzwerk nicht zurückgesetzt. Dadurch waren beim Start noch Artefakte eines vorherigen Netzwerkes vorhanden, die einen sauberen Start verhindert haben.</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Klicke auf der Startseite auf den Button „Netzwerk zurücksetzen“ und starte den Prozess von vorne.</td>
            </tr>

            <tr>
              <td class="py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-0 align-baseline">Fehlermeldung: "../asset-my/chaincode: no such file or directory"</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Vor Beginn des Prozesses wurde das Netzwerk nicht (erfolgreich) zurückgesetzt. Im Zurücksetzungsprozess werden u.a. auch alle benötigten Ordner und Dateien lokal an den vorgesehenen Pfaden abgelegt. Wird das Netzwerk nicht zurückgesetzt, ist nicht sichergestellt, dass die Dateien an den richtigen Stellen abliegen.</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Klicke auf der Startseite auf den Button „Netzwerk zurücksetzen“ und starte den Prozess von vorne.</td>
            </tr>

            <tr>
              <td class="py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-0 align-baseline">Fehlermeldung "chaincode install failed with status: 500 - failed to invoke backing implementation of 'InstallChaincode': chaincode already successfully installed"</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Vor Beginn des Prozesses wurde das Netzwerk nicht (erfolgreich) zurückgesetzt. Die Docker-Container sind mit Volumes eines früheren Betriebs gestartet, wodurch noch der Chaincode der letzten Ausführung installiert ist.</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Klicke auf der Startseite auf den Button „Netzwerk zurücksetzen“ und starte den Prozess von vorne.</td>
            </tr>

            <tr>
              <td class="py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-0 align-baseline">Fehlermeldung "error: [DiscoveryResultsProcessor]: parseDiscoveryResults[cdfghannedfglabcd] - Channel:cdfghannedfglabcd received discovery error:access denied"</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Vor Beginn des Prozesses wurde das Netzwerk nicht (erfolgreich) zurückgesetzt. Beim Zurücksetzen wird u.a. auch der Wallet-Ordner des NodeJS-Servers bereinigt. Wenn dies nicht erfolgt ist, dann kann es zu dieser Fehlermeldung kommen, da die Dateien dann nicht die nötigen Rechte besitzen.</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Klicke auf der Startseite auf den Button „Netzwerk zurücksetzen“ und starte den Prozess von vorne.
Alternativ kann man manuell den Wallet-Ordner leeren (siehe Pfad in .env Datei)</td>
            </tr>

            <tr>
              <td class="py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-0 align-baseline">Fehlermeldung "FAILED to run the application: Error: Identity not found in wallet: appUser_2"</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Der Wallet-Ordner wurde gelöscht, obwohl das Netzwerk noch läuft.</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Klicke auf der Startseite auf den Button „Netzwerk zurücksetzen“ und starte den Prozess von vorne.</td>
            </tr>

            <tr>
              <td class="py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-0 align-baseline">Fehlermeldung "hyperledger fabric Channel credentials must be a ChannelCredentials object"</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Die Hosts-Einträge wurden nicht korrekt angelegt
</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Klicke auf der Startseite auf den Button „Netzwerk zurücksetzen“ und starte den Prozess von vorne.
Alternativ:
In der /etc/hosts Datei einen Eintrag für orderer.example.com anlegen, der auf den Orderer verweist (auf dem Hauptcomputer 127.0.0.1)</td>
            </tr>

            <tr>
              <td class="py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-0 align-baseline">Fehlermeldung "500: cannot create ledger from genesis block: ledger [mychannel] already exists with state [ACTIVE]"</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Vor Beginn des Prozesses wurde das Netzwerk nicht zurückgesetzt oder es laufen auf dem PC parallele Blockchains </td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Stelle sicher, dass keine anderen Hyperledger Fabric Netzwerke laufen, klicke auf der Startseite auf den Button „Netzwerk zurücksetzen“ und starte den Prozess von vorne.</td>
            </tr>

            <tr>
              <td class="py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-0 align-baseline">Fehlermeldung "peer1.org1.example.com: Name or service not known"</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Die Hosts-Einträge wurden nicht korrekt angelegt</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Klicke auf der Startseite auf den Button „Netzwerk zurücksetzen“ und starte den Prozess von vorne.
Alternativ:
In der /etc/hosts Datei einen Eintrag für peer1.org1.example.com anlegen, der auf den Peer verweist (auf dem Hauptcomputer 127.0.0.1)</td>
            </tr>

            <tr>
              <td class="py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-0 align-baseline">Beim Klick auf „Netzwerk zurücksetzen“ passiert nichts</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Bei der Einrichtung wurden die Dateien nicht korrekt berechtigt</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Führe folgende Befehle in deinem Blockchain-Ordner aus:
sudo chmod -R 777 digi3_laravel
sudo chmod -R 777 fabric-samples</td>
            </tr>

            <tr>
              <td class="py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-0 align-baseline">Beim Klick auf „Netzwerk zurücksetzen“ passiert nichts und in der Konsole der Browser-Entwicklungstools wird für die ausgelesene URL null angezeigt</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Der PHP-Cache ist nicht mehr aktuell</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Führe folgenden Befehl aus
php artisan config:clear
</td>
            </tr>

            <tr>
              <td class="py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-0 align-baseline">Fehlermeldung "asset-my/chaincode/build/script/* Operation not permittet "</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Das Netzwerk wurde nicht (erfolgreich) zurückgesetzt.
</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Klicke auf der Startseite auf den Button „Netzwerk zurücksetzen“ und starte den Prozess von vorne.</td>
            </tr>

            <tr>
              <td class="py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-0 align-baseline">Fehlermeldung "error: [DiscoveryResultsProcessor]: _buildOrderer[channel] - Unable to connect to the discovered orderer orderer.example.com:7050 due to TypeError: Channel credentials must be a ChannelCredentials object"</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Die Node-Bibliothek grpc ist mit unterschiedlichen Versionen eingebunden (ggf. durch ein automatisches Update eines der Packages)</td>
              <td class="px-3 py-4 text-sm text-gray-500 align-baseline">Eingebundene Packages auf Versionsunterschiede überprüfen; in der package.json ggf. auf Vorlage aus Git-Repository zurückändern und mit npm install die Abhängigkeiten neu installieren</td>
            </tr>


          </tbody>
        </table>

                </div>
                </div>
                </div>

        </div>
    </div>

    <div class="mt-6 flex items-center justify-end gap-x-6">
        <x-button action="redirectTo('/information')">Hyperledger Fabric Blockchain - Komponenten, Funktionsweise
        </x-button>
        <x-button action="redirectTo('/')" primary>Zur Startseite</x-button>
    </div>

</div>
