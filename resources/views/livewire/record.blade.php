<tr>
    <td class="w-full max-w-0 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:w-auto sm:max-w-none sm:pl-0">
        {{$entry["assetID"]}}
        <dl class="font-normal lg:hidden">
            <dd class="mt-1 truncate text-gray-700">{{$entry["color"]}}</dd>
            <dd class="mt-1
        truncate text-gray-500 sm:hidden">{{$entry["size"]}}</dd>
            <dd class="mt-1 truncate text-gray-500 sm:hidden">
                {{$entry["owner"]}}</dd>
        </dl>
    </td>
    <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">{{$entry["color"]}} </td>
    <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">{{$entry["size"]}}</td>
    <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">{{$entry["owner"]}}</td>
    <td class="px-3
                py-4 text-sm text-gray-500">{{$entry['appraisedValue']}}</td>
    <td class="py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
        <a @click=" edit=true" wire:click="setData"
            @mouseover="selectedField='Bearbeiten'; helpText='Beim Bearbeiten wird eine neue Transaktion im Ledger abgespeichert. Der bisherige Wert bleibt in der Transaktionskette erhalten. Im World State wird der Wert aktualisiert und somit hier in der Oberfläche angezeigt.'"
            href="#" class="text-maincolor hover:text-maincolorh">Bearbeiten</a>
    </td>
    <td @click="deleteModal=true" wire:click="setData"
        @mouseover="selectedField = 'Löschen'; helpText = 'Beim Löschen wird eine neue Transaktion angelegt, die aussagt das der Datensatz gelöscht wurde. Der bisherige Wert bleibt in der Transaktionskette unwiderruflich erhalten. Aufgrund der neuen Transaktion wird jedoch der World State aktualisiert und dadurch wird das Asset hier in der Ansicht nicht mehr angezeigt.'"
        class="px-3 py-4 text-gray-500 hover:text-red-600 hover:cursor-pointer"> <svg xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26
                        9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244
                        2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12
                        .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5
                        0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09
                        2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
        </svg>


    </td>
</tr>
