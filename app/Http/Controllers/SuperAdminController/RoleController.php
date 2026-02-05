<?php

namespace App\Http\Controllers\SuperAdminController;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function updateRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($request->user_id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ],404);
        }

        // if($user->role->name== 'employee'){
        //     return response()->json([
        //         'message' => 'User cannot update role'
        //     ],403);
        // }

        $userRole = $user->role_id;
        if ($request->role_id != 1 && $userRole != $request->role_id) {
            $user->role_id = $request->role_id;
        } else {
            return response()->json([
                'message' => 'User role cannot be superadmin or same role'
            ],403);
        }

        $user->save();

        return response()->json([
            'message' => 'User role updated successfully'
        ],409);
    }
}
