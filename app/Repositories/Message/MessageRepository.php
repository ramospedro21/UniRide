<?php

namespace App\Repositories\Message;

use App\Models\Message;

class MessageRepository
{
    public function createMessage($data)
    {
        return Message::create($data);
    }

    public function getMessagesByConversation($conversationId)
    {
        return Message::with('sender')
                  ->where('conversation_id', $conversationId)
                  ->orderBy('created_at', 'asc')
                  ->get();
    }

    public function markMessagesAsRead($conversationId, $userId)
    {
        return Message::where('conversation_id', $conversationId)
                    ->where('sender_id', '!=', $userId)
                    ->where('read', 0)
                    ->update(['read' => 1]);
    }

}
