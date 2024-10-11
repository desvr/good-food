<div id="banner" tabindex="-1" class="relative isolate flex items-center gap-x-6 overflow-hidden bg-gray-50 py-2.5 px-6 sm:px-3.5 sm:before:flex-1">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 577 310" aria-hidden="true"
         class="absolute top-1/2 left-[max(-7rem,calc(50%-52rem))] -z-10 w-[36.0625rem] -translate-y-1/2 transform-gpu blur-2xl">
        <path id="1d77c128-3ec1-4660-a7f6-26c7006705ad" fill="url(#49a52b64-16c6-4eb9-931b-8e24bf34e053)"
              fill-opacity=".3"
              d="m142.787 168.697-75.331 62.132L.016 88.702l142.771 79.995 135.671-111.9c-16.495 64.083-23.088 173.257 82.496 97.291C492.935 59.13 494.936-54.366 549.339 30.385c43.523 67.8 24.892 159.548 10.136 196.946l-128.493-95.28-36.628 177.599-251.567-140.953Z"/>
        <defs>
            <linearGradient id="49a52b64-16c6-4eb9-931b-8e24bf34e053" x1="614.778" x2="-42.453" y1="26.617" y2="96.115"
                            gradientUnits="userSpaceOnUse">
                <stop stop-color="#9089FC"/>
                <stop offset="1" stop-color="#FF80B5"/>
            </linearGradient>
        </defs>
    </svg>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 577 310" aria-hidden="true"
         class="absolute top-1/2 left-[max(45rem,calc(50%+8rem))] -z-10 w-[36.0625rem] -translate-y-1/2 transform-gpu blur-2xl">
        <use href="#1d77c128-3ec1-4660-a7f6-26c7006705ad"/>
    </svg>
    <div class="flex flex-wrap items-center gap-y-2 gap-x-4">
        <p class="text-sm leading-6 text-secondary">
            <strong class="font-semibold">{{ $title }}</strong>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2 2" class="mx-2 mb-0.5 inline h-1.5 w-1.5 fill-current" aria-hidden="true"><circle cx="1" cy="1" r="1"/></svg>
            {{ $slot }}
        </p>
        <a href="{{ $button_url }}"
           class="flex-none rounded-full bg-secondary py-1 px-3.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-700">
            {{ $button_title }} @if($button_pointer) <span aria-hidden="true">&rarr;</span> @endif
        </a>
    </div>

    <div class="flex flex-1 justify-end items-center">
        <button data-collapse-toggle="banner" type="button" class="text-secondary rounded-lg text-sm p-1.5">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
</div>
