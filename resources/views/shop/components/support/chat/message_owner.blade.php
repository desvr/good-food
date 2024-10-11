<div class="chat-message">
    <div class="flex items-center justify-end">
        <div class="flex flex-col space-y-2 text-sm max-w-lg mx-2 order-1 items-end">
            <div>
                <div class="text-right">
                    <span class="px-3 py-2 rounded-lg inline-block rounded-br-none bg-blue-500 text-white">{{ $message_text }}</span>
                </div>
                <div class="text-right">
                    <span class="font-light text-secondary-dark">{{ $message_time }}</span>
                </div>
            </div>
        </div>
        <img src="{{ $avatar }}" class="w-10 h-10 rounded-full order-2">
    </div>
</div>
