<div class="chat-message">
    <div class="flex items-center justify-start">
        <div class="flex flex-col space-y-2 text-sm max-w-lg mx-2 order-2 items-start">
            <div>
                <div class="text-left">
                    <span class="px-3 py-2 rounded-lg inline-block rounded-bl-none bg-secondary-semi-bright text-secondary">{{ $message_text }}</span>
                </div>
                <div class="text-left">
                    <span class="font-light text-secondary-dark">{{ $message_time }}</span>
                </div>
            </div>
        </div>
        <img src="{{ $avatar }}" class="w-10 h-10 rounded-full order-1">
    </div>
</div>
