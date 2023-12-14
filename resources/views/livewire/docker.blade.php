<div>
	<ul id="docker" class="grid grid-cols-1 gap-2">
		@foreach ($containers as $container)
		<x-container name="{{ $container['Names'][0] }}" status="{{ $container['Status'] }}" />
		@endforeach
	</ul>
</div>