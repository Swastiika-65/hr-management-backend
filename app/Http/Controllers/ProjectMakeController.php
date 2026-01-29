<?php

namespace App\Http\Controllers;

use App\Models\ProjectMake;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectMakeController extends Controller
{
    public function store(Request $request)
  {
        // 🔐 Only HR & SuperAdmin can assign
        if (!in_array(Auth::user()->role->name, ['HR', 'SuperAdmin'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'team_lead_id' => 'nullable|exists:users,id',
        ]);

        $project = ProjectMake::create([
            'name' => $request->name,
            'description' => $request->description,
            'team_lead_id' => $request->team_lead_id,
        ]);
$project->users()->attach($request->user_id);
        return response()->json([
            'message' => 'Project created successfully',
            'project' => $project
        ], 201);
    }
}

