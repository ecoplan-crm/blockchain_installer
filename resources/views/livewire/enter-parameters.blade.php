<div>

    <div>

    </div>

    @if(session('newNetwork'))
    <div class="space-y-12">
        <div class="border-b border-gray-900/10 pb-12 relative">
            <x-help>
                Über die Eingabemaske „Parameter erfassen“ werden diverse Basisparameter für das Hyperledger Fabric
                Netzwerk erfasst. Die Anzahl der Peers bezieht sich auf die Peers, die direkt auf dem Hauptrechner
                (Ubuntu) laufen.<br>
                <br>
                <img src="/images/Hauptrechner_ZusatzPeers.jpg">
                <br>
                Es müssen mindestens 2 Peers eingestellt werden. Jedem Peer ist die Rolle „Endorser“
                und „Committer“ zugeordnet. Die Endorsement-Richtlinie wird automatisch installiert. Im
                Endorsing-Prozess muss immer nur ein weiterer Peer zustimmen. Die Anzahl der Peers, die auf zusätzlichen
                anderen Computern betrieben werden sollen, können im Installationsschritt „05 Peers“ definiert werden.
                Es wird eine Organisation mit einem Channel (Kanal) installiert. Geben sie einen Namen für den Channel
                ein. Der Chaincode wird aktuell in der Sprache Java erstellt. Geben Sie eine beliebige Bezeichnung für
                den Chaincode ein. Die Version sollte mit 1.0 beginnen. Für die Sequenz ist für die aktuelle Version der
                Installationsroutine immer eine 1 einzutragen.
                Der Parameter Speicherort definiert den Ort im Dateisystem, in welchem der Chaincode abgelegt ist. Der
                Pfad muss relativ zum Speicherort des Netzwerk-Ordners angegeben werden. Der vorkonfigurierte Chaincode
                liegt im voreingestellten Verzeichnis ab. Für die Standard-Installation sollte an dieser Stelle keine
                Änderung vorgenommen werden.
            </x-help>
            <h2 class="text-base font-semibold leading-7 text-gray-900">Parameter erfassen</h2>


            <p class="mt-1 text-sm leading-6 text-gray-600">In diesem Schritt werden die Basis-Parameter für das
                Hyperledger Fabric Netzwerk erfasst. Der wichtigste Parameter ist hierbei die Anzahl der Peers; dieser
                definiert, wie viele lokale Peers auf dem Server gestartet werden sollen. Alle anderen Paremeter dienen
                hauptsächlich internen Prozessen und sind für den Testbetrieb zweitrangig zu betrachten.</p>

            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <x-input id="peerCount" name="Anzahl Peers" type="number"
                        help="Über diesen Parameter definieren Sie die Anzahl der Peers, die direkt beim Start des Netzwerkes gestartet werden sollen. Es sind aufgrund der Endorsment-Policy mindestens zwei Peers notwendig, da bei jeder Aktion mindestens ein anderer Perr zustimmen muss.">
                    </x-input>
                </div>
            </div>

            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <x-input id="channelName" name="Channel-Name" type="text"
                        help="In dieses Feld geben Sie die Bennung des Channels (Kanal) ein. Alle Peers werden dann über diesen Channel kommunizieren.">
                    </x-input>
                </div>
            </div>
        </div>


        <div class="border-b border-gray-900/10 pb-12">

            <h2 class="text-base font-semibold leading-7 text-gray-900">Chaincode</h2>
            <p class="mt-1 text-sm leading-6 text-gray-600">Die Parameter des Chaincodes können hier konfiguriert
                werden. Alle Felder sind so vorbefüllt, dass diese mit dem vorgegebenen Netzwerk harmonieren.</p>

            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                <div class="sm:col-span-3">
                    <label for="chaincode-language"
                        class="block text-sm font-medium leading-6 text-gray-900">Sprache</label>
                    <div class="mt-2">
                        <select id="chaincode-language" wire:model="chaincodeLanguage" autocomplete="chaincode-language"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-maincolor sm:max-w-xs sm:text-sm sm:leading-6"
                            x-on:click="selectedField = 'Sprache'; helpText = 'Chaincode kann in verschiedenen Sprachen bereitgestellt werden. Im vorkonfigurierten Nezwerk ist außschließlich Java-Chaincode hinterlegt, weswegen an dieser Stelle aktuell noch keine Wahlmöglichkeit besteht.';">
                            <option>Java</option>
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-2 sm:col-start-1">
                    <x-input id="chaincodeName" name="Name" type="text"
                        help="Der Chaincode-Name beschreibt den internen Name des Chaincodes im Hyperledger Fabric Netzwerk. Bei Aktionen auf dem Ledger muss dieser mitangegeben werden, um zu definieren, wie die eingegebenen Daten verarbeitet werden sollen.">
                    </x-input>
                </div>

                <div class="sm:col-span-2">
                    <x-input id="chaincodeVersion" name="Version" type="text"
                        help="Die Chaincode-Version dient der eigenen Versionierung (des Chaincode-Entwicklers). Diese wird im Deploy-Prozess an den Chaincode-Namen angehängt und bildet zusammen mit ihm den Gesamtnamen des Chaincodes.">
                    </x-input>
                </div>

                <div class="sm:col-span-2">
                    <x-input id="chaincodeSequenz" name="Sequenz" type="number"
                        help="Die Sequenz ist für den Abgleich der Chaincodes im Peer-Netzwerk relevant. Wenn ein Chaincode initial auf einem Peer bereitgestellt wird, muss dieser die Sequenz 1 haben. Beim Einspielen einer aktualisierten Version des Chaincodes muss dieser dann die Sequenz 2 haben usw.">
                    </x-input>
                </div>

                <div class="col-span-full">
                    <x-input id="chaincodeDirectory" name="Speicherort" type="text"
                        help="Der Speicherort definiert, wo der Chaincode im Verzeichnis abgelegt ist. Die Angabe an dieser Stelle erfolgt relativ zum my-network-Verzeichnis, in welchem die Skripte ausgeführt werden. Die Vorbefüllung ist für den zur Verfügung gestellten Chaincode bereits korrekt.">
                    </x-input>
                </div>
            </div>
        </div>
    </div>

    @endif

    <div class="mt-6 flex items-center justify-end gap-x-6">
        <x-button action="redirectTo('/?ignoreSessionCookie')" class="mr-auto">Zurück zur Startseite</x-button>
        <x-button action="resetForm" name="Zurücksetzen"
            help="Über diesen Knopf werden die Eingaben in der Maske auf die Standardwerte zurückgesetzt."></x-button>
        <x-button action="next" primary>Weiter</x-button>
    </div>
</div>
