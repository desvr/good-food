<div id="productCard"
     class="group/cart rounded-xl p-3 bg-white duration-200 shadow hover:shadow-md hover:transform hover:scale-102">
    <div class="relative flex items-end overflow-hidden rounded-xl">
        <!-- Modal toggle -->
        <a id="readProductPageButton{{ $product->id }}" data-target="#productPage"
           data-attr="{{ route('product.show', ['product_id' => $product['id']]) }}" class="cursor-pointer"
           type="button">
            <img class="border-0 rounded-xl" src="{{ asset($product['image']) }}" alt=""/>
        </a>

        @if (!empty($product['label']))
            @component('shop.components.products.badge', ['color' => App\Enum\ProductBadge::getBadgeColorByLabel($product->label)])
                {{ strtoupper($product['label']) }}
            @endcomponent
        @endif
    </div>

    <div class="mt-2 px-2 pt-2">
        <!-- Modal toggle -->
        <a id="readProductPageButton{{ $product->id }}" data-target="#productPage"
           data-attr="{{ route('product.show', ['product_id' => $product['id']]) }}" class="cursor-pointer"
           type="button">
            <h2 class="text-slate-700 font-bold break-words">{{ $product['name'] }}</h2>
            <p id="description"
               class="my-4 text-sm text-secondary break-words line-clamp-2">{{ $product['description'] }}</p>
        </a>
    </div>

    <form data-method="POST" data-action="{{ route('cart.addProduct') }}" name="add_product_to_cart">
        @csrf
        <input class="hidden" type="text" name="product_id" value="{{ $product->id }}"/>

        <div class="px-2">
            @component('shop.components.products.variation_switch', [
                'product' => $product,
                'category_id' => $category['id'],
            ])@endcomponent

            <div class="mt-4 mb-1.5 flex items-stretch justify-between">
                <div class="flex items-center">
                    <p id="price" class="font-bold text-secondary text-lg">{{ $product['price'] }} р.</p>
                </div>

                <div id="btnAddToCard">
                    @if (array_key_exists($product->id, $vsp_cart_collection))
                        @component('shop.components.products.button_product_cart_regulator_medium', ['product_id' => $product->id, 'product_quantity_cart' => $vsp_cart_collection[$product->id]['quantity']])@endcomponent
                    @else
                        @component('shop.components.products.button_add_product_cart_medium', ['product_id' => $product->id])@endcomponent
                    @endif
                </div>
            </div>
        </div>
    @if (!array_key_exists($product->id, $vsp_cart_collection)) </form> @endif
</div>
