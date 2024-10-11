@extends('shop.layouts.app')

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#phone").mask("+7 (999) 999-9999");

            $('ul#payment_method_list').on('change', 'input[name="payment_method"]', function () {
                if ($('input#payment_method_cash').prop('checked')) {
                    $('#change_from').removeClass('hidden');
                } else {
                    $('input#payment_method_cash_change_from').val('');
                    $('#change_from').addClass('hidden');
                }
            });

            $('ul#shipping_type_list').on('change', 'input[name="shipping_type"]', function () {
                if ($('input#shipping_type_pickup').prop('checked')) {
                    $('#delivery_address_div').addClass('hidden');
                    $('#shipping_type_delivery_div').addClass('hidden');
                    $('#shipping_type_pickup_div').removeClass('hidden');
                } else {
                    $('#delivery_address_div').removeClass('hidden');
                    $('#shipping_type_pickup_div').addClass('hidden');
                    $('#shipping_type_delivery_div').removeClass('hidden');
                }
            });

            $('input#is_preorder').on('change', function () {
                if ($('input#is_preorder').prop('checked')) {
                    $('#preorder_div').removeClass('hidden');
                } else {
                    $('#preorder_div').addClass('hidden');
                }
            });

            $('a#number_persons_dec').on('click', function () {
                var number_persons = parseInt($('#number_persons').text());
                if (number_persons > 0) {
                    $('div#number_persons').text(number_persons - 1);
                    $('input#number_persons').val(number_persons - 1);
                }
            });
            $('a#number_persons_inc').on('click', function () {
                var number_persons = parseInt($('#number_persons').text());
                $('div#number_persons').text(number_persons + 1);
                $('input#number_persons').val(number_persons + 1);
            });

            $('form[name=apply_promotion] button').on('click', function (e) {
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

            $('ul[id=shipping_type_list] input').on('click', function (e) {
                var id = $(this).attr('id');
                $('#' + id + '_div').find('input[id^=' + id + '_]:first').prop('checked', true);
            });
        });
    </script>
{{--    <script src="https://cdn.jsdelivr.net/npm/jquery.maskedinput@1.4.1/src/jquery.maskedinput.min.js" type="text/javascript"></script>--}}
@endpush

@section('title', 'Оформление заказа')

