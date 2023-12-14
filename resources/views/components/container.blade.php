@if(Str::contains($name, 'dev-peer'))
@php
$peerNumber = Str::substr($name, 9, 1);
$name = 'Chaincode ' . $peerNumber;
$initial = 'C' . $peerNumber;
$color = 'bg-black';
@endphp

@elseif(Str::contains($name, 'peer'))
@php
$peerNumber = Str::substr($name, 5, 1);
$name = 'Peer ' . $peerNumber;
$initial = 'P' . $peerNumber;
$color = 'bg-greenD';
@endphp

@elseif(Str::contains($name, 'orderer.example.com'))
@php
$name = 'Orderer';
$initial = 'OR';
$color = 'bg-yellowD';
@endphp

@elseif(Str::contains($name, 'ca_'))
@if(Str::contains($name, 'org'))
@php $caType = 'Org' @endphp
@elseif(Str::contains($name, 'orderer'))
@php $caType = 'Orderer' @endphp
@endif
@php
$name = 'CA ' . $caType;
$initial = 'CA';
$color = 'bg-purpleD';
@endphp

@elseif(Str::contains($name, 'cli'))
@php
$name = 'CLI';
$initial = 'CI';
$color = 'bg-pinkD';
@endphp

@else
@php
$initial = '--';
$color = 'bg-black';
@endphp
@endif

<li class="col-span-1 flex rounded-md shadow-sm">
	<div
		class="flex w-16 flex-shrink-0 items-center justify-center {{$color}} rounded-l-md text-sm font-medium text-white">
		{{$initial}}</div>
	<div
		class="flex flex-1 items-center justify-between truncate rounded-r-md border-b border-r border-t border-gray-200 bg-white">
		<div class="flex-1 truncate px-4 py-2 text-sm">
			<p class="font-medium text-gray-900 hover:text-gray-600">{{$name}}</p>
			<p class="text-gray-500">{{$status??'Kein Status erkannt'}}</p>
		</div>
		<div class="flex-shrink-0 pr-2 hidden">
			<button type="button"
				class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-transparent bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-maincolorh focus:ring-offset-2">
				<span class="sr-only">Open options</span>
				<svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
					<path
						d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
				</svg>
			</button>
		</div>
	</div>
</li>
