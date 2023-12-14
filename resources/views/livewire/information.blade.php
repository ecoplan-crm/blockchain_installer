<div>
    <div class="mx-auto max-w-4xl divide-y divide-gray-900/10 border-b border-gray-900/10 pb-12">
        <h2 class="text-base font-semibold leading-7 text-gray-900">Hyperledger Fabric Blockchain - Komponenten, Funktionsweise</h2>
        <dl class="mt-10 space-y-6 divide-y divide-gray-900/10">

            @foreach ($blocks as $block)
            <div x-data="{ isOpen: false }" :aria-checked="isOpen" class="pt-6">
                <dt @click="isOpen = !isOpen">
                    <button type="button" class="flex w-full items-start justify-between text-left text-gray-900"
                        aria-controls="faq-0" aria-expanded="false">
                        <span class="block text-sm font-medium leading-6 text-gray-900">{!! $block['headline'] !!}</span>
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
                    <div class="mt-1 text-sm leading-6 text-gray-600">{!! $block['content'] !!}</div>
                </dd>
            </div>
            @endforeach

        </dl>


    </div>

    <div class="mt-6 flex items-center justify-end gap-x-6">
        <x-button action="redirectTo('/')">Zur Startseite</x-button>
        <x-button action="redirectTo('/installation')" primary>Technik und Installationsschritte</x-button>
    </div>

</div>
