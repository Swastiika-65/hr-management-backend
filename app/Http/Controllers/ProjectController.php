<?php

namespace App\Http\Controllers;

use App\Models\Project as ModelsProject;
use App\Models\User;
use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class ProjectController extends Controller
{
   
   public function assign(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'project_id' => 'required|exists:projects,id',
    ]);

    $project = ModelsProject::findOrFail($request->project_id);

    $project->employees()->syncWithoutDetaching($request->user_id);

    return response()->json([
        'message' => 'Project assigned successfully'
    ], 200);
}


    public function switchProject(Request $request)
    {
        $user = User::find($request->user_id);
        $user->projects()->sync([$request->project_id]);
    }
}


