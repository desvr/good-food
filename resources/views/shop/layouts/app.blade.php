<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>GoodFood - @yield('title', '')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('js/jquery.min.js', config('app.ssl')) }}"></script>
    <script src="{{ asset('js/flowbite.min.js', config('app.ssl')) }}"></script>
    <script src="{{ asset('js/jquery.mask.min.js', config('app.ssl')) }}"></script>
{{--    <link href="https://unpkg.com/tailwindcss@1.8.1/dist/tailwind.min.css" rel="stylesheet">--}}
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @stack('scripts')
</head>
<body class="bg-secondary-light">

@component('shop.components.common.header_navbar')@endcomponent

<div class="pb-1">
    <div class="pb-4 px-4">
        @if (session('status') && session('status_message'))
            <div class="mt-6 alert alert-{{ session('status') }}">
                {{ session('status_message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button>
            </div>
        @endif

        @yield('content')
    </div>

    {{--  Product page modal  --}}
    <div id="divModalShow"></div>

    @component('shop.components.common.footer')@endcomponent
</div>

</body>
</html>
