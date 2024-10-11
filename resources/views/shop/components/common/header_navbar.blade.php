@auth()
    @if (!empty($vsp_user_data['telegram_token']) && empty($vsp_user_data['telegram_user_id']))
        @component('shop.components.common.top_banner', [
            'title' => 'Telegram Bot',
            'button_title' => 'Подписаться',
            'button_pointer' => true,
            'button_url' => 'https://t.me/goodfood_testbot?start=' . $vsp_user_data['telegram_token'],
        ])
            Подписывайтесь на наш Бот в Telegram, чтобы получать важные уведомления о заказах и акциях!
        @endcomponent
    @endif
@endauth

<div class="py-2 bg-white">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-4 lg:px-0">
        <a href="{{ route('home') }}">
            <img src="{{ asset($vsp_app_logo) }}" style="height: 64px" alt=""/>
        </a>
        <div class="hidden items-center justify-between md:order-1 md:flex md:w-auto" id="navbar-sticky">
            <ul class="flex flex-col md:flex-row md:space-x-8 font-medium text-base text-secondary dark:bg-secondary-dark">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-primary duration-200 {{ request()->routeIs('home') ? "text-primary" : "" }}">
                        Главная
                    </a>
                </li>
                <li>
                    <a href="{{ route('worktime') }}" class="hover:text-primary duration-200 {{ request()->routeIs('worktime') ? "text-primary" : "" }}">
                        Заведение
                    </a>
                </li>
            </ul>
        </div>
        <div class="md:order-2">
            @auth()
                <div class="relative">
                    <img id="avatarButton"
                         type="button"
                         data-dropdown-toggle="userDropdown"
                         data-dropdown-placement="bottom-start"
                         class="w-10 h-10 rounded-full cursor-pointer"
                         src="{{ asset($vsp_user_data['avatar']) }}"
                    >
                    @if (!empty($vsp_unread_support_messages_count))
                        <span class="absolute top-0 left-7 h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span class="border-2 border-white absolute top-0 inline-flex rounded-full h-3 w-3 bg-primary"></span>
                        </span>
                    @endif
                </div>

                <div id="userDropdown" class="z-100 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-40">
                    <div class="px-4 py-3 text-sm text-gray-900">
                        <div class="text-md font-bold">{{ $vsp_user_data['name'] }}</div>
                        <div class="text-sm">{{ \App\Helpers\Formatter\PhoneFormatter::phoneFormatterDelimiters($vsp_user_data['phone']) }}</div>
                        <div class="text-sm pt-1.5">
                            <a href="#" class="bg-primary text-white text-xs font-medium me-2 px-2 py-0.5 rounded hover:bg-primary-dark transition duration-200">
                                500 бонусов
                            </a>
                        </div>
                    </div>
                    <ul class="py-2 text-sm text-gray-700" aria-labelledby="avatarButton">
                        <a href="{{ route('order.index') }}">
                            <p href="{{ route('order.index') }}" class="block px-4 py-2 hover:bg-gray-100 transition duration-200">Заказы</p>
                        </a>
                        <a href="{{ route('support.chat') }}" class="flex hover:bg-gray-100 transition duration-200">
                            <p class="block px-4 py-2">Онлайн чат</p>
                            @if (!empty($vsp_unread_support_messages_count))
                                <div class="flex flex-1 justify-end items-center pr-4">
                                    <span class="flex items-center justify-center h-5 w-5 bg-primary text-white text-xs rounded-full">
                                        {{ $vsp_unread_support_messages_count }}
                                    </span>
                                </div>
                            @endif
                        </a>
                    </ul>
                    <div class="py-1">
                        <form method="POST" action="{{ route('auth.logout') }}" class="text-sm text-gray-700 hover:bg-gray-100 transition duration-200 mb-0">
                            @csrf
                            <button type="submit" class="block px-4 py-2">
                                Выйти из аккаунта
                            </button>
                        </form>
                    </div>
                </div>
            @elseguest()
                <a type="button"
                   href="{{ route('auth.loginPage') }}"
                   class="px-4 py-2 justify-center text-center text-sm font-bold duration-200 text-secondary bg-secondary-light hover:bg-secondary-bright rounded-lg cursor-pointer">
                    Войти
                </a>
            @endauth
        </div>
    </div>
</div>

<div class="sticky top-0 left-0 z-90 shadow-md bg-white px-4 lg:px-0">
    <nav class="py-2.5 flex justify-between max-w-6xl mx-auto">
        <div class="hidden lg:flex overflow-auto gap-1">
            @foreach($vsp_categories as $category)
                <a href="{{ route('category.show', ['category' => $category->slug]) }}"
                   class="flex rounded-lg bg-secondary-light text-secondary px-4 py-2 duration-200 hover:bg-secondary-bright text-sm font-bold
                   {{ request()->routeIs('category.show') && request()->route()->parameter('category') === $category->slug ? "bg-slate-200" : "" }}
                   ">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <div class="lg:hidden">
            <button type="button" id="dropdownCatalogButton" data-dropdown-toggle="dropdownCatalog" data-dropdown-delay="200"
                    class="font-bold bg-secondary-light text-secondary hover:bg-secondary-bright rounded-lg text-sm px-5 py-1.5 duration-200 text-center inline-flex items-center">
                Каталог
                <svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                </svg>
            </button>
            <!-- Dropdown menu -->
            <div id="dropdownCatalog" class="z-[90] hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                <ul class="py-2 text-sm font-semibold" aria-labelledby="dropdownCatalogButton">
                    @foreach($vsp_categories as $category)
                    <li>
                        <a href="{{ route('category.show', ['category' => $category->slug]) }}" class="block px-4 py-2 hover:bg-slate-100
                           {{ request()->routeIs('category.show') && request()->route()->parameter('category') === $category->slug ? "bg-slate-200" : "" }}
                        ">
                            {{ $category->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
                <div class="py-2">
                    <a href="{{ route('worktime') }}" class="block px-4 py-2 text-sm hover:bg-slate-100 font-semibold
                        {{ request()->routeIs('worktime') ? "bg-slate-200" : "" }}
                    ">
                        Заведение
                    </a>
                </div>
            </div>
        </div>

        <div class="cart_button">
            <a href="{{ route('cart') }}"
               class="flex rounded-lg bg-primary-light text-primary px-4 py-2 duration-200 text-sm font-bold hover:bg-primary hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" class="bi bi-bag-check-fill" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10.5 3.5a2.5 2.5 0 0 0-5 0V4h5v-.5zm1 0V4H15v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4h3.5v-.5a3.5 3.5 0 1 1 7 0zm-.646 5.354a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3z"/>
                </svg>
                <div class="pl-2 z-40 font-bold" id="cart_total_price">
                    @if ($vsp_cart_empty)
                        Корзина
                    @else
                        {{ $vsp_cart_price['total'] }} руб.
                    @endif
                </div>
            </a>
        </div>
    </nav>
</div>
