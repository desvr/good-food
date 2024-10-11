@extends('shop.layouts.app')

@section('title', 'Заказ' . $order_data['id'] ? ' №' . $order_data['id'] : '')

@section('content')
    <div class="container mx-auto max-w-6xl">
        @if (session('status') && session('status_message'))
            <div class="mt-6 alert alert-{{ session('status') }}">
                {{ session('status_message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button>
            </div>
        @endif

        @if (!empty($order_data))
            @component('shop.components.common.header_text')Заказ №{{ $order_data['id'] }}@endcomponent

            <div class="flex flex-col lg:flex-row max-w-6xl gap-8">
                <div class="w-full rounded-xl h-full">
                    <div class="py-4 px-6 rounded-xl h-full bg-secondary-semi-light border border-secondary-semi-bright mb-4">
                        <p class="text-xl text-secondary font-bold">История заказа</p>

                        <div class="pt-4">
                            <div class="space-y-1">
                            @foreach($order_history['data'] as $history_step)
                                <p class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5
                                         @if($loop->first)
                                             @if(\App\Enum\OrderStatus::checkErrorStatus($history_step['order_status']))
                                                 text-red-500
                                             @else
                                                 text-green-500
                                             @endif
                                         @else
                                             text-secondary-very-dark
                                         @endif"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="mx-1 text-sm @if($loop->first) font-medium @else font-light @endif">
                                        {{ $history_step['updated_at'] }}
                                        {{ $order_status_list[$history_step['order_status']] }}
                                    </span>
                                </p>

                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="py-4 px-6 rounded-xl h-full bg-green-100 border border-green-400 mb-4">
                        <p class="text-xl text-secondary font-bold">Код для получения заказа: {{ $order_data['receipt_code'] }}</p>
                    </div>

                    <div class="py-4 px-6 rounded-xl h-full bg-white border border-secondary-semi-bright">
                        <p class="text-xl text-secondary font-bold">Детали заказа</p>

                        @foreach($order_schema as $section => $section_schema)
                            <div class="pt-4">
                                @foreach($section_schema as $field_name => $field_value)
                                    @if (!empty($field_value))
                                        <div class="flex flex-row w-full break-words gap-2 py-0.5 items-start">
                                            <div class="flex-col w-1/4">
                                                <p class="text-sm text-secondary-very-dark">{{ $field_name }}:</p>
                                            </div>

                                            <div class="flex-col w-3/4">
                                                <p class="text-sm">{{ $field_value }}</p>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="w-full py-4 rounded-xl bg-secondary-very-light border border-secondary-bright tracking-tight">
                    <div class="px-6 flex flex-row items-center justify-between">
                        <p class="text-xl text-secondary font-bold">Состав заказа</p>
                    </div>

                    <div class="py-3 px-6">
                        @foreach($order_data['order_products'] as $product_id => $product)
                            <div class="flex flex-row w-full gap-2 py-2 items-center">
                                <div class="flex-col w-1/3">
                                    <img class="w-full mr-4 pb-2" src="{{ asset($product['detailed_data']['image']) ?? '' }}">
                                </div>

                                <div class="flex-col w-2/3">
                                    <p class="text-sm font-bold">{{ $product['data']['name'] }}</p>

                                    <div class="pt-0.5">
                                        @if (!empty($product['data']['variations']))
                                            @foreach($product['data']['variations'] as $feature_name => $feature_value_name)
                                                <p class="text-sm text-secondary-very-dark font-light">{{ $feature_name }}: {{ $feature_value_name }}</p>
                                            @endforeach
                                        @endif
                                    </div>

                                    @if (!empty($product['detailed_data']['description']))
                                        <div class="pt-0.5">
                                            <p class="text-sm font-light break-words line-clamp-2">{{ $product['detailed_data']['description'] }}</p>
                                        </div>
                                    @endif

                                    <div class="flex flex-row pt-1">
                                        <p class="text-sm font-bold">x{{ $product['quantity'] }}</p>
                                        <p class="text-sm font-bold pl-4">{{ $product['result_subtotal_price'] }} р.</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gray-50 h-screen py-8">
                <div class="container mx-auto max-w-6xl px-4">
                    <h1 class="text-2xl font-semibold text-center mt-24 mb-4">Заказ не найден.</h1>
                </div>
            </div>
        @endif
    </div>
@endsection
