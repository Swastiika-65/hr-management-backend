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
        $request->validate([
            'user_id'=>'required|exists:users,id',
            'project_id'=>'required|exists:projects,id'
        ]);
        $user = User::find($request->user_id);
       if(!$user){
        return response()->json([
            'message'=>'user not found'
        ],404);
       }

        $user->projects()->sync([$request->project_id]);
        return response()->json([
            'message'=>'project switched successfully'
        ]);
    }
}


