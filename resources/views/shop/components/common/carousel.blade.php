@if (!empty($data))
    <div class="pt-6 sm:pt-10 md:pt-0 lg:pt-8"></div>
    <div id="default-carousel" class="container mx-auto max-w-6xl w-full relative z-[70]" data-carousel="slide">
        <div class="relative h-44 overflow-hidden rounded-lg md:h-96">
            @foreach($data as $key => $img)
                <div class="hidden duration-700 ease-in-out rounded-2xl" @if($loop->first) data-carousel-item="active" @else data-carousel-item @endif>
                    <img src="{{ asset($img) }}" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 rounded-2xl" alt="">
                </div>
            @endforeach
        </div>

        <div class="absolute z-50 flex space-x-3 -translate-x-1/2 bottom-5 left-1/2">
            @foreach($data as $key => $img)
                <button type="button" class="w-3 h-3 rounded-full bg-white" aria-current="@if($loop->first) true @else false @endif" data-carousel-slide-to="{{ $loop->index }}}"></button>
            @endforeach
        </div>

        <button type="button" class="absolute top-0 left-0 z-50 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                    <svg class="w-4 h-4 text-white dark:text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                    </svg>
                    <span class="sr-only">Previous</span>
                </span>
        </button>
        <button type="button" class="absolute top-0 right-0 z-50 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                    <svg class="w-4 h-4 text-white dark:text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="sr-only">Next</span>
                </span>
        </button>
    </div>
@endif
