<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use App\Models\ProjectMake;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Str;
// use Illuminate\Support\Facades\Mail;


class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }
    public function register2(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id'  => 3, // employee
        ]);

        if (!$user) {
            return response()->json([
                'message' => 'registration unsucessful'
            ]);
        }

        return response()->json([
            'message' => 'registration successful'
        ]);
    }

    public function login2(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();

        // Optional: revoke old tokens
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        // updatethat user login time 
        $today = Carbon::today();

        $loginLog = LoginLog::where('user_id', $user->id)
            ->whereDate('login_at', $today)
            ->first();

        if ($loginLog) {
            // update login time
            $loginLog->update([
                'login_at' => now()
            ]);
        } else {
            // create new row
            LoginLog::create([
                'user_id'  => $user->id,
                'login_at' => now()
            ]);
        }
        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role_id' => $user->role_id,
            ]
        ]);
    }
    public function forgotPasswordSimple(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6'
        ]);
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        return response()->json([
            'message' => 'password updated successfully'
        ]);
    }

    public function profile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'user unauthorised'
            ], 401);
        }

        // Get all projects of this user through pivot table
        $projects = $user->projects()->with('teamLead')->get();

        if ($projects->isEmpty()) {
            return response()->json([
                'message' => 'No project assigned to this user'
            ], 404);
        }

        $projectData = $projects->map(function ($project) {
            return [
                'project_id' => $project->id,
                'project_name' => $project->name,

                'team_lead' => $project->teamLead ? [
                    'id' => $project->teamLead->id,
                    'name' => $project->teamLead->name,
                    'email' => $project->teamLead->email,
                ] : null,
            ];
        });

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role_id' => $user->role_id,
            ],
            'projects' => $projectData
        ]);
    }


    public function logout2(Request $request)
    {
        $user = $request->user(); // authenticated via sanctum

        $today = Carbon::today();

        $loginLog = LoginLog::where('user_id', $user->id)
            ->whereDate('login_at', $today)
            ->first();

        if ($loginLog) {
            // update login time
            $loginLog->update([
                'logout_at' => now()
            ]);
        } else {
            return response()->json([
                'message' => 'Logout unsuccessful'
            ]);
        }

        // delete only the current token
        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful'
        ]);
    }
}
