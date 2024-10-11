@if (!$product->variations->isEmpty())
    <div id="divVariationSwitch" class="pb-2">
        @php $prefixLabelId = (!empty($is_modal)) ? 'modal-variation-feature-selected-' : 'variation-feature-selected-'; @endphp

        @foreach ($product->variations as $variation)
            @if (!empty($is_modal) && $loop->first)
                <p class="pt-2 mb-1 text-base font-light text-secondary">{{ $variation['product_feature_name'] }}</p>
            @endif

            <div id="rowVariationSwitch" class="grid max-w-6xl grid-cols-3 gap-1 @if($loop->first) p-1 @endif mx-auto bg-secondary-light rounded-lg font-medium @if(!empty($is_modal)) text-sm @else text-xs @endif">
                {{-- ID: "variation" + category_id (или строка "popup" в случае товара в поп-ап) + product_id + feature_id + feature_value_id --}}
                <label for="variation_{{ !empty($is_modal) ? 'popup' : $category_id }}_{{ $variation['product_id'] }}_{{ $variation['product_feature_id'] }}_{{ $variation['product_feature_value_id'] }}"
                       id="{{ $prefixLabelId }}-{{ $variation['product_id'] }}-{{ $variation['product_feature_id'] }}-{{ $variation['product_feature_value_id'] }}"
                       data-product-id="{{ $variation['product_id'] }}"
                >
                    <input @checked($loop->first) type="radio" name="variation_data"
                           id="variation_{{ !empty($is_modal) ? 'popup' : $category_id }}_{{ $variation['product_id'] }}_{{ $variation['product_feature_id'] }}_{{ $variation['product_feature_value_id'] }}"
                           value="{{ $variation['product_id'] }}_{{ $variation['product_feature_id'] }}_{{ $variation['product_feature_value_id'] }}" class="peer sr-only" />
                    <p class="radio py-1 text-center rounded-md duration-200 border-2 text-secondary border-secondary-light hover:bg-secondary-bright hover:border-secondary-bright peer-checked:text-primary peer-checked:border-2 peer-checked:border-primary peer-checked:bg-secondary-light cursor-pointer">
                        {{ $variation['product_feature_value_name'] }}
                    </p>
                </label>

                @foreach ($product->children as $children)
                    @foreach($children->variations as $children_variation)
                        <label for="variation_{{ !empty($is_modal) ? 'popup' : $category_id }}_{{ $children_variation['product_id'] }}_{{ $children_variation['product_feature_id'] }}_{{ $children_variation['product_feature_value_id'] }}"
                               id="{{ $prefixLabelId }}-{{ $children_variation['product_id'] }}-{{ $children_variation['product_feature_id'] }}-{{ $children_variation['product_feature_value_id'] }}"
                               data-product-id="{{ $children_variation['product_id'] }}"
                        >
                            <input type="radio" name="variation_data"
                                   id="variation_{{ !empty($is_modal) ? 'popup' : $category_id }}_{{ $children_variation['product_id'] }}_{{ $children_variation['product_feature_id'] }}_{{ $children_variation['product_feature_value_id'] }}"
                                   value="{{ $children_variation['product_id'] }}_{{ $children_variation['product_feature_id'] }}_{{ $children_variation['product_feature_value_id'] }}" class="peer sr-only" />
                            <p class="radio py-1 text-center rounded-md duration-200 border-2 text-secondary border-secondary-light hover:bg-secondary-bright hover:border-secondary-bright peer-checked:text-primary peer-checked:border-2 peer-checked:border-primary peer-checked:bg-secondary-light cursor-pointer">
                                {{ $children_variation['product_feature_value_name'] }}
                            </p>
                        </label>
                    @endforeach
                @endforeach
            </div>
        @endforeach
    </div>
@endif
