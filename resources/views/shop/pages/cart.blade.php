@extends('shop.layouts.app')

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('form[name=apply_promotion] button').on('click', function (e){
                e.preventDefault();
                var form = $(this).closest("form");
                var formUrl = form.attr('data-action');
                var formMethod = form.attr('data-method');
                var formData = form.serialize();
                $.ajax({
                    url: formUrl,
                    type: formMethod,
                    cache: false,
                    data: formData,
                    dataType: 'JSON',
                    success: function(response) {
                        location.reload();
                    }
                });
                return false;
            });

            $('form[name=remove_promotion] button').on('click', function (e){
                e.preventDefault();
                var form = $(this).closest("form");
                var formUrl = form.attr('data-action');
                var formMethod = form.attr('data-method');
                var formData = form.serialize();
                $.ajax({
                    url: formUrl,
                    type: formMethod,
                    cache: false,
                    data: formData,
                    dataType: 'JSON',
                    success: function(response) {
                        location.reload();
                    }
                });
                return false;
            });
        });
    </script>
@endpush

@section('title', 'Корзина')

@section('content')
    @if ($vsp_cart_empty)
        <div class="h-screen py-8">
            <div class="container mx-auto max-w-6xl px-4">
                <h1 class="text-2xl font-semibold text-center mt-24 mb-4">Ваша корзина пуста. Не забудьте добавить товары для оформления заказа</h1>
            </div>
        </div>
    @else
        <div class="h-screen">
            <div class="container mx-auto max-w-6xl">
                @component('shop.components.common.header_text')Корзина@endcomponent

                <div class="flex flex-col lg:flex-row gap-8">
                    <div class="lg:w-3/4">
                        <div class="bg-white rounded-lg border px-3 py-6">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th class="text-left font-semibold pl-2">Товар</th>
                                        <th class="text-center font-semibold">Цена</th>
                                        <th class="text-center font-semibold">Количество</th>
                                        <th class="text-center font-semibold ">Стоимость</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vsp_cart_collection as $product)
                                        <tr @if(!$loop->last) class="border-b" @endif>
                                            <td class="py-4">
                                                <div class="flex h-7 items-stretch justify-center">
                                                    <form method="POST" action="{{ route('cart.removeProduct', ['product' => $product->id])}}">
                                                        @csrf
                                                        <button type="submit" type="button" class="rounded-md px-1 py-1 lg:ml-2 text-slate-500 bg-secondary-light hover:text-primary hover:bg-primary-light duration-200 cursor-pointer">
                                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="-2 -3 22 26" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.05063 6.73418C1.20573 5.60763 2.00954 4 3.41772 4H14.5823C15.9905 4 16.7943 5.60763 15.9494 6.73418V6.73418C15.3331 7.55584 15 8.5552 15 9.58228V16C15 18.2091 13.2091 20 11 20H7C4.79086 20 3 18.2091 3 16V9.58228C3 8.5552 2.66688 7.55584 2.05063 6.73418V6.73418Z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 15L11 9"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 15L7 9"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 4L12.4558 2.36754C12.1836 1.55086 11.4193 1 10.5585 1H7.44152C6.58066 1 5.81638 1.55086 5.54415 2.36754L5 4"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="py-4">
                                                <div class="flex items-center">
                                                    <img class="h-12 mr-4" src="{{ $product->attributes->has('image') ? asset($product->attributes->image) : '' }}" alt="Product image">
                                                    <div class="flex w-full flex-col justify-center">
                                                        <span class="font-semibold">{{ $product->name }}</span>

                                                        @if ($product->attributes->has('variation'))
                                                            @foreach($product->attributes->variation as $feature_name => $feature_value_name)
                                                                <span class="text-sm float-right text-slate-400">{{ $feature_name }}: {{ $feature_value_name }}</span>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 text-center">{{ $vsp_cart_price['prices'][$product->id] }} р.</td>
                                            <td class="py-4">
                                                <div class="mx-auto flex h-7 items-stretch justify-center text-gray-600">
                                                    <form method="POST" action="
                                                        @if ($product->quantity < 2)
                                                            {{ route('cart.removeProduct', ['product' => $product->id]) }}
                                                        @else
                                                            {{ route('cart.decreaseProduct', ['product' => $product->id]) }}
                                                        @endif">
                                                        @csrf
                                                        <button class="flex items-center font-medium rounded-l-md bg-gray-100 px-1.5 h-7 transition duration-200 hover:bg-gray-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                                <path fill-rule="evenodd" d="M4 10a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H4.75A.75.75 0 014 10z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    <div class="flex items-center bg-gray-50 px-4 font-semibold text-xs uppercase transition">{{ $product->quantity }}</div>
                                                    <form method="POST" action="{{ route('cart.increaseProduct', ['product' => $product->id])}}">
                                                        @csrf
                                                        <button class="flex items-center font-medium rounded-r-md bg-gray-100 px-1.5 h-7 transition duration-200 hover:bg-gray-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="py-4 text-center font-semibold">{{ $vsp_cart_price['subtotals'][$product->id] }} р.</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="pt-2 pl-4">
                            <form method="POST" action="{{ route('cart.clearCart') }}">
                                @csrf
                                <button type="submit" class="text-sm font-medium rounded-lg text-slate-400 duration-200 hover:text-primary">
                                    &#10005; Очистить корзину
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="lg:w-1/4 pt-6 lg:pt-0">
                        <div class="bg-secondary-very-light rounded-lg border p-6 mb-4">
                            <form data-method="POST" data-action="{{ route('cart.applyPromotion') }}" name="apply_promotion">
                                @csrf
                                <label for="promotion_code" class="text-lg text-secondary font-semibold">Промокод</label>
                                <div class="flex my-4">
                                    <input type="text" id="promotion_code" name="promotion_code" class="w-full rounded-l-md border border-secondary-dark px-2.5 py-2 text-sm uppercase outline-none" autofocus/>
                                    <button type="submit" class="btn-text-apply-promotion-medium px-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gift-fill" viewBox="0 0 16 16">
                                            <path d="M3 2.5a2.5 2.5 0 0 1 5 0 2.5 2.5 0 0 1 5 0v.006c0 .07 0 .27-.038.494H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2.038A2.968 2.968 0 0 1 3 2.506V2.5zm1.068.5H7v-.5a1.5 1.5 0 1 0-3 0c0 .085.002.274.045.43a.522.522 0 0 0 .023.07zM9 3h2.932a.56.56 0 0 0 .023-.07c.043-.156.045-.345.045-.43a1.5 1.5 0 0 0-3 0V3zm6 4v7.5a1.5 1.5 0 0 1-1.5 1.5H9V7h6zM2.5 16A1.5 1.5 0 0 1 1 14.5V7h6v9H2.5z"/>
                                        </svg>
                                    </button>
                                </div>
                            </form>

                            @foreach($vsp_cart_applyed_promotions['promotions'] as $applyed_promotions)
                                <form data-method="POST" data-action="{{ route('cart.removePromotion') }}" name="remove_promotion" class="mb-0">
                                    @csrf
                                    <div class="flex @if(!$loop->first) pt-1 @endif">
                                        <input class="hidden" type="text" name="promotion_name" value="{{ $applyed_promotions['name'] }}" />
                                        <button type="submit" class="duration-200 hover:text-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                        <p class="text-sm text-secondary pl-1">{{ $applyed_promotions['name'] }}</p>
                                    </div>
                                </form>
                            @endforeach
                        </div>

                        <div class="bg-secondary-very-light rounded-lg border p-6">
                            <h2 class="text-lg font-semibold mb-4">Итоговая стоимость</h2>
                            <div class="flex justify-between mb-2">
                                <span>Товары</span>
                                <span>{{ $vsp_cart_price['total'] }} р.</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span>Промокод:</span>
                                <span>{{ $vsp_cart_applyed_promotions['sum_value'] }} р.</span>
                            </div>
                            <hr class="my-2">
                            <div class="flex justify-between mb-2">
                                <span class="font-semibold">Сумма заказа</span>
                                <span class="font-semibold">{{ $vsp_cart_price['total'] }} р.</span>
                            </div>
                            <a href="{{ route('checkout') }}" type="button" class="animate-wiggle group inline-flex items-center justify-center font-medium text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-90% py-2 px-4 rounded-lg mt-4 w-full hover:scale-105 duration-200">
                                К оформлению
                                <svg xmlns="http://www.w3.org/2000/svg" class="group-hover:ml-2 ml-1 h-7 w-7 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
