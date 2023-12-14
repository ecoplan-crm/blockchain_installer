<div x-data="{ edit: @entangle('edit'), deleteModal: @entangle('deleteModal') }">
    <div>
        <div class="space-y-12">
            <div class="relative border-b border-gray-900/10 pb-12">

                <x-help>
                    Über die Testoberfläche können Werte auf die Hyperledger Fabric Blockchain geschrieben und gelesen
                    werden.<br>
                    <br>
                    Dem Anwender stehen drei Buttons zur Verfügung.<br>
                    <br>
                    <u>Ledger mit Testdaten befüllen</u>:<br>
                    Dieser Menüpunkt lässt sich nur einmal ausführen und füllt das Ledger mit vorkonfigurierten
                    Testdaten.<br>
                    <br>
                    <u>Neuen Datensatz anlegen</u>:<br>
                    Über diesen Befehl können sie einen neuen Datensatz im Ledger anlegen.<br>
                    <br>
                    <u>Aktualisieren</u>:<br>
                    Aktualisieren der Bildschirmansicht mit den aktuellen Ledger-Daten des Blockchain-Netzwerks.
                    Standradmäßig wird
                    die Ansicht für den Anwender nur aktualisiert, wenn selbst eine Aktion auf dem Ledger ausführt. Wenn
                    ein anderer
                    Peer im Netzwerk eine Änderung vornimmt, dann wird diese nicht automatisch angezeigt. Um diese
                    Änderung sehen zu
                    können, muss man die Ansicht manuell aktualisieren.<br>
                    <br>

                    <img src="/images/Install_Phasen.gif" />
                    <br>

                    <span class="font-medium text-gray-900">Begriffserklärung Ledger (Hauptbuch)</span><br>
                    In Hyperledger Fabric gibt es bei der Datenspeicherung zwei Teilkomponenten, die eng miteinander
                    verbunden sind. Das
                    Ledger (Hauptbuch) und der WorldState (Weltzustand). Diese beiden Komponenten spielen eine wichtige
                    Rolle bei der
                    Verwaltung und Aufrechterhaltung des Zustands und der Transaktionshistorie eines
                    Blockchain-Netzwerks. <br>
                    <br>
                    <span class="font-medium text-gray-900">Ledger</span><br>
                    Das Ledger, auch als Hauptbuch bezeichnet, ist der Kernbestandteil einer jeden Blockchain-Plattform.
                    Es umfasst eine
                    unveränderliche Aufzeichnung aller Transaktionen, die jemals durchgeführt wurden. Es dient als
                    chronologische
                    Aufzeichnung, die alle Transaktionsdetails und deren Reihenfolge enthält. Das Ledger ist in zwei
                    Teile unterteilt,
                    dem World State (Weltzustand) und der Transaktionshistorie.<br>
                    <br>
                    <span class="font-medium text-gray-900">World State</span><br>
                    Der World State ist ein zentraler Bestandteil des Ledgers von Hyperledger Fabric. Er enthält den
                    <u>aktuellen
                        Zustand</u> aller Assets und Verträge in der Blockchain. Der World State kann als aktueller,
                    "live" Zustand
                    verstanden werden, der den aktuellen Status aller Daten in der Blockchain darstellt. Anstatt die
                    gesamte
                    Transaktionshistorie erneut durchzugehen, um den aktuellen Zustand eines Assets zu ermitteln, kann
                    dieser direkt aus
                    dem Weltzustand abgerufen werden. Der World State wird in einer optimierten Datenbank gespeichert,
                    die den Zugriff
                    auf die aktuellen Daten beschleunigt. Dies ist besonders nützlich, wenn es viele Transaktionen und
                    Assets gibt.<br>
                    <br>
                    Zusammenfassend kann gesagt werden, dass der Ledger in Hyperledger Fabric Transaktionshistorie und
                    World State
                    kombiniert. Während die Transaktionshistorie alle Transaktionen speichert, die jemals auf der
                    Blockchain
                    durchgeführt wurden, ermöglicht der World State den schnellen Zugriff auf den aktuellen Zustand
                    aller Assets und
                    Verträge, ohne die gesamte Transaktionshistorie durchgehen zu müssen. Dieses Design hilft, die
                    Effizienz und
                    Leistungsfähigkeit von Hyperledger Fabric zu erhöhen.
                    <br>
                    <img src="/images/Ledger.png">
                    <br>
                    Die Ansicht der Testoberfläche enthält den Inhalt des World States.<br>
                    <br>
                    <img src="/images/worldstate.png" />
                    <br>
                    <u>Bearbeiten</u><br>
                    Beim Bearbeiten wird eine neue Transaktion im Ledger abgespeichert. Der bisherige Wert bleibt in der
                    Transaktionskette erhalten. Im World State wird der Wert aktualisiert und somit hier in der
                    Oberfläche angezeigt.<br>
                    <br>
                    <u>Löschen</u><br>
                    Beim Löschen wird eine neue Transaktion angelegt, die aussagt das der Datensatz gelöscht wurde. Der
                    bisherige Wert bleibt in der Transaktionskette unwiderruflich erhalten. Aufgrund der neuen
                    Transaktion wird jedoch der World State aktualisiert und dadurch wird das Asset hier in der Ansicht
                    nicht mehr angezeigt.<br>
                    <br>
                    <u>Session in Cookie abspeichern</u><br>
                    Um nach Beenden der Applikation und späteren Neustart der Software wieder direkt zur Testoberfläche
                    zu navigieren muss ein „Cookie“ über den Befehl „Session in Cookie abspeichern“ erstellt werden.<br>
                    <br>
                    Auf der Startseite der Installationssoftware erscheint dann der Hinweis: „Es wurde ein
                    Session-Cookie
                    gefunden. Dort weitermachen wo Sie aufgehört haben: Zur TestApp ->“.
                    Bei Auswahl des Buttons „Zur TestApp->“ wird die Installation übersprungen und Sie gelangen direkt
                    zur TestApp.<br>
                    <br>
                    <img src="/images/ZurTestApp.png" />
                    <br>


                </x-help>

                <h2 class="text-base font-semibold leading-7 text-gray-900">Testoberfläche</h2>
                <p class="mt-1 text-sm leading-6 text-gray-600">Hier haben Sie die Möglichkeit, die Daten der Blockchain
                    zu bearbeiten.</p>

                <div class="my-5 flex gap-6 justify-left items-center">
                    <x-button onlyOnce async action="init" name="Ledger mit Testdaten befüllen"
                        help="Über diesen Button können Sie Testdaten in das Ledger einfügen. Dieser Button is nur einmalig aufrufbar.">
                    </x-button>
                    <x-button action="newEntry" primary name="Neuen Datensatz anlegen"
                        help="Über diesen Button können Sie einen eigenen neuen Datensatz in das Ledger einfügen.">
                    </x-button>
                    <x-button async action="refreshWithTiming" name="Aktualisieren"
                        help="Dieser Button aktualisiert die Anzeige des Ledgers."></x-button>

                    <div class="ml-auto text-sm leading-6 text-gray-600">Letzte Ausführungszeit: <span
                            id="ausfuehrungszeit">{{
                number_format($executionTime, 2, '.', '.') }}</span> s</div>
                </div>

                <div class="-mx-4 mt-8 sm:-mx-0">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                            <tr>
                                <th scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">ID
                                </th>
                                <th scope="col"
                                    class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                                    Farbe</th>
                                <th scope="col"
                                    class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">
                                    Größe</th>
                                <th scope="col"
                                    class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">
                                    Eigenümer
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Wert
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                    <span class="sr-only">Aktionen</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($data as $entry)
                            <livewire:record :entry="$entry" :wire:key="implode('', $entry)" />
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <x-button class="" action="saveToCookie" name="Session in Cookie abspeichern"
                help="Über einen Klick auf diesen Button werden alle Session-Parameter in einen Cookie abgespeichert. Sie können nun die Website verlassen und zu einem späteren Zeitpunkt wieder zu dieser Website zurückkehren und an der Stelle weitermachen, an der Sie aufgehört haben. Dieser Cookie wird gelöscht, sobald Sie auf der Startseite den Button >>Netzwerk zurücksetzen<< betätigen.">
            </x-button>
            @if(isset($cookieSavedMessage) && $cookieSavedMessage !== "")
            <p class="text-sm leading-6 text-gray-600 ">
                {{ $cookieSavedMessage }}
            </p>
            @endif
            <div class="mr-auto"></div>
            <x-button action="back">Zurück</x-button>
            <x-button action="next" primary>Zur Startseite</x-button>
        </div>
    </div>

    <div x-cloak x-show="edit" class="fixed top-0 left-0 pointer-events-auto w-screen h-screen max-w-md"
        x-transition:enter="transform transition ease-in-out duration-500" x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="-translate-x-0" x-transition:leave="transform transition ease-in-out duration-500"
        x-transition:leave-start="-translate-x-0" x-transition:leave-end="-translate-x-full">
        <form class="flex h-full flex-col divide-y divide-gray-200 bg-white shadow-xl" wire:submit.prevent=""
            wire:keydown.enter="save">
            <div class="h-0 flex-1 overflow-y-auto">
                <div class="bg-maincolor px-4 py-6 sm:px-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-semibold leading-6 text-white" id="slide-over-title">
                            Datensatz</h2>
                        <div class="ml-3 flex h-7 items-center">
                            <button wire:click="cancel" action="cancel" type="button"
                                class="rounded-md bg-maincolor text-maincolorh hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
                                <span class="sr-only">Close panel</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="mt-1">
                        <p class="text-sm text-white">Verwalte hier die Daten deines Datensatzes.
                        </p>
                    </div>
                </div>
                <div class="flex flex-1 flex-col justify-between">
                    <div class="divide-y divide-gray-200 px-4 sm:px-6">
                        <div class="space-y-6 pb-5 pt-6">

                            <div>
                                <x-input id="assetID" type="text" name="ID" help="Deine Hilfe ;)"
                                    :isDisabled="$isNewAsset ? null : true" />
                            </div>

                            <div>
                                <x-input id="color" type="text" name="Farbe" help="Deine Hilfe zur color ;)" />
                            </div>

                            <div>
                                <x-input id="size" type="number" name="Größe" help="Deine Hilfe zur size ;)" />
                            </div>

                            <div>
                                <x-input id="owner" type="text" name="Eigentümer" help="Deine Hilfe zum owner ;)" />
                            </div>

                            <div>
                                <x-input id="appraisedValue" type="number" name="Wert"
                                    help="Deine Hilfe zum appraisedValue ;)" />
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            @if(isset($errorMessage))
            <div class="p-4">
                <span class="text-sm text-red-500">{{ $errorMessage }}</span>
            </div>
            @endif

            <div class="flex flex-shrink-0 justify-end px-4 py-4 gap-6">
                <x-button action="cancel">Abbrechen</x-button>
                <x-button action="save" async primary>Speichern</x-button>
            </div>
        </form>
    </div>



    <div x-cloak x-show="deleteModal" class="relative z-10" aria-labelledby="modal-delete" role="dialog"
        aria-modal="true" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-cloak x-show="deleteModal"
                    class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="oopacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Eintrag löschen
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Möchten Sie {{$assetID}} wirklich löschen?</p>
                                <br>
                                <p class="text-sm text-gray-500">Beim Löschen wird eine neue Transaktion angelegt, die
                                    aussagt das der Datensatz
                                    gelöscht wurde. Der bisherige Wert bleibt in der Transaktionskette unwiderruflich
                                    erhalten. Aufgrund der neuen Transaktion wird jedoch der World State aktualisiert
                                    und dadurch wird das Asset hier in der Ansicht nicht mehr angezeigt.</p>
                            </div>

                            @if(isset($errorMessage))
                            <div class="mt-2">
                                <span class="text-sm text-red-500">{{ $errorMessage }}</span>
                            </div>
                            @endif
                        </div>
                    </div>


                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <x-button action="deleteEntry" type="delete" async>Löschen</x-button>
                        <x-button action="cancel" type="withBorder">Abbrechen</x-button>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
