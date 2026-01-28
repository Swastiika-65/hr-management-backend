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
        $project = ModelsProject::find($request->project_id);
        $project->employees()->syncWithoutDetaching($request->user_id);
    }

    public function switchProject(Request $request)
    {
        $user = User::find($request->user_id);
        $user->projects()->sync([$request->project_id]);
    }
}


