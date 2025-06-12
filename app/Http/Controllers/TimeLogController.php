<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeLog;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DateTimeZone;

class TimeLogController extends Controller
{
    // Show task form with project dropdown
    public function showTaskForm()
    {
        $projects = Project::all();
        return view('dashboard.employee', compact('projects'));
    }

    // Start the timer
    public function start(Request $request)
    {
        try {
            Log::info('Start method called.');
            Log::info('Request data: ' . json_encode($request->all()));
            Log::info('Authenticated user ID (from users table): ' . Auth::id());

            $request->validate([
                'task_description' => 'required|string|max:255',
                'project_id' => 'required|exists:projects,id',
            ]);

            $user = Auth::user();

            if (!$user || !$user->employee_id) {
                Log::error('User missing employee ID. Auth ID: ' . Auth::id());
                return response()->json([
                    'status' => 'error',
                    'message' => 'Authenticated user does not have an associated employee ID.'
                ], 403);
            }

            // Use Asia/Kolkata timezone
            $indianTime = Carbon::now(new DateTimeZone('Asia/Kolkata'));

            $log = TimeLog::create([
                'employee_id' => $user->employee_id,
                'project_id' => $request->project_id,
                'task_description' => $request->task_description,
                'start_time' => $indianTime,
            ]);

            return response()->json([
                'status' => 'started',
                'log_id' => $log->id,
                'start_time' => $log->start_time->toDateTimeString(),
            ]);
        } catch (\Exception $e) {
            Log::error('Timer start error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Server error while starting timer.'
            ], 500);
        }
    }

    // Stop the timer
   public function stop(Request $request)
{
    try {
        $request->validate([
            'log_id' => 'required|exists:time_logs,id',
        ]);

        $user = Auth::user();

        if (!$user || !$user->employee_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Authenticated user does not have an associated employee ID.'
            ], 403);
        }

        $log = TimeLog::where('id', $request->log_id)
            ->where('employee_id', $user->employee_id)
            ->firstOrFail();

        $endTimeIST = Carbon::now(new DateTimeZone('Asia/Kolkata'));
        $startTimeIST = Carbon::parse($log->start_time, new DateTimeZone('Asia/Kolkata'));

        $totalSeconds = $startTimeIST->diffInSeconds($endTimeIST);
        $formattedTime = $this->secondsToTime($totalSeconds); 

        $log->end_time = $endTimeIST;
        $log->total_time_seconds = $totalSeconds; 
        $log->save();

        return response()->json([
            'status' => 'stopped',
            'end_time' => $endTimeIST->toDateTimeString(),
            'total_time_formatted' => $formattedTime,
        ]);
    } catch (\Exception $e) {
        Log::error('Timer stop error: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Server error while stopping timer.'
        ], 500);
    }
}

 
    private function secondsToTime(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

public function report()
{
    $user = auth()->user();

    if ($user->role_id != 3) {
        abort(403, 'Unauthorized');
    }

   
    $logs = TimeLog::where('employee_id', $user->employee_id)
                   ->orderBy('start_time', 'desc')
                   ->paginate(5);

    return view('timelog.report', ['logs' => $logs]);
}




}