@section('content')
    <div class="container mx-auto max-w-6xl">
        @if ($vsp_cart_empty)
            <div class="bg-gray-50 h-screen py-8">
                <div class="container mx-auto max-w-6xl px-4">
                    <h1 class="text-2xl font-semibold text-center mt-24 mb-4">Ваша корзина пуста. Не забудьте добавить товары для оформления заказа</h1>
                </div>
            </div>
        @else
            @component('shop.components.common.header_text')Оформление заказа@endcomponent

            <form method="POST" action="{{ route('order.store') }}">
                @csrf
                <div class="flex flex-col md:flex-row max-w-6xl gap-8">
                    <div class="md:w-1/2 lg:w-2/3 py-4 px-6 rounded-xl h-full bg-white border border-secondary-semi-bright">
                        <p class="text-xl text-secondary font-bold">Детали заказа</p>

                        <div class="flex flex-row gap-4 py-5">
                            <div class="w-1/2">
                                <label for="name" class="text-base font-medium text-secondary">Имя получателя</label>
                                <input type="text" id="name" name="name" value="{{$user->name ?? ''}}" class="mt-1.5 bg-secondary-light border border-secondary-dark text-secondary text-sm rounded-lg w-full py-1.5 px-2.5" required>
                            </div>
                            <div class="w-1/2">
                                <label for="phone" class="text-base font-medium text-secondary">Номер телефона</label>
                                <input type="text" id="phone" name="phone" value="{{$user->phone ?? '+7'}}" class="mt-1.5 bg-secondary-light border border-secondary-dark text-secondary text-sm rounded-lg w-full py-1.5 px-2.5" required>
                            </div>
                        </div>

                        <div class="py-3">
                            <ul id="shipping_type_list" class="flex flex-row gap-1">
                                <li class="">
                                    <input id="shipping_type_delivery" @checked(true) class="peer sr-only" type="radio" value="delivery" name="shipping_type"/>
                                    <label for="shipping_type_delivery" class="justify-center font-medium text-sm cursor-pointer rounded-lg bg-white py-2 px-3 hover:bg-secondary-light peer-checked:border peer-checked:border-secondary-dark peer-checked:bg-secondary-light">{{ $shipping_type_list['delivery'] }}</label>
                                </li>

                                <li class="">
                                    <input id="shipping_type_pickup" class="peer sr-only" type="radio" value="pickup" name="shipping_type"/>
                                    <label for="shipping_type_pickup" class="justify-center font-medium text-sm cursor-pointer rounded-lg bg-white py-2 px-3 hover:bg-secondary-light peer-checked:border peer-checked:border-secondary-dark peer-checked:bg-secondary-light">{{ $shipping_type_list['pickup'] }}</label>
                                </li>
                            </ul>

                            <div id="shipping_type_delivery_div" class="bg-secondary-light border border-secondary-dark mt-3 py-4 px-6 rounded-lg">
                                <div class="flex py-1.5">
                                    <div class="flex items-center h-5">
                                        <input @checked(true) id="shipping_type_delivery_1" name="shipping_type_delivery" aria-describedby="shipping_type_delivery_1-text" type="radio" class="w-4 h-4 cursor-pointer"
                                               value="Центр / Север / 4й мкр. / 18й мкр. / 19й мкр.">
                                    </div>
                                    <div class="ml-2 text-sm w-full">
                                        <label for="shipping_type_delivery_1" class="cursor-pointer font-medium text-gray-900 leading-tight">
                                            <p>Центр / Север / 4й мкр. / 18й мкр. / 19й мкр.</p>
                                        </label>
                                    </div>
                                </div>
                                <div class="flex py-1.5">
                                    <div class="flex items-center h-5">
                                        <input id="shipping_type_delivery_2" name="shipping_type_delivery" aria-describedby="shipping_type_delivery_2-text" type="radio" class="w-4 h-4 cursor-pointer"
                                               value="мкр. Сельдь / с. Лаишевка / СНТ Черемушки, Малинка, Вишневый сад">
                                    </div>
                                    <div class="ml-2 text-sm w-full">
                                        <label for="shipping_type_delivery_2" class="cursor-pointer font-medium text-gray-900 leading-tight">
                                            <p>мкр. Сельдь / с. Лаишевка / СНТ Черемушки</p>
                                        </label>
                                    </div>
                                </div>
                                <div class="flex py-1.5">
                                    <div class="flex items-center h-5">
                                        <input id="shipping_type_delivery_3" name="shipping_type_delivery" aria-describedby="shipping_type_delivery_3-text" type="radio" class="w-4 h-4 cursor-pointer"
                                               value="Засвияжский р-н. / Железнодорожный р-н.">
                                    </div>
                                    <div class="ml-2 text-sm w-full">
                                        <label for="shipping_type_delivery_3" class="cursor-pointer font-medium text-gray-900 leading-tight">
                                            <p>Засвияжский р-н. / Железнодорожный р-н.</p>
                                        </label>
                                    </div>
                                </div>
                                <div class="flex py-1.5">
                                    <div class="flex items-center h-5">
                                        <input id="shipping_type_delivery_4" name="shipping_type_delivery" aria-describedby="shipping_type_delivery_4-text" type="radio" class="w-4 h-4 cursor-pointer"
                                               value="Ишеевка (р.п.)">
                                    </div>
                                    <div class="ml-2 text-sm w-full">
                                        <label for="shipping_type_delivery_4" class="cursor-pointer font-medium text-gray-900 leading-tight">
                                            <p>Ишеевка (р.п.)</p>
                                        </label>
                                    </div>
                                </div>
                                <div class="flex py-1.5">
                                    <div class="flex items-center h-5">
                                        <input id="shipping_type_delivery_5" name="shipping_type_delivery" aria-describedby="shipping_type_delivery_5-text" type="radio" class="w-4 h-4 cursor-pointer"
                                               value="Новый город / Верхняя терасса / Нижняя терасса">
                                    </div>
                                    <div class="ml-2 text-sm w-full">
                                        <label for="shipping_type_delivery_5" class="cursor-pointer font-medium text-gray-900 leading-tight">
                                            <p>Новый город / Верхняя терасса / Нижняя терасса</p>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div id="shipping_type_pickup_div" class="hidden bg-secondary-light border border-secondary-dark mt-3 py-4 px-6 rounded-lg">
                                <div class="flex py-1.5">
                                    <div class="flex items-center h-5">
                                        <input id="shipping_type_pickup_1" name="shipping_type_pickup" aria-describedby="shipping_type_pickup_1-text" type="radio" class="w-4 h-4 cursor-pointer"
                                         value="ул. Крымова 63А">
                                    </div>
                                    <div class="ml-2 text-sm w-full">
                                        <label for="shipping_type_pickup_1" class="cursor-pointer font-medium text-gray-900 leading-tight">
                                            <p>ул. Крымова 63А</p>
                                            <p id="shipping_type_pickup_1-text" class="text-xs font-normal text-gray-500 leading-tight">
                                                Открыто с 10:00 до 23:00
                                            </p>
                                        </label>
                                    </div>
                                </div>
                                <div class="flex py-1.5">
                                    <div class="flex items-center h-5">
                                        <input id="shipping_type_pickup_2" name="shipping_type_pickup" aria-describedby="shipping_type_pickup_2-text" type="radio" class="w-4 h-4 cursor-pointer"
                                         value="ул. Кузоватовская 41Г">
                                    </div>
                                    <div class="ml-2 text-sm w-full">
                                        <label for="shipping_type_pickup_2" class="cursor-pointer font-medium text-gray-900 leading-tight">
                                            <p>ул. Кузоватовская 41Г</p>
                                            <p id="shipping_type_pickup_2-text" class="text-xs font-normal text-gray-500 leading-tight">
                                                Открыто круглосуточно
                                            </p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="delivery_address_div" class="py-3">
                            <p class="text-base font-semibold text-secondary">Адрес доставки</p>
                            <div class="grid grid-cols-3 gap-3 pt-2">
                                <div class="col-span-2">
                                    <div class="relative">
                                        <input type="text" id="street" name="delivery_address[street]" class="px-2.5 pb-1.5 pt-3 w-full text-sm text-secondary bg-secondary-light rounded-lg border border-secondary-dark appearance-none peer" value="" placeholder="" />
                                        <label for="street" class="absolute text-sm text-secondary-very-dark duration-200 transform -translate-y-3 scale-75 top-1 z-10 origin-[0] bg-secondary-light px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-3 left-1">
                                            Улица
                                        </label>
                                    </div>
                                </div>
                                <div class="relative">
                                    <input type="text" id="house" name="delivery_address[house]" class="px-2.5 pb-1.5 pt-3 w-full text-sm text-secondary bg-secondary-light rounded-lg border border-secondary-dark appearance-none peer" value="" placeholder="" />
                                    <label for="house" class="absolute text-sm text-secondary-very-dark duration-200 transform -translate-y-3 scale-75 top-1 z-10 origin-[0] bg-secondary-light px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-3 left-1">
                                        Дом
                                    </label>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-3 pt-3">
                                <div class="relative">
                                    <input type="text" id="porch" name="delivery_address[porch]" class="px-2.5 pb-1.5 pt-3 w-full text-sm text-secondary bg-secondary-light rounded-lg border border-secondary-dark appearance-none peer" value="" placeholder="" />
                                    <label for="porch" class="absolute text-sm text-secondary-very-dark duration-200 transform -translate-y-3 scale-75 top-1 z-10 origin-[0] bg-secondary-light px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-3 left-1">
                                        Подъезд
                                    </label>
                                </div>
                                <div class="relative">
                                    <input type="text" id="floor" name="delivery_address[floor]" class="px-2.5 pb-1.5 pt-3 w-full text-sm text-secondary bg-secondary-light rounded-lg border border-secondary-dark appearance-none peer" value="" placeholder="" />
                                    <label for="floor" class="absolute text-sm text-secondary-very-dark duration-200 transform -translate-y-3 scale-75 top-1 z-10 origin-[0] bg-secondary-light px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-3 left-1">
                                        Этаж
                                    </label>
                                </div>
                                <div class="relative">
                                    <input type="text" id="flat" name="delivery_address[flat]" class="px-2.5 pb-1.5 pt-3 w-full text-sm text-secondary bg-secondary-light rounded-lg border border-secondary-dark appearance-none peer" value="" placeholder="" />
                                    <label for="flat" class="absolute text-sm text-secondary-very-dark duration-200 transform -translate-y-3 scale-75 top-1 z-10 origin-[0] bg-secondary-light px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1 peer-focus:scale-75 peer-focus:-translate-y-3 left-1">
                                        Квартира
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-full py-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input id="is_preorder" @checked(false) type="checkbox" name="is_preorder" value="true" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-secondary-dark after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                                <span class="ml-2 text-sm font-medium text-se">Оформить предзаказ</span>
                            </label>

                            <div id="preorder_div" class="pt-3 hidden">
                                <div class="grid grid-cols-3 gap-3">
                                    <div class="col-span-2">
                                        <label for="preorder_date" class="block mb-2 text-sm font-medium text-secondary">Дата предзаказа</label>
                                        <select id="preorder_date" name="preorder_date" class="block w-full p-2 text-sm text-secondary border border-secondary-dark rounded-lg bg-secondary-light">
                                            <option value="none" selected>Выбрать дату</option>
                                            <option value="01/04/2025">1 апреля 2025 г.</option>
                                            <option value="02/04/2025">2 апреля 2025 г.</option>
                                            <option value="03/04/2025">3 апреля 2025 г.</option>
                                            <option value="04/04/2025">4 апреля 2025 г.</option>
                                        </select>
                                    </div>
                                    <div class="col-span-1">
                                        <label for="preorder_time" class="block mb-2 text-sm font-medium text-secondary">Время</label>
                                        <select id="preorder_time" name="preorder_time" class="block w-full p-2 text-sm text-secondary border border-secondary-dark rounded-lg bg-secondary-light">
                                            <option value="none" selected>Выбрать время</option>
                                            <option value="12:00">12:00</option>
                                            <option value="12:30">12:30</option>
                                            <option value="13:00">13:00</option>
                                            <option value="13:30">13:30</option>
                                            <option value="14:00">14:00</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-full py-5">
                            <label for="note" class="text-base font-medium text-secondary">Комментарий к заказу</label>
                            <textarea id="note" name="note" rows="3" placeholder="Например, уточнения к адресу или пожелания к заказу"
                                      class="mt-1.5 bg-secondary-light w-full text-secondary border border-secondary-dark rounded-lg text-sm placeholder:text-secondary-very-dark py-1.5 px-2.5"></textarea>
                        </div>

                        <div class="flex flex-row gap-2 py-2">
                            <div class="flex h-6 items-center">
                                <input id="no_request_send" name="no_request_send" type="checkbox" value="true" class="h-4 w-4 rounded border-secondary">
                            </div>
                            <div class="text-sm leading-6">
                                <label for="no_request_send" class="font-medium text-secondary">Отказаться от звонка оператора для подтверждения заказа</label>
                                <p class="font-light text-secondary-very-dark">Пожалуйста, перепроверьте введенную информацию о заказе</p>
                            </div>
                        </div>
                    </div>

                    <div class="md:w-1/2 lg:w-1/3 py-4 rounded-xl bg-secondary-very-light border border-secondary-bright tracking-tight">
                        <div class="px-6 flex flex-row items-center justify-between">
                            <p class="text-xl text-secondary font-bold">Ваш заказ</p>
                            <a href="{{ route('cart') }}"
                               class="text-xs text-secondary-very-dark bg-secondary-semi-light hover:bg-secondary-bright hover:text-secondary font-medium rounded-full px-2 pt-0.5 pb-1 duration-200">
                                изменить
                            </a>
                        </div>

                        <div class="py-3 px-6">
                            @foreach($vsp_cart_collection as $product)
                                <div class="flex flex-row gap-0.5 w-full py-1.5">
                                    <div class="flex flex-row justify-start w-1/12">
                                        <div class="flex">
                                            <p class="text-sm font-light">x{{ $product->quantity }}</p>
                                        </div>
                                    </div>

                                    <div class="w-9/12">
                                        <p class="text-sm font-medium">{{ $product->name }}</p>

                                        @if ($product->attributes->has('variation'))
                                            @foreach($product->attributes->variation as $feature_name => $feature_value_name)
                                                <p class="text-xs text-secondary-very-dark font-normal">{{ $feature_name }}: {{ $feature_value_name }}</p>
                                            @endforeach
                                        @endif
                                    </div>

                                    <div class="flex flex-row w-2/12 justify-end">
                                        <p class="text-sm font-medium">{{ $vsp_cart_price['subtotals'][$product->id] }} р.</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="py-4 px-6 bg-secondary-semi-light border-y">
                            <div class="flex flex-row justify-between items-center gap-2">
                                <div class="">
                                    <p class="text-base font-medium text-secondary">Количество персон</p>
                                    <p class="text-xs font-light leading-tight">Укажите кол-во персон для укомплектования аксессуарами.</p>
                                </div>

                                <div class="flex h-7 justify-center text-gray-600">
                                    <a id="number_persons_dec" class="flex items-center font-medium rounded-l-md bg-secondary-semi-bright px-1.5 h-7 transition duration-200 hover:bg-secondary-bright cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                            <path fill-rule="evenodd" d="M4 10a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H4.75A.75.75 0 014 10z" clip-rule="evenodd" />
                                        </svg>
                                    </a>

                                    <div type="text" id="number_persons" class="flex items-center bg-secondary-light px-4 font-bold text-base uppercase transition">1</div>
                                    <input type="hidden" id="number_persons" name="number_persons" value="1" />

                                    <a id="number_persons_inc" class="flex items-center font-medium rounded-r-md bg-secondary-semi-bright px-1.5 h-7 transition duration-200 hover:bg-secondary-bright cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div class="pt-5">
                                <p class="text-base font-medium text-secondary">Способ оплаты</p>

                                <div class="w-full pt-2">
                                    <ul id="payment_method_list" class="text-sm font-medium text-secondary bg-secondary-very-light rounded-lg border border-secondary-dark">
                                        <li class="">
                                            <div class="flex items-center pl-3.5">
                                                <input @checked(true) id="payment_method_stripe_checkout" type="radio" value="stripe_checkout" name="payment_method" class="w-4 h-4">
                                                <label for="payment_method_stripe_checkout" class="w-full py-3 ml-2.5 text-sm font-normal text-secondary cursor-pointer">Stripe Checkout</label>
                                            </div>
                                        </li>
                                        <li class="border-t border-secondary-dark">
                                            <div class="flex items-center pl-3.5">
                                                <input id="payment_method_yookassa" type="radio" value="yookassa" name="payment_method" class="w-4 h-4">
                                                <label for="payment_method_yookassa" class="w-full py-3 ml-2.5 text-sm font-normal text-secondary cursor-pointer">ЮKassa</label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="pt-3 pb-1">
                                <p class="pt-2 text-sm font-normal text-secondary leading-tight text-center">
                                    Управляйте промокодом и бонусными баллами на странице <a href="{{ route('cart') }}" class="text-sky-500 hover:text-sky-600 duration-200">корзины</a>.
                                </p>
                            </div>
                        </div>

                        <div class="py-4 px-6">
                            @php
                                $promotion = '';
                                if (!empty($vsp_cart_applyed_promotions['promotions'])) {
                                    foreach ($vsp_cart_applyed_promotions['promotions'] as $applyed_promotion) {
                                        $promotion = $applyed_promotion['name'];
                                    }
                                }

                                $order_data = [
                                    'Стоимость товаров' => [
                                        'price' => $vsp_cart_price['total_without_conditions'] . ' р.',
                                    ],
                                    'Стоимость доставки' => [
                                        'price' => 'Бесплатно',
                                    ],
                                    'Промокод' => [
                                        'price' => $vsp_cart_applyed_promotions['sum_value'] . ' р.',
                                        'additional_info' => !empty($promotion) ? 'Используемый промокод: ' . $promotion : '',
                                    ],
                                    'Бонусные баллы' => [
                                        'price' => '0 р.',
                                        'additional_info' => 'Бонусные баллы не начисляются за заказы, оформленные с использованием промокода или ранее накопленных баллов.',
                                    ]
                                ];
                            @endphp
                            @foreach($order_data as $param_name => $param_data)
                                <div class="flex flex-row w-full justify-between gap-2 pt-0.5">
                                    <div class="">
                                        <p class="flex items-center text-sm font-normal text-secondary">
                                            {{ $param_name }}
                                            @if (!empty($param_data['additional_info']))
                                                <button data-popover-target="popover-description-{{ $loop->iteration }}" data-popover-placement="bottom-end" type="button">
                                                    <svg class="w-4 h-4 ml-0.5 text-secondary-dark hover:text-secondary-very-dark" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </p>
                                            <div data-popover id="popover-description-{{ $loop->iteration }}" role="tooltip" class="absolute z-10 invisible inline-block text-sm text-secondary transition-opacity duration-200 bg-white border border-secondary-very-dark rounded-lg shadow-sm opacity-0 w-72">
                                                <div class="p-3 space-y-2">
                                                    <p>{{ $param_data['additional_info'] }}</p>
                                                </div>
                                                <div data-popper-arrow></div>
                                            </div>
                                        @else
                                            </p>
                                        @endif
                                    </div>

                                    <div class="">
                                        <p class="text-sm font-normal">{{ $param_data['price'] }}</p>
                                    </div>
                                </div>
                            @endforeach

                            <div class="flex flex-row w-full justify-between gap-2 pt-1.5">
                                <div class="">
                                    <p class="text-sm font-medium">Итого к оплате</p>
                                </div>

                                <div class="">
                                    <p class="text-sm font-medium">{{ $vsp_cart_price['total'] }} р.</p>
                                </div>
                            </div>

                            <button class="animate-wiggle my-5 w-full rounded-lg bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-90% px-6 py-3 font-medium text-white hover:transform hover:scale-105 duration-200">
                                Оформить заказ
                            </button>

                            <div class="text-xs text-secondary-very-dark font-light">
                                <div class="flex">
                                    <div class="flex items-center h-5">
                                        <input @checked(true) id="agreements-checkbox" aria-describedby="agreements-checkbox-text" type="checkbox" value="" class="w-3.5 h-3.5">
                                    </div>
                                    <div class="ml-2">
                                        <label for="agreements-checkbox">
                                            Нажимая на кнопку «Оформить заказ», вы соглашаетесь с условиями использования и передачи данных, определенных в документах:
                                        </label>
                                        <a href="#" class="text-blue-600 hover:underline">Политика обработки персональных данных</a>.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>
@endsection
