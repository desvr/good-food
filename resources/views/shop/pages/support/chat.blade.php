@extends('shop.layouts.app')

@section('title', 'Онлайн-чат')

@push('scripts')
    <script type="text/javascript" defer>
        $(document).ready(function() {
            Echo.private('support.chat.{{ $chat_id }}').listen('.send.support.message', response => {
                loadMessage(response);
            });

            $("#messages").scrollTop($("#messages")[0].scrollHeight);

            function sendMessage($message) {
                var message_content = $message.content ?? '';

                if (message_content.trim().length === 0) {
                    return;
                }

                var message_created_at = $message.created_at;

                var message_view = $('#chatEmptyMessageOwner').clone();
                message_view.find('#chatEmptyMessage').text(message_content);
                message_view.find('#chatEmptyMessageTime').text(message_created_at);
                $('#messages').append(message_view.removeAttr('id').removeAttr('style').show())
                    .scrollTop($("#messages")[0].scrollHeight);

                $('#messageField').val('');
            }

            $('form[name=messageForm] button').on('click', function (e){
                e.preventDefault();
                var form = $(this).closest("form");
                var formUrl = form.attr('data-action');
                var formMethod = form.attr('data-method');

                $.ajax({
                    url: formUrl,
                    type: formMethod,
                    cache: false,
                    data: {
                        chat_id: {{ $chat_id }},
                        message: $('#messageField').val(),
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        sendMessage(response);
                    }
                });
                return false;
            });

            function loadMessage($message) {
                var message_content = $message.message.content ?? '';

                if (message_content.trim().length === 0) {
                    return;
                }

                var message_created_at = $message.message.created_at;

                var message_view = $('#chatEmptyMessageRespondent').clone();
                message_view.find('#chatEmptyMessage').text(message_content);
                message_view.find('#chatEmptyMessageTime').text(message_created_at);
                $('#messages').append(message_view.removeAttr('id').removeAttr('style').show())
                    .scrollTop($("#messages")[0].scrollHeight);

                markMessageAsRead($message.message.id);
            }

            function markMessageAsRead($message_id) {
                var url = "{{ route('support.chat.mark_message_as_read', ':message_id') }}";
                url = url.replace(':message_id', $message_id);

                $.ajax({
                    url: url,
                    type: 'POST',
                    cache: false,
                    async: true,
                });
            }
        });
    </script>
@endpush

@section('content')
    <div class="pt-8">
        <div class="container mx-auto max-w-6xl">
            <div class="bg-white rounded-lg border flex-1 px-4 py-2 sm:p-6 justify-between flex flex-col">
                <div class="flex sm:items-center justify-between pb-2 sm:pb-5 border-b border-b-secondary-bright">
                    <div class="relative flex items-center space-x-4">
                        <div class="relative">
                            <span class="absolute text-green-500 right-0 bottom-0">
                                <svg width="20" height="20">
                                    <circle cx="8" cy="8" r="8" fill="currentColor"></circle>
                                </svg>
                            </span>
                            <img src="{{ asset($support_avatar) }}" alt="" class="w-10 sm:w-16 h-10 sm:h-16 rounded-full">
                        </div>
                        <div class="flex flex-col leading-tight">
                            <div class="items-center">
                                <span class="text-lg font-bold text-secondary">Ресторан GoodFood</span>
                            </div>
                            <span class="text-md text-secondary-very-dark">Техническая поддержка</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" class="inline-flex items-center justify-center rounded-lg border h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="messages" class="flex flex-col space-y-4 py-4 overflow-y-auto" style="height: calc(100vh - 500px)">
                    <div id="chatEmptyMessageOwner" class="chat-message" style="display:none">
                        <div class="flex items-center justify-end">
                            <div class="flex flex-col space-y-2 text-sm max-w-lg mx-2 order-1 items-end">
                                <div>
                                    <div class="text-right">
                                        <span id="chatEmptyMessage" class="px-3 py-2 rounded-lg inline-block rounded-br-none bg-blue-500 text-white"></span>
                                    </div>
                                    <div class="text-right">
                                        <span id="chatEmptyMessageTime" class="font-light text-secondary-dark"></span>
                                    </div>
                                </div>
                            </div>
                            <img id="chatEmptyMessageAvatar" src="{{ asset($user->avatar) }}" class="w-10 h-10 rounded-full order-2">
                        </div>
                    </div>

                    <div id="chatEmptyMessageRespondent" class="chat-message" style="display:none">
                        <div class="flex items-center justify-start">
                            <div class="flex flex-col space-y-2 text-sm max-w-lg mx-2 order-2 items-start">
                                <div>
                                    <div class="text-left">
                                        <span id="chatEmptyMessage" class="px-3 py-2 rounded-lg inline-block rounded-bl-none bg-secondary-semi-bright text-secondary"></span>
                                    </div>
                                    <div class="text-left">
                                        <span id="chatEmptyMessageTime" class="font-light text-secondary-dark"></span>
                                    </div>
                                </div>
                            </div>
                            <img id="chatEmptyMessageAvatar" src="{{ $support_avatar }}" class="w-10 h-10 rounded-full order-1">
                        </div>
                    </div>

                    @foreach($messages as $message)
                        @if ($message['from_admin'])
                            @component('shop.components.support.chat.message_respondent', [
                                'message_text' => $message['content'],
                                'message_time' => $message['created_at'],
                                'avatar' => asset($support_avatar),
                            ])@endcomponent
                        @else
                            @component('shop.components.support.chat.message_owner', [
                                'message_text' => $message['content'],
                                'message_time' => $message['created_at'],
                                'avatar' => asset($user->avatar),
                            ])@endcomponent
                        @endif
                    @endforeach
                </div>

                @if (empty($chat_id))
                    <form method="POST" action="{{ route('support.chat.store') }}">
                        @csrf

                        <div class="flex items-center justify-center rounded-lg">
                            <button type="submit" class="btn-add-to-cart-big">
                                Начать общение
                            </button>
                        </div>
                    </form>
                @else
                    <form name="messageForm" data-method="POST" data-action="{{ route('support.chat.send') }}">
                        @csrf

                        <div class="flex items-center px-3 py-2 rounded-lg bg-gray-50">
                            <button type="button" class="inline-flex justify-center p-2 text-gray-500 rounded-lg cursor-pointer hover:text-gray-900 hover:bg-gray-100">
                                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 18">
                                    <path fill="currentColor" d="M13 5.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0ZM7.565 7.423 4.5 14h11.518l-2.516-3.71L11 13 7.565 7.423Z"/>
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 1H2a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1Z"/>
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0ZM7.565 7.423 4.5 14h11.518l-2.516-3.71L11 13 7.565 7.423Z"/>
                                </svg>
                                <span class="sr-only">Upload image</span>
                            </button>

                            <input id="messageField" type="text" class="block mx-4 p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Сообщение..."></input>

                            <button type="submit" id="sendMessageButton" class="inline-flex justify-center p-2 text-blue-600 rounded-full cursor-pointer hover:bg-blue-100">
                                <svg class="w-5 h-5 rotate-90 rtl:-rotate-90" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                                    <path d="m17.914 18.594-8-18a1 1 0 0 0-1.828 0l-8 18a1 1 0 0 0 1.157 1.376L8 18.281V9a1 1 0 0 1 2 0v9.281l6.758 1.689a1 1 0 0 0 1.156-1.376Z"/>
                                </svg>
                                <span class="sr-only">Отправить</span>
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
