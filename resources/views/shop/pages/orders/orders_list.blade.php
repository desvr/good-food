@extends('shop.layouts.app')

@section('title', 'Список заказов')

@section('content')
    <div class="container mx-auto max-w-6xl">
        @if (!empty($orders_data))
            @component('shop.components.common.header_text')Список заказов@endcomponent

            @foreach($orders_data as $order_data)
                @php
                    $status_color = \App\Enum\OrderStatus::checkErrorStatus($order_data['status'])
                        ? 'bg-red-50 text-red-500'
                        : 'bg-secondary-semi-light text-secondary-very-dark';
                @endphp

                <div class="flex flex-col lg:flex-row max-w-6xl">
                    <div class="w-full rounded-xl h-full">
                        <div class="py-4 px-6 rounded-xl bg-white duration-200 shadow hover:shadow-md border border-secondary-semi-bright mb-4">
                            <div class="flex flex-row justify-between">
                                <a href="{{ route('order.show', ['order_id' => $order_data['id']]) }}" class="text-lg text-secondary font-bold duration-200 hover:text-primary">Заказ от {{ $order_data['created_at'] }}</a>
                                <div class="{{ $status_color }} text-sm font-medium px-2 py-1 rounded-lg">
                                    {{ $order_status_list[$order_data['status']] }}
                                </div>
                            </div>
                            <div class="flex justify-between items-center pt-4 gap-8">
                                <div class="w-full">
                                    <p class="font-medium">{{ $shipping_type_list[$order_data['shipping_type']] }}</p>
                                    <p class="font-light">Имя получателя: {{ $order_data['name'] }}</p>
                                    <p class="font-light">Номер телефона: {{ $order_data['phone'] }}</p>
                                </div>
                                <div class="py-2 px-4 flex-none bg-green-100 border border-green-400 rounded-lg">
                                    Номер для получения: <b>{{ $order_data['receipt_code'] }}</b>
                                </div>
                            </div>
                            <div class="flex flex-wrap pt-4">
                                @foreach($order_data['order_products'] ?? [] as $order_product)
                                    <img class="w-32 mr-2 rounded-lg" src="{{ asset($order_product['detailed_data']['image']) ?? '' }}" alt="Product image">
                                @endforeach
                            </div>
                            <div class="flex justify-between items-center pt-6 gap-8">
                                <p class="font-semibold">Стоимость заказа: {{ $order_data['result_price'] }} руб.</p>
                                <a href="{{ route('order.show', ['order_id' => $order_data['id']]) }}" type="button" class="text-primary duration-200 hover:text-primary-dark">
                                    Подробнее
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-gray-50 h-screen py-8">
                <div class="container mx-auto max-w-6xl px-4">
                    <h1 class="text-2xl font-semibold text-center mt-24 mb-4">Заказы отсутствуют.</h1>
                </div>
            </div>
        @endif
    </div>
@endsection
