<li class="relative md:flex md:flex-1"

{{-- Um direkt über die Navigation zu einzelnen Seiten springen zu können, hier ein true eintragen --}}
@if(false)
wire:click="redirectTo('{{$redirectTo}}')"
@endif

>


	@if($status == 'completed')

	<!-- Completed Step -->
	<a href="#" class="group flex w-full items-center cursor-default">
		<span class="flex items-center px-6 py-4 text-sm font-medium ">
			<span
				class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-maincolor group-hover:bg-maincolorh">
				<svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
					<path fill-rule="evenodd"
						d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z"
						clip-rule="evenodd" />
				</svg>
			</span>
			<span class="ml-4 text-sm font-medium text-gray-900">{{$description}}</span>
		</span>
	</a>

	@elseif($status == 'current')

	<!-- Current Step -->
	<a href="#" class="flex items-center px-6 py-4 text-sm font-medium cursor-default" aria-current="step">
		<span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border-2 border-maincolor">
			<span class="text-maincolor">{{$step}}</span>
		</span>
		<span class="ml-4 text-sm font-medium text-maincolor">{{$description}}</span>
	</a>

	@elseif($status == 'upcoming')

	<!-- Upcoming Step -->
	<a href="#" class="group flex items-center cursor-default">
		<span class="flex items-center px-6 py-4 text-sm font-medium">
			<span
				class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border-2 border-gray-300 group-hover:border-gray-400">
				<span class="text-gray-500 group-hover:text-gray-900">{{$step}}</span>
			</span>
			<span class="ml-4 text-sm font-medium text-gray-500 group-hover:text-gray-900">{{$description}}</span>
		</span>
	</a>

	@endif

	@if(!($laststep ?? false))

	<!-- Arrow separator for lg screens and up -->
	<div class="absolute right-0 top-0 hidden h-full w-5 md:block" aria-hidden="true">
		<svg class="h-full w-full text-gray-300" viewBox="0 0 22 80" fill="none" preserveAspectRatio="none">
			<path d="M0 -2L20 40L0 82" vector-effect="non-scaling-stroke" stroke="currentcolor"
				stroke-linejoin="round" />
		</svg>
	</div>

	@endif

</li>
