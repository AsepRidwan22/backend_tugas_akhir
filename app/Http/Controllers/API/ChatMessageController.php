<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Events\NewMessageSent;
use App\Http\Requests\GetMessageRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Responses\PrettyJsonResponse;
use Illuminate\Support\Facades\Storage;

class ChatMessageController extends Controller
{
    public function index(GetMessageRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $chatId = $data['chat_id'];
            $currentPage = $data['page'];
            $pageSize = $data['page_size'] ?? 15;

            $messages = ChatMessage::where('chat_id', $chatId)
                ->with('user')
                ->latest('created_at')
                ->simplePaginate(
                    $pageSize,
                    ['*'],
                    'page',
                    $currentPage
                );

            // return $this->success($messages->getCollection());
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }
        return new PrettyJsonResponse(['success' => true, 'message' => 'Data ditemukan', 'data' => $messages->getCollection()]);
    }

    public function store(StoreMessageRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->user()->id;
            if ($request->hasFile('file')) {
                $fileName = $data['file']->getClientOriginalName();
                $filePath = Storage::disk('public')->put('files', $data['file']);

                $data['file_name'] = $fileName;
                $data['file_path'] = $filePath;
            }

            $chatMessage = ChatMessage::create($data);
            $chatMessage->load('user');
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
        // TODO send broadcast event to pusher and send notification to onesignal services
        // $this->sendNotificationToOther($chatMessage);

        return new PrettyJsonResponse(['success' => true, 'message' => 'Pesan berhasil dikirim', 'data' => $chatMessage], 201);
        // return $this->success($chatMessage, 'Message has been sent successfully.');
    }

    // private function sendNotificationToOther(ChatMessage $chatMessage) : void {

    //     // TODO move this event broadcast to observer
    //     broadcast(new NewMessageSent($chatMessage))->toOthers();

    //     $user = auth()->user();
    //     $userId = $user->id;

    //     $chat = Chat::where('id',$chatMessage->chat_id)
    //         ->with(['participants'=>function($query) use ($userId){
    //             $query->where('user_id','!=',$userId);
    //         }])
    //         ->first();
    //     if(count($chat->participants) > 0){
    //         $otherUserId = $chat->participants[0]->user_id;

    //         $otherUser = User::where('id',$otherUserId)->first();
    //         $otherUser->sendNewMessageNotification([
    //             'messageData'=>[
    //                 'senderName'=>$user->username,
    //                 'message'=>$chatMessage->message,
    //                 'chatId'=>$chatMessage->chat_id
    //             ]
    //         ]);

    //     }

    // }


}
