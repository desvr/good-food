@if ($category->products->count() > 0)
    <div class="mx-auto grid max-w-6xl grid-cols-1 gap-4 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        @foreach($category->products as $product)
            @if(!empty($product))
                @component('shop.components.products.card', ['product' => $product, 'category' => $category, 'vsp_cart_collection' => $vsp_cart_collection_array])@endcomponent
            @endif
        @endforeach
    </div>
@else
    <div class="py-8">
        <div class="container mx-auto max-w-2xl px-4">
            <h1 class="text-2xl font-semibold text-center">Товары не найдены</h1>
        </div>
    </div>
@endif
