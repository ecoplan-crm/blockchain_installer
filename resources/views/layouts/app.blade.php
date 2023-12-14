<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    @vite('resources/css/app.css') @livewireStyles
    @vite('resources/js/app.js')
</head>

<body class="h-full">

    <div class="min-h-full">
        <header class="bg-maincolor pb-24">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8 h-28 flex lg:block lg:h-auto">
                <div
                    class="relative flex items-center justify-center py-5 lg:justify-between absolute w-full lg:relativ">

                    <div class="absolute left-0 flex-shrink-0 lg:static">
                        <a href="/?ignoreSessionCookie">
                            <h1 class="text-white text-2xl font-bold uppercase">Hyperledger Fabric Blockchain Installer
                            </h1>
                        </a>
                    </div>

                </div>
                <div class="hidden border-t border-white border-opacity-20 py-5 lg:block">
                    <div class="grid grid-cols-3 items-center gap-8">

                        @if (!Request::is('/'))
                        @livewire('navigation')
                        @endif

                    </div>
                </div>
            </div>


        </header>
        <main class="-mt-24 pb-8" x-data="{ selectedField: '', helpText: '' }">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <!-- Main 3 column grid -->
                <div class="grid grid-cols-1 items-start gap-4 lg:grid-cols-3 lg:gap-8">
                    <!-- Left column -->
                    <div class="grid grid-cols-1 gap-4 lg:col-span-2">
                        <section aria-labelledby="section-1-title">
                            <div class="overflow-hidden rounded-lg bg-white shadow">
                                <div class="p-6">

                                    @if (Session::has('newNetwork') || Request::is('/'))
                                    {{ $slot }}
                                    @else
                                    <meta http-equiv="refresh" content="0;url=/">
                                    @endif

                                </div>
                            </div>
                        </section>
                    </div>

                    <!-- Right column -->
                    <div class="grid grid-cols-1 gap-4 sticky top-8">

                        <section aria-labelledby="section-help">
                            <div class="overflow-hidden rounded-lg bg-white shadow">
                                <div class="p-6">
                                    <div class="space-y-6">
                                        <div class="">
                                            <h2 class="text-base font-semibold leading-7 text-gray-900">Hilfe</h2>
                                            <p class="mt-1 text-sm leading-6 text-gray-600">Klicke
                                                mit deiner Maus in ein Feld oder bewege die Maus über einen Button, um
                                                hier erweiterte Informationen zu
                                                erhalten.</p>
                                        </div>

                                        <div x-cloak x-show="selectedField && helpText">
                                            <h3 class="block text-sm font-medium leading-6 text-gray-9000"
                                                x-text="selectedField"></h3>
                                            <div class="mt-1 text-sm leading-6 text-gray-600" x-html="helpText"></div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </section>

                        <section aria-labelledby="section-docker">
                            <div class="overflow-hidden rounded-lg bg-white shadow">
                                <div class="p-6">
                                    <div class="space-y-4">
                                        <div class="">
                                            <h2 class="text-base font-semibold leading-7 text-gray-900">Docker-Container
                                            </h2>
                                            <p class="mt-1 text-sm leading-6 text-gray-600">Hier erscheinen die
                                                gestarteten Docker-Container.</p>
                                        </div>

                                        <livewire:docker />

                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <div
                    class="flex flex-wrap justify-between items-center border-t border-gray-200 py-8 text-center text-sm text-gray-500 sm:text-left">
                    <span class="block sm:inline">&copy; 2023 Ecoplan GmbH</span> <span class="block sm:inline"></span>
                    <img class="w-[500px]" src="/images/logo-digitales-hessen.svg">
                    <img class="w-[350px]" src="images/Ecoplan-Logo.png" />
                </div>

            </div>
        </footer>
    </div>

    @stack('modals')

    @livewireScripts
</body>

</html>
