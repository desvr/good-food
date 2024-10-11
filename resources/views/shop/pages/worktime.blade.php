@extends('shop.layouts.app')

@section('title', 'Home')

@section('content')
    @component('shop.components.common.header_text')Заведение@endcomponent

    <section class="mx-auto grid max-w-6xl grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-slate-100 rounded-lg px-5 py-4">
            <div class="pb-2">
                <div class="text-sm font-medium text-secondary">Телефон</div>
                <div class="text-sm">+7 (937)273-35-35</div>
            </div>

            <div class="pb-2">
                <div class="text-sm font-medium text-secondary">Адрес</div>
                <div class="text-sm">г. Ульяновск, ул. Бородина 20</div>
            </div>

            <div class="pb-2">
                <div class="text-sm font-medium text-secondary">Вопросы, отзывы и предложения</div>
                <div class="text-sm">info@goodfood.ru</div>
            </div>

            <div class="flex flex-col pt-2">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                            <table class="min-w-full text-center text-sm font-light">
                                <thead class="border-b font-medium dark:border-neutral-500">
                                    <tr>
                                        <th scope="col">Пн</th>
                                        <th scope="col">Вт</th>
                                        <th scope="col">Ср</th>
                                        <th scope="col">Чт</th>
                                        <th scope="col">Пт</th>
                                        <th scope="col">Сб</th>
                                        <th scope="col">Вс</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="whitespace-nowrap">11:00</td>
                                        <td class="whitespace-nowrap">10:00</td>
                                        <td class="whitespace-nowrap">10:00</td>
                                        <td class="whitespace-nowrap">10:00</td>
                                        <td class="whitespace-nowrap">10:00</td>
                                        <td class="whitespace-nowrap">10:00</td>
                                        <td class="whitespace-nowrap">10:00</td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap">23:00</td>
                                        <td class="whitespace-nowrap">23:00</td>
                                        <td class="whitespace-nowrap">23:00</td>
                                        <td class="whitespace-nowrap">23:00</td>
                                        <td class="whitespace-nowrap">23:00</td>
                                        <td class="whitespace-nowrap">23:59</td>
                                        <td class="whitespace-nowrap">23:59</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
