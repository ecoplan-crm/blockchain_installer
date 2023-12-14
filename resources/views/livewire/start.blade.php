<div>

    <div class="space-y-12">
        <div class="border-b border-gray-900/10 pb-12">

            <img src="/images/blockchain.jpg" alt="Blockchain">
            <span class="text-sm text-xs text-gray-600">Quelle:
                https://pixabay.com/de/illustrations/blockchain-daten-datens%C3%A4tze-konzept-4077256/</span>
            <br><br>

            <p class="mt-1 text-sm leading-6 text-gray-600">Mit dem vorliegenden Implementierungstool soll der
                Installationsprozess der Hyperledger Fabric Blockchain vereinfacht und beschleunigt werden. Es soll eine
                gezielte Wissensvermittlung zu den im Hintergrund ablaufenden Prozessen und der Technologie erfolgen.
                Das Tool führt die wesentlichen Schritte der Hyperledger Fabric Blockchain-Installation unter einer
                Oberfläche zusammen. Der User wird dabei Schritt für Schritt durch die Anwendung geführt, um die
                komplexen Prozesse der Installation zu verstehen. Kontextabhängige Hilfen und Grafiken unterstützen den
                User bei jedem einzelnen Schritt. Am Ende werden fertige Pakete mit gepackten Dateien (Docker-Container)
                über einen Download bereitstehen, die dann auf die einbezogenen Rechner verteilt und ausgeführt werden
                können. Zielanwender sind IT-System-Administratoren, sowie IT-Fachpersonal kleiner und mittlerer
                Unternehmen und anderer Organisationen. Nach dem Aufsetzen der Blockchain kann der Anwender über eine
                Testoberfläche Werte auf die Blockchain schreiben und lesen. So können erste Erfahrungen mit der
                Blockchain gesammelt werden.</p>

            <div class="block text-sm font-medium leading-6 text-gray-900 mt-5 mb-2">Hilfe, Hintergründe und technische
                Abläufe:</div>
            <x-button type="withBorder" action="redirectTo('/information')">Hyperledger Fabric Blockchain - Komponenten,
                Funktionsweise</x-button>
            <x-button type="withBorder" action="redirectTo('/installation')">Technik und
                Installationsschritte</x-button>


            <div class="block text-sm font-medium leading-6 text-gray-900 mt-5 mb-1">Achtung</div>
            <p class="text-sm leading-6 text-gray-600">
                Vor Beitritt oder Start eines Netzwerkes muss dieses immer zuerst zurückgesetzt werden, auch
                wenn es der erste Durchlauf ist!
            </p>

            @if($this->isSessionCookieSet())

            <div class="rounded-md bg-green-50 p-4 mt-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 md:flex md:justify-between">
                        <p class="text-sm text-green-700">Es wurde ein Session-Cookie gefunden. Dort weitermachen, wo
                            Sie aufgehört haben:</p>
                        <p class="mt-3 text-sm md:ml-6 md:mt-0">
                            <a href="/testApp"
                                class="whitespace-nowrap font-medium text-green-700 hover:text-green-600"
                                @mouseover="selectedField = 'Zur TestApp'; helpText = 'Es wurde ein Session-Cookie in Ihrem Browser gefunden. Das bedeutet, dass die Umgebungsvariablen aus einer früheren Installation noch vorhanden sind. Wenn Sie weiterarbeiten möchten, dann klicken Sie auf diesen Button, um direkt zur TestApp zu gelangen. Ansonsten setzten Sie das Netzwerk zurück. Dabei wird der Cookie gelöscht und diese Meldung verschwindet.'">
                                Zur TestApp
                                <span aria-hidden="true"> &rarr;</span>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @endif

    </div>


    <div class="mt-6 flex items-center justify-end gap-x-6">
        <x-button class="mr-auto" action="resetNetwork" async name="Netzwerk zurücksetzen"
            help="Der Menü-Befehl Netzwerk zurücksetzen stoppt und entfernt die Knoten- und Chaincode-Container, löscht das Kryptomaterial der Organisation. Der Befehl entfernt außerdem die Kanalartefakte und Docker-Volumes aus früheren Ausführungen, sodass Sie die Ausführung erneut ausführen können. Zum Schluss wird der gesamte Netzwerk- und Asset-Ordner gelöscht und neu aus dem Repository geladen.">
        </x-button>
        <x-button action="joinNetwork" name="Einem Netzwerk beitreten"
            help="Über diesen Button können Sie einem bestehenden Netzwerk beitreten. Bitte beachten Sie, dass das System bereinigt sein muss (Netzwerk zurücksetzen)!">
        </x-button>
        <x-button action="installNetwork" primary name="Installation starten"
            help="Über diesen Button können Sie ein neues Netzwerk aufestzen. Bitte beachten Sie, dass das System bereinigt sein muss (Netzwerk zurücksetzen)!">
        </x-button>
    </div>

</div>
