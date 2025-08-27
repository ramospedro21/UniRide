<?php

namespace App\Repositories\Conversation;

use App\Models\Conversation;

class ConversationRepository
{
    public function getUserConversations($userId)
    {
        return Conversation::where('user_one_id', $userId)
            ->orWhere('user_two_id', $userId)
            ->with(['messages'])
            ->get();
    }

    public function getConversationBetween($userOneId, $userTwoId)
    {
        return Conversation::where(function($q) use ($userOneId, $userTwoId) {
            $q->where('user_one_id', $userOneId)
            ->where('user_two_id', $userTwoId);
        })->orWhere(function($q) use ($userOneId, $userTwoId) {
            $q->where('user_one_id', $userTwoId)
            ->where('user_two_id', $userOneId);
        })->first();
    }

    public function create(array $data)
    {
        return Conversation::create($data);
    }
}
