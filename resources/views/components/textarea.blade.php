<div class="mt-2">
	<textarea rows="6" wire:model="{{$id}}" id="{{$id}}" name="{{$id}}"
		x-on:click="selectedField = '{{$name}}'; helpText = '{{preg_replace('/[\r\n]*/','',$slot)}}'; console.log(helpText);" @if (isset($disabled)) readonly @endif
		class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-maincolor sm:text-sm sm:leading-6"></textarea>
	@error($id) <span class="text-sm text-red-500">{{ $message }}</span> @enderror
</div>
