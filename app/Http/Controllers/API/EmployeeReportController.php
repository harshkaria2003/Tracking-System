<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EmployeeReportController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            // Check if the logged-in user is an employee (role_id = 3)
            if (!$user || $user->role_id !== 3) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // If employees are users directly (not a separate employee model)
            $logs = $user->timeLogs()->with('project')->get();
            $totalSeconds = 0;

            $data = $logs->map(function ($log) use (&$totalSeconds) {
                $duration = 0;

                if ($log->start_time && $log->end_time) {
                    $start = Carbon::parse($log->start_time);
                    $end = Carbon::parse($log->end_time);
                    $duration = $start->diffInSeconds($end);
                    $totalSeconds += $duration;
                }

                return [
                    'project' => optional($log->project)->name ?: 'N/A',
                    'task_description' => $log->task_description,
                    'start_time' => Carbon::parse($log->start_time)->toDateTimeString(),
                    'end_time' => $log->end_time ? Carbon::parse($log->end_time)->toDateTimeString() : null,
                    'duration_seconds' => $duration,
                ];
            });

            return response()->json([
                'employee' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'total_time_seconds' => $totalSeconds,
                    'time_logs' => $data,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Employee Report Error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error.'], 500);
        }
    }
}
