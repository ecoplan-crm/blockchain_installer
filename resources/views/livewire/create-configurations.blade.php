<div>
    <div class="space-y-12">
    <div class="relative border-b border-gray-900/10 pb-12">
        <x-help>

        @if(session('newNetwork'))

        Hyperledger Fabric verwendet Docker zum Starten der notwendigen Komponenten. Die Konfigurationen der
        Docker-Container werden in Docker-Compose-Yaml-Dateien angegeben. Diese Dateien enthalten alle für das
        Starten der Netzwerk-Dienste notwendigen Parameter. Für die Konfiguration des Netzwerkes an sich gibt es
        die configtx-yaml-Datei.<br>
        <br>
        <b>Konfigurationsdateien:</b><br>
        <br>
        <u>docker-compose-test-net.yaml</u>:<br>
        Diese Datei wird verwendet, um die Client-Docker-Container zu definieren Sie enthält Informationen über
        die am Netzwerk teilnehmenden Peers und über den CLI-Container.<br>
        <br>
        <u>compose-test-net.yaml</u>:<br>
        In dieser Konfigurationsdatei werden die Peers, der Orderer sowie das ganze Netzwerk definiert und die
        Umgebungsvariablen gesetzt. Es ist die Hauptkonfigurationsdatei für das Setzen der Umgebungsvariablen.
        Es werden die Arbeitsverzeichnisse gesetzt, die Ports definiert.<br>
        <br>
        <u>compose-ca.yaml</u>:<br>
        Die Datei compose-ca.yaml wird verwendet, um Container bereitzustellen, die eine Zertifizierungsstelle
        simuliert und verwaltet. Die Zertifizierungsstelle ist eine vertrauenswürdige Instanz, die digitale
        Zertifikate ausstellt und verwaltet. Diese Zertifikate werden für die sichere Kommunikation im
        Blockchain-Netzwerk benötigt. In dieser Datei werden die benötigten Zertifizierungsdocker definiert für
        ca.org1 und für den Orderer.<br>
        <br>
        <u>config.tx.yaml</u>:<br>
        Diese Datei definiert die Konfiguration für das Channel- und Orderer-System. Sie enthält Informationen
        über die Organisationen, Ordnungsknoten, Konsensmechanismus, Profile und Policies. Mit dieser
        Konfigurationsdatei wird der Orderer Einzelknoten Raft-Bestelldienst erstellt.<br>
        <br>
        Die configtx.yaml-Datei in Hyperledger Fabric wird verwendet, um die Konfiguration des
        Blockchain-Netzwerks zu definieren. Folgende Punkte sind Bestandteil der Datei:<br>
        <br>
        <ol class="custom-list-decimal text-sm text-gray-500">
            <li>Organizations: Hier werden die beteiligten Organisationen und ihre Richtlinien definiert. Dies
                umfasst sowohl die Orderer-Organisation als auch die Peer-Organisationen.</li>
            <li>Capabilities: Dies definiert die Fähigkeiten des Netzwerks in Bezug auf Versionen. Sie können
                verschiedene Versionen für Channel, Orderer und Anwendungen aktivieren.</li>
            <li>ApplicationDefaults: Hier können Sie die Standardeinstellungen für Anwendungen, wie Richtlinien
                für Leser, Schreiber, Admins und Endorsement, definieren.</li>
            <li>OrdererDefaults: Dies enthält die Standardeinstellungen für den Orderer, einschließlich des
                Orderer-Typs, der Batch-Timeouts und -Größen sowie der Konsensermechanismus-Details.</li>
            <li>ChannelDefaults: Dies sind die Standardeinstellungen für Channels, einschließlich Richtlinien
                für Leser, Schreiber und Admins.</li>
        </ol><br>
        <p class="text-sm text-gray-500">
            Die erzeugten Konfigurationsdateien werden in einer Infobox, die aufklappbar ist, angezeigt.
        </p>

        @else

        Um mit einem anderen Computer dem Blockchain-Netzwerk beizutreten sind verschiedene Schritte erforderlich. Die
        ZIP-Datei, die während des Haupt-Installationsprozesses der Blockchain über den Schritt „Peers“ erstellt wurde
        und auf den Ziel-Computer heruntergeladen wurde, kann an dieser Stelle auf dem beitretenden Computer hochgeladen
        werden. Dadurch werden alle Konfigurationen automatisch gesetzt und der Chaincode Name wird in der Session
        abgelegt. Ist auf dem beitretenden Computer vorher schon einmal eine Konfiguration hochgeladen worden, ist es
        notwendig das Verzeichnis zu bereinigen. Die Bereinigung erfolgt im Startbildschirm über den Button „Netzwerk
        zurücksetzen“. Wie dort beschrieben, ist es immer erforderlich, vor einer Neuinstallation oder einem Beitritt
        das Netzwerk zuerst zurückzusetzen. Auch wenn zum ersten Mal eine Installation oder ein Beitritt erfolgt.

        @endif

        </x-help>
        <h2 class="text-base font-semibold leading-7 text-gray-900">Konfigurationsdateien</h2>

        @if(session('newNetwork'))

        <p class="mt-1 text-sm leading-6 text-gray-600">In diesem Schritt werden die Konfigurationsdateien für die
            Hyperledger Fabric Blockchain erzeugt. Diese umfassen im wesentlichen die Docker-Container-Konfiguration
            der zum Betrieb nötigen Netzwerkkomponenten. Nach Erzeugung der Konfigurationsdateien kann man über
            einen Klick auf den Dateinamen in den Inhalt der Datei hineinschauen.</p>

        <div class="flex gap-6 mt-5 mb-5">
            <x-button class="" action="createConfigurations" name="Konfigurationsdateien erzeugen"
                    help="Über einen Klick auf diesen Button werden die darunter angezeigten Konfigurationsdateien erzeugt." primary></x-button>
            @if(isset($message) && $message !== "")
            <p class="mt-1 text-sm leading-6 text-gray-600">{{$message}}</p>
            @endif
        </div>


        <form class="mt-5 space-y-6 divide-y divide-gray-900/10">

            <!-- docker-compose-test-net.yaml -->
            <dl>
                <div x-data="{ isOpen: false }" :aria-checked="isOpen" class="pt-6">
                    <dt @click="isOpen = !isOpen">
                        <button type="button" class="flex w-full items-start justify-between text-left text-gray-900"
                            aria-controls="faq-0" aria-expanded="false">
                            <span
                                class="block text-sm font-medium leading-6 text-gray-900">docker-compose-test-net.yaml</span>
                            <span class="ml-6 flex h-7 items-center">
                                <svg :class="{'hidden': isOpen, '': !isOpen }" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                </svg>
                                <svg :class="{'': isOpen, 'hidden': !isOpen }" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
                                </svg>
                            </span>
                        </button>
                    </dt>
                    <dd :class="{'': isOpen, 'hidden': !isOpen }" class="mt-2 pr-12" id="faq-0">
                        <x-textarea id="dockerComposeTestNet" name="docker-compose-test-net.yaml" disabled>
                            In der Datei docker-compose-test-net.yaml werden die Docker-Grundkonfigurationen für die
                            Peers und die CLI definiert.
                        </x-textarea>
                    </dd>
                </div>
            </dl>


            <!-- compose-test-net.yaml -->
            <dl>
                <div x-data="{ isOpen: false }" :aria-checked="isOpen" class="pt-6">
                    <dt @click="isOpen = !isOpen">
                        <button type="button" class="flex w-full items-start justify-between text-left text-gray-900"
                            aria-controls="faq-0" aria-expanded="false">
                            <span class="block text-sm font-medium leading-6 text-gray-900">compose-test-net.yaml</span>
                            <span class="ml-6 flex h-7 items-center">
                                <svg :class="{'hidden': isOpen, '': !isOpen }" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                </svg>
                                <svg :class="{'': isOpen, 'hidden': !isOpen }" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
                                </svg>
                            </span>
                        </button>
                    </dt>
                    <dd :class="{'': isOpen, 'hidden': !isOpen }" class="mt-2 pr-12" id="faq-0">
                        <x-textarea id="composeTestNet" name="compose-test-net.yaml" disabled>
                            In der Datei compose-test-net.yaml werden die Hauptkonfigurationen für die Peers, die
                            Orderer und die CLI konfiguriert. Hier werden unter anderem die Umgebungsvariablen
                            gesetzt und die Ports definiert.
                        </x-textarea>
                    </dd>
                </div>
            </dl>


            <!-- compose-ca.yaml -->
            <dl>
                <div x-data="{ isOpen: false }" :aria-checked="isOpen" class="pt-6">
                    <dt @click="isOpen = !isOpen">
                        <button type="button" class="flex w-full items-start justify-between text-left text-gray-900"
                            aria-controls="faq-0" aria-expanded="false">
                            <span class="block text-sm font-medium leading-6 text-gray-900">compose-ca.yaml</span>
                            <span class="ml-6 flex h-7 items-center">
                                <svg :class="{'hidden': isOpen, '': !isOpen }" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                </svg>
                                <svg :class="{'': isOpen, 'hidden': !isOpen }" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
                                </svg>
                            </span>
                        </button>
                    </dt>
                    <dd :class="{'': isOpen, 'hidden': !isOpen }" class="mt-2 pr-12" id="faq-0">
                        <x-textarea id="composeCa" name="compose-ca.yaml" disabled>
                            In der Datei compose-ca.yaml werden die Docker-Container für die CA-Dienste
                            konfiguriert.
                        </x-textarea>
                    </dd>
                </div>
            </dl>

            <!-- configtx.yaml -->
            <dl>
                <div x-data="{ isOpen: false }" :aria-checked="isOpen" class="pt-6">
                    <dt @click="isOpen = !isOpen">
                        <button type="button" class="flex w-full items-start justify-between text-left text-gray-900"
                            aria-controls="faq-0" aria-expanded="false">
                            <span class="block text-sm font-medium leading-6 text-gray-900">configtx.yaml</span>
                            <span class="ml-6 flex h-7 items-center">
                                <svg :class="{'hidden': isOpen, '': !isOpen }" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                </svg>
                                <svg :class="{'': isOpen, 'hidden': !isOpen }" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
                                </svg>
                            </span>
                        </button>
                    </dt>
                    <dd :class="{'': isOpen, 'hidden': !isOpen }" class="mt-2 pr-12" id="faq-0">
                        <x-textarea id="configtx" name="configtx.yaml" disabled>

                            Die configtx.yaml-Datei in Hyperledger Fabric wird verwendet, um die Konfiguration des
                            Blockchain-Netzwerks zu definieren. Folgende Punkte sind Bestandteil der Datei:
                            <ol class="custom-list-decimal">
                                <li><b>Organizations</b>: Hier werden die beteiligten Organisationen und ihre
                                    Richtlinien definiert. Dies umfasst sowohl die Orderer-Organisation als auch die
                                    Peer-Organisationen.</li>

                                <li><b>Capabilities</b>: Dies definiert die Fähigkeiten des Netzwerks in Bezug auf
                                    Versionen. Sie können verschiedene Versionen für Channel, Orderer und
                                    Anwendungen aktivieren.</li>

                                <li><b>ApplicationDefaults</b>: Hier können Sie die Standardeinstellungen für
                                    Anwendungen, wie Richtlinien für Leser, Schreiber, Admins und Endorsement,
                                    definieren.</li>

                                <li><b>OrdererDefaults</b>: Dies enthält die Standardeinstellungen für den Orderer,
                                    einschließlich des Orderer-Typs, der Batch-Timeouts und -Größen sowie der
                                    Konsensermechanismus-Details.</li>

                                <li><b>ChannelDefaults</b>: Dies sind die Standardeinstellungen für Channels,
                                    einschließlich Richtlinien für Leser, Schreiber und Admins.</li>
                            </ol>
                        </x-textarea>
                    </dd>
                </div>
            </dl>

        </form>

        @else

        <p class="mt-1 text-sm leading-6 text-gray-600">In diesem Schritt werden die Konfigurationsdateien
            hochgeladen. <br>Wichtig: Es muss sichergestellt sein, dass das Netzwerk hiervor zurückgesetzt wurde!
        </p>

        <div class="flex flex-wrap gap-6 mt-5">

            {{-- <x-button action="clearInstallationPath">Verzeichnis bereinigen</x-button> --}}

            <form action="{{ route('upload.zip') }}" method="post" enctype="multipart/form-data" id="upload-form">
                @csrf
                <input id="file-input" class="hidden" type="file" name="zip_file" accept=".zip">
                <x-button id="upload-button" inputType="button" primary name="Konfigurationsdateien hochladen"
                    help="Nach Betätigung dieses Buttons öffnet sich ein Datei-Auswahlfenster. Hier wählen Sie das zuvor vom Server übertragene Peer-Paket aus. Dieses wird im Anschluss hochgeladen, extrahiert und zur Ausführung berechtigt.">
                </x-button>
            </form>

            <script>
                const uploadButton = document.getElementById('upload-button');
                const fileInput = document.getElementById('file-input');
                const uploadForm = document.getElementById('upload-form');

                uploadButton.addEventListener('click', () => {
                    fileInput.click(); // Öffnet das Datei-Auswahlfenster
                });

                fileInput.addEventListener('change', () => {
                    if (fileInput.files.length > 0) {
                        uploadForm.submit(); // Sendet das Formular, nachdem die Datei ausgewählt wurde
                    }
                });
            </script>

            @if(Session::has('message') && session('message') !== '')
            <p class="mt-1 text-sm leading-6 text-gray-600">
                {{session('message')}}
            </p>
            @endif
        </div>



        @endif
    </div>
</div>


<div class="mt-6 flex items-center justify-end gap-x-6">
    <x-button action="back">Zurück</x-button>
    <x-button action="next" primary>Weiter</x-button>
</div>

</div>
