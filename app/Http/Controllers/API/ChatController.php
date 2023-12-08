<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetChatRequest;
use App\Http\Requests\StoreChatRequest;
use App\Models\Chat;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Responses\PrettyJsonResponse;

class ChatController extends Controller
{
    public function index(GetChatRequest $request): JsonResponse
    {
        try {
            $isPrivate = 1;
            if ($request->has('is_private')) {
                $isPrivate = (int)$request->get('is_private');
            }

            $chats = Chat::where('is_private', $isPrivate)
                ->hasParticipant(auth()->user()->id)
                ->whereHas('messages')
                ->with('lastMessage.user', 'participants.user')
                ->latest('updated_at')
                ->get();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data ditemukan', 'data' => $chats]);
    }

    public function store(StoreChatRequest $request): JsonResponse
    {
        try {
            $data = $this->prepareStoreData($request);
            if ($data['userId'] === $data['otherUserId']) {
                return $this->error('You can not create a chat with your own');
            }

            $previousChat = $this->getPreviousChat($data['otherUserId']);

            if ($previousChat === null) {
                $chat = Chat::create($data['data']);
                // return new PrettyJsonResponse(['success' => true, 'message' => 'Data ditemukan', 'data' => $data], 201);

                $chat->participants()->createMany([
                    [
                        'user_id' => $data['userId']
                    ],
                    [
                        'user_id' => $data['otherUserId']
                    ]
                ]);

                $chat->refresh()->load('lastMessage.user', 'participants.user');

                return new PrettyJsonResponse(['success' => true, 'message' => 'Data ditemukan', 'data' => $chat], 201);
            }

            // return $this->success($previousChat->load('lastMessage.user', 'participants.user'));
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
        return new PrettyJsonResponse(['success' => true, 'message' => 'Data ditemukan', 'data' => $previousChat->load('lastMessage.user', 'participants.user')]);
        // return new PrettyJsonResponse(['success' => true, 'message' => 'Data ditemukan', 'data' => $chat], 201);
    }

    private function getPreviousChat($otherUserId): mixed
    {

        $userId = auth()->user()->id;

        return Chat::where('is_private', 1)
            ->whereHas('participants', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->whereHas('participants', function ($query) use ($otherUserId) {
                $query->where('user_id', $otherUserId);
            })
            ->first();
    }

    private function prepareStoreData(StoreChatRequest $request): array
    {
        $data = $request->all();
        $data['started_at'] = now();
        $otherUserId = $data['user_id'];
        unset($data['user_id']);
        $data['created_by'] = auth()->user()->id;

        return [
            'otherUserId' => $otherUserId,
            'userId' => auth()->user()->id,
            'data' => $data,
        ];
    }

    public function show(Chat $chat): JsonResponse
    {
        try {
            $chat->load('lastMessage.user', 'participants.user');
            return new PrettyJsonResponse(['success' => true, 'message' => 'Data ditemukan', 'data' => $chat]);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }
    }
}
