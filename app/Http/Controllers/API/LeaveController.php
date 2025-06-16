<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    public function index()
    {
        try {
            $leaves = Leave::with('user')->orderBy('id', 'desc')->get();
            return response()->json([
                'success' => true,
                'data'    => $leaves,
                'message' => 'Success retrieving data',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving data',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                /**
                 * Start Date
                 *
                 * @example 2025-06-16
                 */
                'start_date' => 'required|date',
                /**
                 * End Date
                 *
                 * @example 2025-06-18
                 */
                'end_date'   => 'required|date|after_or_equal:start_date',
                /**
                 * Reason
                 *
                 * @example Keperluan keluarga
                 */
                'reason'     => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'data'    => $validator->errors(),
                ], 422);
            }

            $leave = Leave::create([
                'user_id'    => Auth::id(),
                'start_date' => $request->start_date,
                'end_date'   => $request->end_date,
                'reason'     => $request->reason,
                'status'     => 'pending',
            ]);

            $leave->load('user');
            return response()->json([
                'success' => true,
                'message' => 'Success creating leave request',
                'data'    => $leave,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => 'Error creating leave request',
            ], 500);
        }
    }
}
