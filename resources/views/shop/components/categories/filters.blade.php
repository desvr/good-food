<div class="pb-8">
    <form id="productOptionFilterForm" data-method="GET" data-url="{{ route('category.show', ['category' => $category_slug]) }}" class="space-y-4">
        @foreach($category_filters as $filter_type => $filter_variants)
            <div class="flex justify-center space-x-2">
                @foreach($filter_variants as $filter_variant)
                    <div class="max-w-6xl">
                        <input type="checkbox"
                               name="{{ $filter_type }}[{{ $filter_variant->id }}]"
                               data-type="{{ $filter_type }}"
                               id="filter_{{ $filter_type }}_{{ $filter_variant->id }}"
                               value="{{ $filter_variant->id }}"
                               class="hidden peer"
                               @checked(false)
                        >
                        <label for="filter_{{ $filter_type }}_{{ $filter_variant->id }}" class="flex cursor-pointer rounded-lg bg-white text-slate-600 px-2.5 py-1 text-sm font-medium outline-dashed outline-secondary duration-200 hover:text-primary hover:outline-primary peer-checked:bg-primary-light peer-checked:text-primary peer-checked:outline-primary">
                            {{ $filter_variant->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        @endforeach
    </form>
</div>
