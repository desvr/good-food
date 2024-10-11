@extends('admin.layouts.app')

@section('title', 'Чат')

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            setLoadMessages();

            function sendMessage($message) {
                var message_content = $message.content ?? '';

                if (message_content.trim().length === 0) {
                    return;
                }

                var message_created_at = $message.created_at;

                var message_view = $('#chatEmptyMessageOwner').clone();
                message_view.find('#chatEmptyMessage').text(message_content);
                message_view.find('#chatEmptyMessageTime').text(message_created_at);
                $('#messages').append(message_view.removeAttr('id').removeAttr('style').show());
                $("#chatBody").scrollTop($("#chatBody")[0].scrollHeight);

                $('#messageField').val('');
            }

            function loadMessage($message) {
                var message_content = $message.message.content ?? '';

                if (message_content.trim().length === 0) {
                    return;
                }

                var message_created_at = $message.message.created_at;

                var message_view = $('#chatEmptyMessageRespondent').clone();
                message_view.find('#chatEmptyMessage').text(message_content);
                message_view.find('#chatEmptyMessageTime').text(message_created_at);
                $('#messages').append(message_view.removeAttr('id').removeAttr('style').show());
                $("#chatBody").scrollTop($("#chatBody")[0].scrollHeight);

                markMessageAsRead($message.message.id);
            }

            function markMessageAsRead($message_id) {
                var url = "{{ route('admin.chat.mark_message_as_read', ':message_id') }}";
                url = url.replace(':message_id', $message_id);

                $.ajax({
                    url: url,
                    type: 'POST',
                    cache: false,
                    async: true,
                });
            }

            function setLoadMessages() {
                $('form[name^=loadMessages]').off('click').on('click', function (e) {
                    e.preventDefault();
                    var form = $(this).closest("form");
                    var formUrl = form.attr('data-action');
                    var formMethod = form.attr('data-method');
                    var chatId = form.attr('data-chat-id');

                    $.ajax({
                        url: formUrl,
                        type: formMethod,
                        cache: false,
                        async: true,
                        success: function (response) {
                            $('div[id=messagesDiv]').html(response);
                            $("#chatBody").scrollTop($("#chatBody")[0].scrollHeight);

                            form.find('span[id="unreadMessagesCount"]').text('').hide();

                            Echo.leaveAllChannels();
                            Echo.private('support.chat.' + chatId).listen('.send.support.message', response => {
                                loadMessage(response);
                            });

                            setMessageForm();
                        }
                    });

                    return false;
                });
            }

            function setMessageForm() {
                $('form[name=messageForm] button').off('click').on('click', function (e) {
                    e.preventDefault();
                    var form = $(this).closest("form");
                    var formUrl = form.attr('data-action');
                    var formMethod = form.attr('data-method');
                    var chatId = form.attr('data-chat-id');

                    $.ajax({
                        url: formUrl,
                        type: formMethod,
                        cache: false,
                        async: true,
                        data: {
                            chat_id: chatId,
                            message: $('#messageField').val(),
                        },
                        dataType: 'JSON',
                        success: function (response) {
                            sendMessage(response);
                        }
                    });
                    return false;
                });
            }
        });
    </script>
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title">Дашборд</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Главная</a></li>
                <li class="breadcrumb-item active" aria-current="page">Дашборд</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
            <div class="card overflow-hidden">
                <div class="main-content-app pt-0 main-chat-2">
                    <div class="main-content-left main-content-left-chat">
                        <div class="card-body d-flex">
                            <div class="main-img-user online"><img alt="avatar" src="{{ asset('storage/images/logo.png') }}"></div>
                            <div class="main-chat-msg-name">
                                <h6>{{ config('app.name') }}</h6>
                                <span class="dot-label bg-success"></span><small class="me-3">Available</small>
                            </div>
                            <nav class="nav ms-auto">
                                <div class="dropdown">
                                    <a class="nav-link text-muted fs-20" href="javascript:void(0)" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fe fe-more-horizontal"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="javascript:void(0)"><i class="fe fe-user me-1"></i> Профиль</a>
                                        <a class="dropdown-item" href="javascript:void(0)"><i class="fe fe-settings me-1"></i> Настройки</a>
                                    </div>
                                </div>
                            </nav>
                        </div>

                        <div class="card-body">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ...">
                                <span class="input-group-text btn btn-primary">Search</span>
                            </div>
                        </div>

                        <div class="tab-content main-chat-list flex-2">
                            <div class="tab-pane active overflow-y-auto">
                                <div class="main-chat-list tab-pane">
                                    @foreach($chats as $chat)
                                        <form data-method="GET" data-action="{{ route('admin.chat.load_messages', ['chat_id' => $chat->id]) }}" data-chat-id="{{ $chat->id }}" name="loadMessages{{ $chat->id  }}">
                                            @csrf

                                            <div id="chatLink" class="media new border-top-0">
                                                <div class="main-img-user online">
                                                    <img alt="" src="{{ asset($chat->created_by_user->avatar) }}">
                                                    @if ($chat->messages_count)
                                                        <span id="unreadMessagesCount">{{ $chat->messages_count }}</span>
                                                    @endif
                                                </div>
                                                <div class="media-body">
                                                    <div class="media-contact-name">
                                                        <span>{{ $chat->created_by_user->name }}</span>
                                                        <span>{{ $chat->created_by_user->phone_formatted }}</span>
                                                    </div>
                                                    <div>
                                                        <p class="break-words line-clamp-2" style="text-overflow: ellipsis; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                                            {{ $chat->last_message->content ?? '(пусто)' }}
                                                        </p>
                                                        <p class="font-light font-italic pt-1">
                                                            {{ !empty($chat->last_message->updated_at) ? $chat->last_message->updated_at->diffForHumans() : '' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ...">
                                <span class="input-group-text btn btn-primary">Search</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-8">
            <div id="messagesDiv">
{{--                @include('admin.components.chat.chat_block')--}}
            </div>
        </div>
    </div>
@endsection
