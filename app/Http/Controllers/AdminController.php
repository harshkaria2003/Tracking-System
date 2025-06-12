<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;
use App\Models\Project;
use Carbon\Carbon;
use App\Models\TimeLog;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;


class AdminController extends Controller
{
    // Show the admin dashboard or form with countries and projects dropdown
   
    // Handle admin form submission
    public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'organization_name' => 'required|string|max:255',
            'project_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'country_id' => 'required|exists:countries,id',  // validate country_id
            'role_id' => 'nullable|integer',                 // optional role_id
        ]);

        // Create user with validated data
        User::create([
            'name' => $request->name,
            'organization_name' => $request->organization_name,
            'project_name' => $request->project_name,
            'email' => $request->email,
            'country_id' => $request->country_id ?? null,  
            'role_id' => $request->role_id ?? 2,            
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Admin added successfully!');
    }

public function report(Request $request)
{
    $employees = User::where('role_id', 3)->get(); // Fetch all employees
    $selectedEmployee = null;
    $timeLogs = collect();

    if ($request->has('employee_id') && $request->employee_id != '') {
        // Find the user by their ID
        $selectedEmployee = User::find($request->employee_id);

        if ($selectedEmployee) {
            // Now use the employee's internal employee_id field to fetch logs
            $timeLogs = TimeLog::where('employee_id', $selectedEmployee->employee_id)
                ->orderBy('start_time', 'desc')
                ->paginate(5);
        }
    }

    return view('admin.view_all_records', compact('employees', 'selectedEmployee', 'timeLogs'));
}

public function employeeHoursReport(Request $request)
{
    $employees = collect(); // default empty
    $projects = Project::all();

    if ($request->filled('date_range')) {
        $rawRange = str_replace(' to ', ',', $request->date_range);
        $dates = explode(',', $rawRange);

        try {
            $start = Carbon::parse(trim($dates[0]))->startOfDay();
            $end = isset($dates[1])
                ? Carbon::parse(trim($dates[1]))->endOfDay()
                : $start->copy()->endOfDay();

            $projectId = $request->project_id;

            // Fetch employees with time logs in date range (and optionally by project)
            $employees = Employee::whereHas('timeLogs', function ($query) use ($start, $end, $projectId) {
                    $query->whereBetween('start_time', [$start, $end]);
                    if ($projectId) {
                        $query->where('project_id', $projectId);
                    }
                })
                ->with([
                    'timeLogs' => function ($query) use ($start, $end, $projectId) {
                        $query->whereBetween('start_time', [$start, $end])
                              ->when($projectId, fn($q) => $q->where('project_id', $projectId))
                              ->with('project');
                    }
                ])
                ->get()
                ->map(function ($employee) {
                    $totalSeconds = $employee->timeLogs->reduce(function ($carry, $log) {
                        if ($log->start_time && $log->end_time) {
                            $start = Carbon::parse($log->start_time);
                            $end = Carbon::parse($log->end_time);
                            return $carry + $start->diffInSeconds($end);
                        }
                        return $carry;
                    }, 0);

                    // Add calculated field directly to the model
                    $employee->total_time_seconds = $totalSeconds;
                    return $employee;
                });

          
            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'date_range' => $request->date_range,
                    'project_id' => $projectId,
                    'result_count' => $employees->count(),
                ])
                ->log('Viewed employee hours report');

        } catch (\Exception $e) {
            return back()->withErrors(['date_range' => 'Invalid date range format.']);
        }
    }

    return view('admin.employee_report', compact('employees', 'projects'));
}




}
