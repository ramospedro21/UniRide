<?php

namespace App\Services\Message;

use App\Repositories\Message\MessageRepository;
use App\Events\MessageSent;

class MessageService
{
    protected $messageRepo;

    public function __construct(MessageRepository $messageRepo)
    {
        $this->messageRepo = $messageRepo;
    }

    public function sendMessage($userId, $conversationId, $content)
    {
        $message = $this->messageRepo->createMessage([
            'conversation_id' => $conversationId,
            'sender_id'       => $userId,
            'content'         => $content,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return $message->load('sender');
    }

    public function getMessages($conversationId)
    {
        return $this->messageRepo->getMessagesByConversation($conversationId);
    }

    public function markAsRead($conversationId, $userId)
    {
        return $this->messageRepo->markMessagesAsRead($conversationId, $userId);
    }

}
