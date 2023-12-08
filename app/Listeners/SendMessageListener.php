<?php

namespace App\Listeners;

use App\Events\ChatMessageEvent;
use BeyondCode\LaravelWebSockets\Facades\WebSocketRouter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMessageListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ChatMessageEvent $event)
    {
        $message = $event->message;
        $receiverId = $message->receiver_id;

        $channelName = 'chat.' . $receiverId;

        WebSocketRouter::webSocket($channelName)->broadcast()->toOthers()->emit('chat-message', [
            'message' => $message->toArray(),
        ]);
    }
}
