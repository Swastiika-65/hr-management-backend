<?php

namespace App\Http\Controllers;

use App\Models\GroupChat;
use App\Models\ProjectMake;
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
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user_id = $user->id;

        // $project = ProjectMake::where('user_id', $user_id)->first();
        // if (!$project) {
        //     return response()->json([
        //         'message' => 'No project assigned to this user'
        //     ], 404);
        // }

        // $project_id=$project->project_id;

        $chat = GroupChat::create([
            'project_id' => $request->project_id,
            'user_id' => $user_id,
            'message' => $request->message,
        ]);

        return response()->json(['message' => 'Message sent', 'chat' => $chat], 201);
    }

    // Get messages
    public function getMessages(Request $request)
    { 
        $request->validate([
            'project_id' => 'required|exists:projects,id',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $chats = GroupChat::where('project_id', $request->project_id)
            ->with('sender:id,name')
            ->orderBy('created_at', 'asc')
            ->get();


        return response()->json([
            'project_id' => $request->project_id,
            'messages' => $chats
        ]);
    }
}
