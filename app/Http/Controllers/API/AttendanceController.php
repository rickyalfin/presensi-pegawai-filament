<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function getAttendanceToday()
    {
        $userId       = auth()->user()->id;
        $today        = now()->toDateString();
        $currentMonth = now()->month;

        $attendanceToday = Attendance::select('start_time', 'end_time')
            ->where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->first();

        $attendanceThisMonth = Attendance::select('start_time', 'end_time', 'created_at')
            ->where('user_id', $userId)
            ->whereMonth('created_at', $currentMonth)
            ->get()
            ->map(function ($attendance) {
                return [
                    'start_time' => $attendance->start_time,
                    'end_time'   => $attendance->end_time,
                    'date'       => $attendance->created_at->toDateString(),
                ];
            });

        return response()->json([
            'success' => true,
            'data'    => [
                'today'      => $attendanceToday,
                'this_month' => $attendanceThisMonth,
            ],
            'message' => 'Success get attendance today',
        ]);
    }

    public function getSchedule()
    {
        $schedule = Schedule::with(['office', 'shift'])
            ->where('user_id', auth()->user()->id)
            ->first();
        return response()->json([
            'success' => true,
            'data'    => $schedule,
            'message' => 'Success get schedule',
        ]);
    }
}
