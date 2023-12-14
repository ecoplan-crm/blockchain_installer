<div>
    <div class="space-y-12">
    <div class="relative border-b border-gray-900/10 pb-12">

        <x-help>

        @if(session('newNetwork'))

        Über den Befehl „Netzwerk starten“ wird das Startskript des Netzwerks aufgerufen und die Peers, Orderer, CA und
        CLI werden gestartet.
        Rechts in der Infobox werden die gestarteten Docker-Container angezeigt.

        Die CA Org, CLI und der Orderer (Bestelldienst) mit dem Raft-Protokoll werden gestartet.

        Die einzelnen Peers des Hauptrechners
        treten dem Channel (Kanal) bei und sind dadurch miteinander verbunden. Auf dem Hauptrechner (Ubuntu-Server)
        müssen mindestens 2 Peers vorhanden sein, da bei der Transaktionsverarbeitung mindestens 1 Peer zustimmen muss.
        Die gestarteten Peers sind an dieser Stelle dem Netzwerk beigetreten, verfügen aber noch über keine
        Funktionalität. Die Bereitstellung der Funktionalität der Peers erfolgt im nächsten Schritt über die Übertragung
        des Chaincodes.
        <br>
        Nachfolgend die Container, die bei der Minimalkonfiguration mit zwei Peers gestartet werden sollten:<br>
        <br>
        <img class="max-w-xs" src="/images/ContainerInstallStartNetzwork.png">

        @else

        Der neue Peer wird an dieser Stelle in das Netzwerk eingegliedert. Dafür wird der bereits vorkonfigurierte
        Docker-Container gestartet und der Prozess zu dem Beitritt des erstellten Channels angestoßen. Hierfür nimmt der
        Peer Kontakt zum Orderer und dem Hauptrechner auf und klingt sich in das Peer-Netzwerk ein. Auf der rechten
        Bildschirmseite wird nach Erfolg der gestartete Docker-Container angezeigt.

        @endif
        </x-help>

        <h2 class="text-base font-semibold leading-7 text-gray-900">Netzwerk</h2>



        <p class="mt-1 text-sm leading-6 text-gray-600">

            @if(session('newNetwork'))

            In diesem Schritt wird das Hyperledger Fabric Netzwerk hochgefahren.

            @else

            In diesem Schritt wird der neue Peer in das Netzwerk eingegliedert.

            @endif

        </p>


        <div class="flex gap-6 mt-5">

            @if(session('newNetwork'))

            <form class="script">
                <input name="script" hidden
                    value="{{Config::get('ecoplan.network_directory')}}/network.sh up createChannel -myDomain peer0.org1.example.com -ip 127.0.0.1 -c {{session('channelName')}} -ca -oc 1 -pc {{session('peerCount')}}" />
                <x-button action="start" :asyncVariable="$starting" name="Netzwerk starten"
                    help="Das Hyperledger Fabric Netzwerk wird hochgefahren." primary></x-button>
            </form>

            @else

            <form class="script">
                <input name="script" hidden
                    value="{{Config::get('ecoplan.network_directory')}}/startPeerAndJoinChannel.sh" />
                <x-button action="start" :asyncVariable="$starting" primary name="Peer starten"
                    help="Der Peer wird gestartet."></x-button>
            </form>

            @endif

            <x-button action="clearLog" name="Log leeren" help="Die Log-Ausgabe wird geleert."></x-button>

        </div>

        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <div class="sm:col-span-6">
                <x-logs id="logOutput" name="Log-Ausgaben">
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
