<?php

namespace App\Services\Conversation;

use App\Repositories\Conversation\ConversationRepository;

class ConversationService
{
    protected $conversationRepo;

    public function __construct(ConversationRepository $conversationRepo)
    {
        $this->conversationRepo = $conversationRepo;
    }

    public function getConversationsForUser($userId)
    {
        $conversations = $this->conversationRepo->getUserConversations($userId);

        return $conversations->map(function($conv) use ($userId) {
            $otherUserId = $conv->user_one_id === $userId ? $conv->user_two_id : $conv->user_one_id;

            $otherUser = $conv->user_one_id === $userId ? $conv->userTwo : $conv->userOne; // Relacionamento
            $lastMessage = $conv->messages->last();
            $unreadCount = $conv->messages->where('read', false)
                                        ->where('sender_id', '!=', $userId)
                                        ->count();

            return [
                'conversation_id'   => $conv->id,
                'other_user_id'     => $otherUserId,
                'other_user_name'   => $otherUser->name ?? 'Usuário',
                'last_message'      => $lastMessage?->content,
                'last_message_at'   => $lastMessage?->created_at,
                'unread_count'      => $unreadCount,
            ];
        })->sortByDesc('last_message_at')->values();
    }

    public function createConversation($userOneId, $userTwoId)
    {
        $conversation = $this->conversationRepo->getConversationBetween($userOneId, $userTwoId);

        if ($conversation) {
            return $conversation;
        }

        return $this->conversationRepo->create([
            'user_one_id' => $userOneId,
            'user_two_id' => $userTwoId,
        ]);
    }

}
