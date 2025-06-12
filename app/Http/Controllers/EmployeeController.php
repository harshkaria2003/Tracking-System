<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class EmployeeController extends Controller
{
   public function index()
{
    $countries = Country::all();
    $projects = Project::all();
    $employees = User::where('role_id', 3)->get(); 

    return view('dashboard.admin', compact('countries', 'projects', 'employees'));
}


    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:employees,email',
                'password' => 'required|string|min:6|confirmed', // Added confirmation
                'project_id' => 'required|exists:projects,id',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'country_id' => 'required|exists:countries,id',
            ]);

            // Use database transaction to ensure atomicity
            DB::beginTransaction();

            $employee = Employee::create([
                'name' => $request->name,
                'email' => $request->email,
                    
                'project_id' => $request->project_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'country_id' => $request->country_id,
            ]);

            // Create a corresponding user record and assign the 'employee' role
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'employee_id' => $employee->id, // Link employee ID to the user
                'role_id' => 3,
            ]);

            $employeeRole = Role::where('name', 'employee')->firstOrFail();
            $user->roles()->attach($employeeRole);

            DB::commit(); // Commit the transaction

            return redirect()->back()->with('success', 'Employee added successfully!');

        } catch (ValidationException $e) {
            DB::rollBack(); // Rollback on validation failure
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            DB::rollBack(); 
           
        }
    }
}