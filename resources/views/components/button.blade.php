<!--
Mögliche Attribute:

name & help
Beides muss gesetzt sein, damit beim hover über den Button die Info angezeigt wird. Wenn name gesetzt ist, kann auf slot angabe des button namens verzichtet werden

id:
setzt die Id des Buttons

primary:
Der Button erscheint in der Primary-Color

action:
Wenn das Attribut $action gesetzt ist, wird die angegebene Livewire-Aktion beim Klicken auf den Button ausgelöst.

type:
type definiert bestimmte Stile des Buttons:
- delete: hier wird ein roter Button angezeigt
- withBorder: hier hat der Button einen Border

inputType:
Dieses Attribut kann verwendet werden, wenn ein Button in einem Input verwendet wird und eine andere als die Standard-Action submit hinterlegt werden soll

class:
Dieses Attribut ermöglicht es, zusätzliche CSS-Klassen für den Button hinzuzufügen, um das Erscheinungsbild zu stylen. Die vorhandene Logik verwendet die Tailwind CSS-Klassen, um verschiedene Stile für den Button zu definieren,

onlyOnce:
Wenn das Attribut $onlyOnce gesetzt ist, wird durch Klicken auf den Button ein JavaScript-Event ausgelöst, das den Button nach einem Klick deaktiviert.

async:
Wenn das Attribut $async gesetzt ist, wird der Button während die in action definierte Methode durchgeführt wird, deaktiviert und es erscheint ein Ladebalken

asyncVariable:
Wenn das Attribut $asyncVariable gesetzt ist, wird der Button solange asyncVariable true ist, deaktiviert und ein Ladebalken angezeigt
-->
<button
		@if(isset($name) && isset($help))
		@mouseover="selectedField = '{{$name}}'; helpText = '{{$help}}'"
		@endif
		@if(isset($id)) id="{{$id}}" @endif
		@if(isset($inputType)) type="{{$inputType}}" @endif
		@if(isset($action)) wire:click="{{$action}}" @endif class="{{isset($class)?$class:''}} inline-flex items-center py-2 text-sm font-semibold
		@if(isset($primary))
			rounded-md bg-maincolor px-3 text-white shadow-sm hover:bg-maincolorh focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-maincolor
		@elseif(isset($type))
			w-full justify-center rounded-md px-3 shadow-sm sm:w-auto
			@if ($type == 'delete')
				bg-red-600 text-white hover:bg-red-500 sm:ml-3
			@elseif ($type == 'withBorder')
				bg-white text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0
			@endif
		@else
			text-gray-900
		@endif
		" @if(isset($onlyOnce)) x-data="{ clicked: false }" x-on:click="clicked = true" x-bind:disabled="clicked" @endif
	@if(isset($async)) wire:loading.attr="disabled" @endif @if(isset($asyncVariable) && $asyncVariable) disabled @endif>

	@if(isset($name))
	{{$name}}
	@else
	{{$slot}}
	@endif

	@if(isset($async) || (isset($asyncVariable) && $asyncVariable))
	<svg class="animate-spin h-5 w-5 ml-3" viewBox="0 0 24 24" fill="none" @if(isset($async)) wire:loading
		wire:target="{{$action}}" @endif>
		<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
		<path class="opacity-75" fill="currentColor"
			d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
		</path>
	</svg>
	@endif

</button>
