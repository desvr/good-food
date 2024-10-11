<div class="card">
    <div class="main-content-app pt-0">
        <div class="main-content-body main-content-body-chat h-100">
            <div class="main-chat-header pt-3 d-block d-sm-flex">
                <div class="main-img-user online"><img alt="avatar" src="{{ asset($chat->created_by_user->avatar) }}"></div>
                <div class="main-chat-msg-name">
                    <h6>{{ $chat->created_by_user->name }}</h6>
                    <small class="me-3">{{ $chat->created_by_user->phone_formatted }}</small>
                </div>
                <nav class="nav">
                    <div class="">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search ...">
                            <span class="input-group-text btn bg-white text-muted border-start-0"><i class="fe fe-search"></i></span>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a class="nav-link" href="javascript:void(0)" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fe fe-more-horizontal"></i></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="javascript:void(0)"><i class="fe fe-phone-call me-1"></i> Phone Call</a>
                            <a class="dropdown-item" href="javascript:void(0)"><i class="fe fe-video me-1"></i> Video Call</a>
                            <a class="dropdown-item" href="javascript:void(0)"><i class="fe fe-user-plus me-1"></i> Add Contact</a>
                            <a class="dropdown-item" href="javascript:void(0)"><i class="fe fe-trash-2 me-1"></i> Delete</a>
                        </div>
                    </div>
                </nav>
            </div>
            <!-- main-chat-header -->
            <div class="main-chat-body flex-2 overflow-y-auto px-3 pb-4" id="chatBody">
                <div id="messages">
                    <div id="chatEmptyMessageRespondent" class="media chat-left" style="display:none">
                        <div class="main-img-user online">
                            <img alt="avatar" src="{{ asset($chat->created_by_user->avatar) }}">
                        </div>
                        <div class="media-body">
                            <div id="chatEmptyMessage" class="main-msg-wrapper py-2 px-3"></div>
                            <div>
                                <span id="chatEmptyMessageTime"></span> <a href="javascript:void(0)"><i class="icon ion-android-more-horizontal"></i></a>
                            </div>
                        </div>
                    </div>

                    <div id="chatEmptyMessageOwner" class="media flex-row-reverse chat-right" style="display:none">
                        <div class="main-img-user online">
                            <img id="avatar" alt="avatar" src="{{ asset($support_avatar) }}">
                        </div>
                        <div class="media-body">
                            <div id="chatEmptyMessage" class="main-msg-wrapper py-2 px-3.5"></div>
                            <div>
                                <span id="chatEmptyMessageTime"></span> <a href="javascript:void(0)"><i class="icon ion-android-more-horizontal"></i></a>
                            </div>
                        </div>
                    </div>

                    @foreach($messages as $message)
                        @if ($message['from_admin'])
                            <div class="media flex-row-reverse chat-right">
                                <div class="main-img-user online">
                                    <img alt="avatar" src="{{ asset($support_avatar) }}">
                                </div>
                                <div class="media-body">
                                    <div id="chatEmptyMessage" class="main-msg-wrapper py-2 px-3.5">
                                        {{ $message['content'] }}
                                    </div>
                                    <div>
                                        <span id="chatEmptyMessageTime">{{ $message['created_at'] }}</span> <a href="javascript:void(0)"><i class="icon ion-android-more-horizontal"></i></a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="media chat-left">
                                <div class="main-img-user online">
                                    <img alt="avatar" src="{{ asset($chat->created_by_user->avatar) }}">
                                </div>
                                <div class="media-body">
                                    <div id="chatEmptyMessage" class="main-msg-wrapper py-2 px-3">
                                        {{ $message['content'] }}
                                    </div>
                                    <div>
                                        <span id="chatEmptyMessageTime">{{ $message['created_at'] }}</span> <a href="javascript:void(0)"><i class="icon ion-android-more-horizontal"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <form name="messageForm" data-method="POST" data-action="{{ route('admin.chat.send') }}" data-chat-id="{{ $chat->id }}" class="main-chat-footer">
                @csrf

                <input id="messageField" class="form-control mx-4" placeholder="Ваше сообщение" type="text">
                <a class="nav-link" data-bs-toggle="tooltip" href="javascript:void(0)"><i class="fe fe-paperclip"></i></a>
                <button type="submit" class="btn btn-icon btn-primary py-2.5">
                    <i class="fa fa-paper-plane-o"></i>
                </button>
            </form>
        </div>
    </div>
</div>
