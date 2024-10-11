@extends('shop.layouts.app')

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            initProductCarts();

            function initProductCarts() {
                setAddProductToCart('add_product_to_cart');
                setIncreaseProductCart('increaseProduct');
                setDecreaseProductCart('decreaseProduct');
                setReadProductPageButton('readProductPageButton');
                loadProductVariationProduct('variation-feature-selected');
            }

            function setReadProductPageButton($buttonId) {
                $('[id^=' + $buttonId + ']').on('click', function (e) {
                    e.preventDefault();
                    var modalId = $(this).data('target');
                    var modalUrl = $(this).data('attr');
                    $.ajax({
                        url: modalUrl,
                        type: 'GET',
                        success: function (result) {
                            $('#divModalShow').html(result);
                            $(modalId).show();

                            setCloseProductPageButton('closeProductPageButton');
                            setAddProductToCart('add_product_to_cart_modal', true);
                            setIncreaseProductCart('increaseProduct', true);
                            setDecreaseProductCart('decreaseProduct', true);
                            loadProductVariationProduct('modal-variation-feature-selected', true);
                        }
                    });
                    return false;
                });
            }

            function setCloseProductPageButton($buttonId) {
                $('[id^=' + $buttonId + ']').on('click', function () {
                    $('#productPage').hide();
                });
            }

            function setAddProductToCart($formName, $isModal = false) {
                $('form[name=' + $formName + '] button').off('click').on('click', function (e) {
                    e.preventDefault();
                    var form = $(this).closest("form");
                    var btnAddToCardDiv = $(this).closest("#btnAddToCard");
                    var formUrl = form.attr('data-action');
                    var formMethod = form.attr('data-method');
                    var productId = form.find('input[name=product_id]').val();
                    var variationData = form.find('input[name=variation_data]:checked').val();
                    $.ajax({
                        url: formUrl,
                        type: formMethod,
                        cache: false,
                        async: true,
                        data: {
                            product_id: productId,
                            variation_data: variationData,
                            is_modal: $isModal,
                        },
                        dataType: 'JSON',
                        success: function (response) {
                            updateCartTotalPriceInHeader(response.cart_total_price);
                            $(btnAddToCardDiv).html(response.product_quantity_regulator);
                            setIncreaseProductCart('increaseProduct', $isModal);
                            setDecreaseProductCart('decreaseProduct', $isModal);
                        }
                    });
                    return false;
                });
            }

            function setIncreaseProductCart($formName, $isModal = false) {
                $('form[name=' + $formName + '] button').off('click').on('click', function (e) {
                    e.preventDefault();
                    var form = $(this).closest("form");
                    var btnAddToCardDiv = $(this).closest("#btnAddToCard");
                    var formUrl = form.attr('data-action');
                    var formMethod = form.attr('data-method');
                    var productId = form.find('input[name=product_id]').val();
                    $.ajax({
                        url: formUrl,
                        type: formMethod,
                        cache: false,
                        data: {
                            product_id: productId,
                            is_modal: $isModal,
                        },
                        dataType: 'JSON',
                        success: function (response) {
                            $(btnAddToCardDiv).html(response.product_quantity_regulator);
                            updateCartTotalPriceInHeader(response.cart_total_price);
                            setIncreaseProductCart('increaseProduct', $isModal);
                            setDecreaseProductCart('decreaseProduct', $isModal);
                        }
                    });
                    return false;
                });
            }

            function setDecreaseProductCart($formName, $isModal = false) {
                $('form[name=' + $formName + '] button').off('click').on('click', function (e) {
                    e.preventDefault();
                    var form = $(this).closest("form");
                    var btnAddToCardDiv = $(this).closest("#btnAddToCard");
                    var formUrl = form.attr('data-action');
                    var formMethod = form.attr('data-method');
                    var productId = form.find('input[name=product_id]').val();
                    $.ajax({
                        url: formUrl,
                        type: formMethod,
                        cache: false,
                        data: {
                            product_id: productId,
                            is_modal: $isModal,
                        },
                        dataType: 'JSON',
                        success: function (response) {
                            $(btnAddToCardDiv).html(response.product_quantity_regulator);
                            updateCartTotalPriceInHeader(response.cart_total_price);

                            if (response.product_add_button) {
                                if ($isModal) {
                                    setAddProductToCart('add_product_to_cart_modal', true);
                                } else {
                                    setAddProductToCart('add_product_to_cart');
                                }
                            } else {
                                setIncreaseProductCart('increaseProduct', $isModal);
                                setDecreaseProductCart('decreaseProduct', $isModal);
                            }
                        }
                    });
                    return false;
                });
            }

            function loadProductVariationProduct($switchButtonId, $isModal = false) {
                $('[id^=' + $switchButtonId + ']').on('click', function (e) {
                    e.preventDefault();
                    var selectedLabel = $(this);
                    var productId = selectedLabel.attr('data-product-id');
                    $.ajax({
                        url: '{{ route('product.loadVariationProduct') }}',
                        type: 'POST',
                        cache: true,
                        dataType: 'JSON',
                        data: {
                            product_id: productId,
                            is_modal: $isModal,
                        },
                        success: function (response) {
                            selectedLabel.find('input').prop('checked', true);

                            if ($isModal) {
                                $('#productPage p[id=description]').text(response.description);
                                $('#productPage span[id=weight]').text('Вес: ' + response.weight + ' г.');
                                $('#productPage span[id=calories]').text(response.calories + ' кКал/100 г.');
                                $('#productPage h1[id=price]').text(response.price + ' р.');

                                $('#productPage #btnAddToCard').html(response.product_quantity_regulator);

                                setAddProductToCart('add_product_to_cart_modal', $isModal);
                            } else {
                                var usedCard = selectedLabel.closest('#productCard');
                                usedCard.find('p[id=description]').text(response.description);
                                usedCard.find('p[id=price]').text(response.price + ' р.');

                                usedCard.find('#btnAddToCard').html(response.product_quantity_regulator);

                                setAddProductToCart('add_product_to_cart', $isModal);
                            }

                            setIncreaseProductCart('increaseProduct', $isModal);
                            setDecreaseProductCart('decreaseProduct', $isModal);
                        }
                    });
                    return false;
                });
            }

            function updateCartTotalPriceInHeader($price) {
                $('#cart_total_price').html($price + ' руб.');
            }
        });
    </script>
@endpush

@section('title', 'Главная')

@section('content')
    @component('shop.components.common.carousel', ['data' => $promotions])@endcomponent

    @foreach($categories as $category)
        @if(!empty($category->products_count))
            @component('shop.components.common.header_text'){{ $category->name }}@endcomponent

            <section>
                <div class="mx-auto grid max-w-6xl grid-cols-1 gap-4 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach($category->products as $product)
                        @if(!empty($product))
                            @component('shop.components.products.card', ['product' => $product, 'category' => $category, 'vsp_cart_collection' => $vsp_cart_collection_array])@endcomponent
                        @endif
                    @endforeach

                    @if($category->products_count > $limit_view_category_products)
                        @component('shop.components.products.empty_card', ['category_slug' => $category->slug])@endcomponent
                    @endif
                </div>
            </section>
        @endif
    @endforeach
@endsection
