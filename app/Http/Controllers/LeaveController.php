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
}
