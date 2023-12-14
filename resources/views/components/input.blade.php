<label for="{{$id}}" class="block text-sm font-medium leading-6 text-gray-900">{{$name}}</label>
<div class="mt-2">
	<input type="{{$type}}" wire:model="{{$id}}" id="{{$id}}" autocomplete="{{$id}}" name="{{$id}}" @if(isset($min)) min={{$min}} @endif @if(isset($max)) max={{$max}} @endif
		@if(isset($isDisabled)) disabled @endif x-on:click="selectedField = '{{$name}}'; helpText = '{{$help}}';"
		class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-maincolor sm:text-sm sm:leading-6 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200">
	@error($id) <span class="text-sm text-red-500">{{ $message }}</span> @enderror
</div>
