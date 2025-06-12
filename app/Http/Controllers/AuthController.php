<?php

namespace App\Http\Controllers;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Country;




class AuthController extends Controller
{
    // Show login form
    public function loginForm()
    {
        return view('auth.login');
    }

    // Handle login request
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Enter a valid email address.',
            'password.required' => 'Password is required.',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        
        $defaultRoles = [
            'superadmin@example.com' => 1,
            'admin@example.com'     => 2,
            'employee@example.com'  => 3,
        ];

        if (!$user && array_key_exists($credentials['email'], $defaultRoles)) {
            $user = User::create([
                'name' => ucfirst(explode('@', $credentials['email'])[0]),
                'email' => $credentials['email'],
                'password' => Hash::make($credentials['password']),
                'role_id' => $defaultRoles[$credentials['email']],
            ]);

            activity()
                ->causedBy($user)
                ->log('User auto-created during login');
        }

        if (!$user) {
            return back()->withErrors(['email' => 'Unauthorized email address.']);
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

          
            activity()
                ->causedBy(Auth::user())
                ->log('User logged in');

            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function dashboard()
    {
        $user = Auth::user();

        if (!$user || !$user->role_id) {
            abort(403, 'Unauthorized role.');
        }

        return match ($user->role_id) {
            1 => redirect()->route('superadmin.dashboard'),
            2 => redirect()->route('admin.dashboard'),
            3 => redirect()->route('employee.dashboard'),
            default => abort(403, 'Unauthorized role.'),
        };
    }

    public function superAdminDashboard()
    {
        return view('dashboard.superadmin');
    }

    public function adminDashboard()
    {
        $countries = Country::all();
        return view('dashboard.admin', compact('countries'));
    }

    public function employeeDashboard()
    {
        return view('dashboard.employee');
    }

    // Logout user
    public function logout(Request $request)
    {
      
        activity()
            ->causedBy(Auth::user())
            ->log('User logged out');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
