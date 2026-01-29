<?php

namespace App\Http\Controllers;

use App\Models\GroupChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    // Send message
    public function sendMessage(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'message' => 'required|string',
        ]);

        // Check user is assigned to project (employee or team lead)
        if (!Auth::user()->projects->contains($request->project_id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $chat = GroupChat::create([
            'project_id' => $request->project_id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return response()->json(['message' => 'Message sent', 'chat' => $chat], 201);
    }

    // Get messages
    public function getMessages($project_id)
    {
        if (!Auth::user()->projects->contains($project_id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $chats = GroupChat::where('project_id', $project_id)
            ->with('sender:id,name')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($chats);
    }
}
