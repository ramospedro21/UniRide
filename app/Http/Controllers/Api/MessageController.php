<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Message\MessageService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'content'         => 'required|string',
        ]);

        $message = $this->messageService->sendMessage(
            $request->user()->id,
            $request->conversation_id,
            $request->content
        );

        return $this->respondWithOk($message, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($conversationId)
    {
        $messages = $this->messageService->getMessages($conversationId);

        return $this->respondWithOk($messages);
    }

    /**
     * Mark messages as read in a conversation.
     */
    public function markAsRead(Request $request, $conversationId)
    {
        $userId = $request->user()->id;
        $this->messageService->markAsRead($conversationId, $userId);

        return $this->respondWithOk(['status' => 'success']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
