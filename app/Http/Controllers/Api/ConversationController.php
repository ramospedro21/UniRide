<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Conversation\ConversationService;
use Illuminate\Http\Request;

class ConversationController extends Controller
{

    protected $conversationService;

    public function __construct(ConversationService $conversationService) {
        $this->conversationService = $conversationService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $conversations = $this->conversationService->getConversationsForUser($userId);
        return $this->respondWithOk($conversations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);

        $conversation = $this->conversationService->createConversation(
            $request->user()->id,
            $request->receiver_id
        );

        return $this->respondWithOk($conversation, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
