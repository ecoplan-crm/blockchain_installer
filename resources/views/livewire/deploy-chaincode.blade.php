<div>
    <div class="space-y-12">
        <div class="relative border-b border-gray-900/10 pb-12">

            <x-help>

            @if(session('newNetwork'))

                In diesem Schritt wird der Chaincode kompiliert, gepackt, auf die Peers verteilt, installiert und
                instanziiert. In der Infobox rechts wird der Chaincode als Docker-Container angezeigt. Für zwei Peers
                als Bezeichnung Chaincode 0 und Chaincode 1.
                Wenn in der linken Infobox die Meldung: „Skriptausführung beendet“ erscheint und keine Fehlermeldung
                angezeigt wurde, ist der Chaincode erfolgreich auf die Peers installiert und instanziiert.

                @else

                Der Chaincode aus dem extrahierten ZIP-File wir auf dem Peer installiert und instanziiert.<br>
                <br>
                <img src="/images/ChaincodeBeitritt.png">

                @endif

            </x-help>

            <h2 class="text-base font-semibold leading-7 text-gray-900">Chaincode</h2>
            <p class="mt-1 text-sm leading-6 text-gray-600">
                @if(session('newNetwork'))
                In diesem Schritt wird der Chaincode auf die Peers verteilt.
                @else
                In diesem Schritt wird der Chaincode auf dem neuen Peer zur
                Verfügung gestellt.
                @endif
            </p>

            <div class="flex gap-6 mt-5">

                @if(session('newNetwork'))

                <form class="script">
                    <input name="script" hidden
                        value="{{Config::get('ecoplan.network_directory')}}/network.sh deployCC -oc 1 -pc {{session('peerCount')}} -c {{session('channelName')}} -ccn {{session('chaincodeName')}} -ccl {{session('chaincodeLanguage')}} -ccv {{session('chaincodeVersion')}} -ccs {{session('chaincodeSequenz')}} -ccp {{session('chaincodeDirectory')}}" />
                    <x-button action="start" :asyncVariable="$deploying" name="Chaincode bereitstellen"
                        help="Der Chaincode wird auf die Peers verteilt und installiert." primary>Chaincode
                        bereitstellen</x-button>
                </form>

                @else

                <form class="script">
                    <input name="script" hidden
                        value="{{Config::get('ecoplan.network_directory')}}/installChaincode.sh" />
                    <x-button action="start" :asyncVariable="$deploying" primary name="Chaincode bereitstellen"
                        help="Der Chaincode wird auf dem neuen Peer bereitgestellt."></x-button>
                </form>

                @endif

                <x-button action="clearLog" name="Log leeren" help="Die Log-Ausgabe wird geleert.">Log leeren</x-button>

            </div>

            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-6">
                    <x-logs id="logOutput" name="Log-Ausgaben" help="Dies ist der Hilfetext für Element 1">
                        {!!$logContent!!}
                    </x-logs>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 flex items-center justify-end gap-x-6">
        <x-button action="back">Zurück</x-button>
        <x-button action="next" primary>Weiter</x-button>
    </div>




</div>
