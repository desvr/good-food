<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>GoodFood - Вход</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @vite('resources/css/app.css')
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

        $(document).ready(function() {
            $("#phone").val('+7').mask("+7 (999) 999-9999");
            $("#birthday").mask("99.99.9999");
            setSendCode('sendCodeButton');

            function setCloseEnterCodeButton($buttonId, $modalId) {
                $('[id=' + $buttonId + ']').on('click', function () {
                    $('div[id=' + $modalId + ']').hide();
                });
            }

            function setSendCode($buttonId) {
                $('button[id=' + $buttonId + ']').on('click', function (e) {
                    e.preventDefault();
                    var modalUrl = $(this).data('attr');
                    var modalId = $(this).data('target');

                    $.ajax({
                        url: '{{ route('auth.sendCode') }}',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            phone: $('#phone').val(),
                            name: $('#name').val(),
                            birthday: $('#birthday').val(),
                        },
                        success: function (response) {
                            if (response['failed']) {
                                $('#failedPhone').html(response['failed']);
                                return false;
                            } else {
                                $('#failedPhone').html('');
                            }

                            var data = {
                                'phone': $('#phone').val(),
                                'name': $('#name').val(),
                                'birthday': $('#birthday').val(),
                            };

                            $.ajax({
                                url: modalUrl,
                                type: 'GET',
                                cache: true,
                                success: function (response) {
                                    $('#divEnterCodeModalShow').html(response);
                                    $('div[id=' + modalId + ']').show();

                                    setCloseEnterCodeButton('closeEnterCodeButton', modalId);
                                    setVerifyCode('verifyCode', data);
                                    $('input[id=code-1]').focus();
                                }
                            });
                        }
                    });
                    return false;
                });
            }

            function setVerifyCode($formName, $data) {
                $('form[name=' + $formName + '] button').off('click').on('click', function (e) {
                    e.preventDefault();
                    var form = $(this).closest("form");
                    var formUrl = form.attr('data-action');
                    var formMethod = form.attr('data-method');

                    var code = [];
                    var codes = form.find('input[name^=code-]');
                    codes.each(function(index_item, code_item) {
                        code[index_item] = code_item.value;
                    });

                    $.ajax({
                        url: formUrl,
                        type: formMethod,
                        cache: false,
                        data: {
                            purpose: 'register',
                            code: code,
                            data: $data,
                        },
                        dataType: 'JSON',
                        success: function (response) {
                            if (response['failed']) {
                                $('#failedVerifyCode').html(response['failed']);
                                return false;
                            } else {
                                $('#failedVerifyCode').html('');
                            }

                            if (response['redirect']) {
                                window.location.href = response['redirect'];
                            } else {
                                window.location.href = '/index.php';
                            }
                        }
                    });
                    return false;
                });
            }
        });
    </script>
{{--    <script src="https://cdn.jsdelivr.net/npm/jquery.maskedinput@1.4.1/src/jquery.maskedinput.min.js" type="text/javascript"></script>--}}
    @stack('scripts')
</head>
<body>
    <div class="flex flex-wrap">
        <div class="flex w-full flex-col md:w-1/2">
            <div class="flex justify-center pt-12 md:-mb-36 md:pt-36">
                <a href="{{ route('home') }}">
                    <img src="{{ asset($app_logo) }}" style="height: 96px" alt=""/>
                </a>
            </div>
            <div class="mx-auto max-w-md my-auto flex flex-col justify-center pt-8 md:pt-0 md:px-6 text-center">
                <p class="text-3xl font-bold">Регистрация</p>
                <p class="mt-2 text-gray-500">Укажите номер телефона и основную информацию.</p>

                <div class="flex flex-col pt-4">
                    <div class="focus-within:border-b-secondary relative flex overflow-hidden border-b-2 border-b-secondary-dark transition">
                        <input class="w-full flex-1 appearance-none px-4 py-2 text-base text-gray-700 placeholder-gray-400 focus:outline-none text-center"
                               type="text"
                               id="phone"
                               name="phone"
                               placeholder="+7"
                               maxlength="12"
                               minlength="11"
                               autofocus
                               required
                        />
                    </div>
                </div>

                <div class="flex flex-col pt-4">
                    <div class="focus-within:border-b-secondary relative flex overflow-hidden border-b-2 border-b-secondary-dark transition">
                        <input class="w-full flex-1 appearance-none px-4 py-2 text-base text-gray-700 placeholder-gray-400 focus:outline-none text-center"
                               type="text"
                               id="name"
                               name="name"
                               placeholder="Имя"
                               required
                        />
                    </div>
                </div>

                <div class="flex flex-col pt-4">
                    <div class="focus-within:border-b-secondary relative flex overflow-hidden border-b-2 border-b-secondary-dark transition">
                        <input class="w-full flex-1 appearance-none px-4 py-2 text-base text-gray-700 placeholder-gray-400 focus:outline-none text-center"
                               type="text"
                               id="birthday"
                               name="birthday"
                               placeholder="Дата рождения"
                               required
                        />
                    </div>
                </div>

                <p id="failedPhone" class="mt-4 text-sm text-center text-primary"></p>

                <button id="sendCodeButton"
                        type="button"
                        class="mt-8 w-full rounded-lg bg-primary hover:bg-primary-dark px-4 py-2 text-center text-base font-semibold text-white shadow-md ring-gray-500 ring-offset-2 transition focus:ring-2"
                        data-attr="{{ route('auth.getEnterCodeModal') }}"
                        data-target="enterCodeModal"
                >
                    Подтвердить по СМС
                </button>

                <div class="py-12 text-center">
                    <p class="whitespace-nowrap text-gray-600">
                        Есть аккаунт?
                        <a href="{{ route('auth.loginPage') }}" class="underline-offset-4 font-semibold text-gray-900 underline hover:text-primary-dark transition duration-200">Авторизуйтесь</a>
                    </p>
                </div>
            </div>
        </div>

        <div class="pointer-events-none relative hidden h-screen select-none bg-black md:block md:w-1/2">
            <div class="absolute bottom-0 z-10 px-8 text-white opacity-100">
                <p class="mb-8 text-3xl font-semibold leading-10">We work 10x faster than our compeititors and stay consistant. While they're bogged won with techincal debt, we're realeasing new features.</p>
                <p class="mb-4 text-3xl font-semibold">John Elmond</p>
                <p class="">Founder, Emogue</p>
                <p class="mb-7 text-sm opacity-70">Web Design Agency</p>
            </div>
            <img class="-z-1 absolute top-0 h-full w-full object-cover opacity-90" src="https://images.unsplash.com/photo-1565301660306-29e08751cc53?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=687&q=80" />
        </div>
    </div>

    {{--  Enter code modal  --}}
    <div id="divEnterCodeModalShow"></div>
</body>
</html>

