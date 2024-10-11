<div id="productPage" tabindex="-1" aria-hidden="true" role="dialog"
     class="modal fade bg-secondary bg-opacity-50 backdrop-blur-sm hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-200 justify-center items-center w-full md:inset-0 h-full">
    <div class="relative mx-auto p-4 w-full max-w-4xl h-full md:h-auto md:mt-16 lg:mt-32">
        <div class="relative p-4 bg-white rounded-lg shadow sm:p-5">
            <div class="flex justify-end rounded-t px-1 pt-1">
                <button id="closeProductPageButton{{ $product->id }}" type="button" data-dismiss="modal"
                        class="close text-secondary-dark bg-transparent hover:bg-slate-100 hover:text-secondary rounded-lg text-sm p-1.5 inline-flex transition duration-200">
                    <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            <div class="max-w-6xl container mx-auto px-2">
                <div class="md:col-gap-12 xl:col-gap-16 grid grid-cols-1 gap-12 md:mt-4 md:grid-cols-5 md:gap-16">
                    <div class="md:col-span-3 md:row-end-1">
                        <div class="md:flex md:items-start">
                            <div class="md:order-2 md:ml-5">
                                <div class="max-w-xl overflow-hidden rounded-lg">
                                    <img id="image" class="h-full w-full max-w-full object-cover"
                                         src="{{ asset($product['image']) }}" alt=""/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-3 md:row-span-3 md:row-end-3">
                        <h1 id="name" class="text-2xl font-bold text-secondary sm:text-3xl select-all">
                            {{ $product->name }}
                        </h1>

                        <p id="description" class="mt-6 text-base text-slate-700">{{ $product->description }}</p>

                        <form data-method="POST" data-action="{{ route('cart.addProduct') }}" name="add_product_to_cart_modal">
                            @csrf
                            <input class="hidden" type="text" name="product_id" value="{{ $product->id }}"/>

                            <div class="pt-4">
                                @component('shop.components.products.variation_switch', [
                                    'is_modal' => true,
                                    'product' => $product,
                                ])@endcomponent
                            </div>

                            <div class="pt-6 pb-2 mr-0.5">
                                @if (!empty($product['weight']))
                                    <span id="weight"
                                          class="bg-secondary-light text-secondary text-xs font-light px-2.5 py-1.5 rounded-full">Вес: {{ $product['weight'] }} г.</span>
                                @endif
                                @if (!empty($product['calories']))
                                    <span id="calories"
                                          class="bg-secondary-light text-secondary text-xs font-light px-2.5 py-1.5 rounded-full">{{ $product['calories'] }} кКал/100 г.</span>
                                @endif
                            </div>

                            <div class="mt-2 flex flex-row items-center justify-between border-t py-4">
                                <div class="flex items-end text-2xl">
                                    <h1 id="price" class="font-bold text-secondary">{{ $product['price'] }} р.</h1>
                                </div>

                                <div id="btnAddToCard">
                                    @if (array_key_exists($product->id, $vsp_cart_collection))
                                        @component('shop.components.products.modals.button_product_cart_regulator_big', ['product_id' => $product->id, 'product_quantity_cart' => $vsp_cart_collection[$product->id]['quantity']])@endcomponent
                                    @else
                                        @component('shop.components.products.modals.button_add_product_cart_big', ['product_id' => $product->id])@endcomponent
                                    @endif
                                </div>
                            </div>
                        @if (!array_key_exists($product->id, $vsp_cart_collection)) </form> @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
