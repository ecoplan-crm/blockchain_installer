<div x-data="{showModal: false}"> <div @click="showModal=true" class="absolute right-0 p-2 hover:bg-maincolor rounded-md
    text-black hover:text-white cursor-pointer"> <div class="flex items-center	">
    <div class="mr-2 text-sm ">
        Weitere Hilfe
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
        class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
    </svg>
</div>
</div>

<div x-cloak x-show="showModal" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">

    <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

            <div x-show="showModal" x-transition:enter="ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl sm:p-6">
                <div>

                    <div class="">
                        <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Weitere
                            Informationen</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                {{$slot}}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex mt-5 sm:mt-6 w-full justify-end">
                    <button @click="showModal = false" type="button"
                        class="inline-flex justify-center rounded-md bg-maincolor px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-maincolorh focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-maincolor">
                        Schließen
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
