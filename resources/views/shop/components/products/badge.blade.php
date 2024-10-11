<div class="absolute top-2 left-2 inline-flex items-center rounded-full">
    @if ($color === 'green')
        <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-semibold text-green-500 ring-1 ring-inset ring-green-600/20">{{ $slot }}</span>
    @elseif ($color === 'red')
        <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-semibold text-red-500 ring-1 ring-inset ring-red-600/20">{{ $slot }}</span>
    @endif
</div>
