<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
            'reason' => 'required|string'
        ]);

        Leave::create([
            'user_id' => $request->user()->id,
            'from_date' => $request->from,
            'to_date' => $request->to,
            'reason' => $request->reason
        ]);

        return response()->json([
            'message' => 'Leave applied successfully'
        ]);
    }
    public function approve(Request $request, $id)
    {
        // Only admin / hr / superadmin
        if (!in_array(Auth::user()->role->name, ['admin', 'hr', 'superadmin'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $leave = Leave::findOrFail($id);

        // Prevent re-approval
        if ($leave->status !== 'pending') {
            return response()->json([
                'message' => 'Leave already processed'
            ], 400);
        }

        $leave->update([
            'status' => $request->status,
            'approved_by' => Auth::id()
        ]);

        return response()->json([
            'message' => 'Leave ' . $request->status . ' successfully'
        ]);
    }
}
