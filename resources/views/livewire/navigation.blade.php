	<nav x-data="{ showURLAlert: @entangle('showURLAlert') }" class="col-span-3">
        <ol role="list"
            class="divide-y divide-gray-300 rounded-md border border-gray-300 md:flex md:divide-y-0  bg-white">

            @foreach ($nav as $point)
            @if ($point['condition'])
            @php
            $count++;
            @endphp
            <x-item-progress step="0{{$count}}" description="{{$point['description']}}" status="{{$point['status']}}"
                redirectTo="{{$point['redirectTo']}}" laststep="{{$point['laststep']}}" />
            @endif
            @endforeach

        </ol>


    @php
    $currentUrl = request()->fullUrl();
    $parsedUrl = parse_url($currentUrl);
    $domain = isset($parsedUrl['host']) ? $parsedUrl['host'] : '';
    @endphp

    @if (filter_var($domain, FILTER_VALIDATE_IP))
    <div x-show="showURLAlert" class="relative z-10" aria-labelledby="modal-delete" role="dialog"
        aria-modal="true" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="showURLAlert"
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
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Achtung!
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Die Seite wurde über eine IP-Adresse geladen. Damit Anfragen
                                    an den Node.js-Server gesendet werden können, muss die Seite entweder über localhost
                                    oder über eine eigene URL geladen werden.</p>
                            </div>
                        </div>
                    </div>


                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <x-button action="reloadWithAppURL" type="delete">Seite neu über AppURL laden</x-button>
                        <x-button action="ignore" type="withBorder">Warnung ignorieren</x-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    </nav>
