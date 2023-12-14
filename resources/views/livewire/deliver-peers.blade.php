<div>

    <div class="space-y-12">
        <div class="relative border-b border-gray-900/10 pb-12">

        <x-help>
            Über das Eingabefeld „Anzahl Peer-Pakte“ wird festgelegt, wie viele Peers dem Blockchain-Netzwerk zusätzlich
            von anderen Computern aus beitreten sollen. <br>
            <br>
            <img src="/images/Hauptrechner_ZusatzPeers.jpg">
            <br>
            Der Hauptrechner (Ubuntu) enthält bereits zwei Peers, wenn Sie
            keine weiteren Peers benötigen können Sie diesen Schritt einfach übergehen.
            Das Feld „Server-IP“ umfasst die IP-Adresse des Hauptrechners (Ubuntu) auf dem die Blockchain-Basis
            installiert ist.
            Die Anzahl der gewünschten Peers entspricht in Folge den generierten und bereitgestellten ZIP-Paketen.
            <br>
            <br>
            <img src="/images/PeerPakete.png">
            <br>
            In den ZIP-Files ist die komplette Ordnerstruktur des Netzwerks hinterlegt sowie alle Zertifikate, der
            Channel- und Chaincode-Name und alle Konfigurationsfiles.
            Die automatisch erstellten Pakete können dann auf die anvisierten Computer verteilt werden.
            Der Prozess zum Beitritt zur aktiven Hyperledger Fabric Blockchain wird über den Hauptmenüpunkt
            „Netzwerk beitreten“ angestoßen. Die ZIP-Datei wird dann auf dem Zielrechner extrahiert und die
            Skript-Dateien werden dann automatisch ausgeführt.

        </x-help>

        <h2 class="text-base font-semibold leading-7 text-gray-900">Peers im Netzwerk verteilen</h2>
        <p class="mt-1 text-sm leading-6 text-gray-600">Hier haben Sie die Möglichkeit vorkonfigurierte Peers für
            den Einsatz auf anderen Maschienen herunterzuladen. Die Angabe der IP-Adresse dieses Servers ist
            notwendig. Es muss die IP-Adresse sein, über welche die anderen Maschienen diese Maschine finden können.
        </p>

        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <x-input id="peerPackages" name="Anzahl Peer-Pakete" type="number" min=0 max=20
                    help="Hierüber definieren Sie die Anzahl der Peer-Pakete, die generiert werden sollen.">
                </x-input>
            </div>
            <div class="sm:col-span-3">
                <x-input id="serverIP" name="Server-IP" type="text"
                    help="Hier muss die IP-Adresse dieses Servers eingegeben werden. Die zusätzlichen Peers müssen diesen Server über diese IP adressieren können.">
                </x-input>
            </div>
        </div>

        @if ($this->isServerIpSet())
        <div class="flex flex-wrap gap-6 mt-5">
            @for ($i = 1; $i <= $peerPackages; $i++) <x-button action="downloadPeer({{session('peerCount') + $i - 1}})"
                primary>Peer
                {{session('peerCount') + $i - 1}} herunterladen</x-button>
                @endfor
        </div>
        @endif

        @if ($peerPackages == 0)
        <div class="mt-1 text-sm leading-6 text-green">Wenn Sie keine weiteren Peers auf anderen Maschienen
            installieren möchten, dann können Sie diesen Schritt überspringen.</div>
        @endif

    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-x-6">
    <x-button action="back">Zurück</x-button>
    <x-button action="next" primary>Weiter</x-button>
</div>

</div>
