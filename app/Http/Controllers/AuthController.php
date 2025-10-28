<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Faculty;
use App\Models\Admin;
use App\Models\Librarian;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('signin');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Check approval for faculty, librarian, and student roles
            if (in_array($user->role, ['faculty', 'librarian', 'student'])) {
                if (!$user->is_approved) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect('/signin')->with('error', 'Your account is pending admin approval.');
                }
            }

            return redirect()->intended($user->role === 'admin' ? '/admin' : '/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:student,faculty,librarian,admin'],
            'campus_id' => ['required', 'exists:campus,Campus_ID'],
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'Campus_ID' => $request->campus_id,
            'is_approved' => in_array($request->role, ['student', 'admin']), // Auto-approve students and admins
        ]);

        if ($request->role === 'student') {
            Student::create([
                'UID' => $user->id,
                'First_Name' => $request->first_name,
                'Last_Name' => $request->last_name,
            ]);
        } elseif ($request->role === 'faculty') {
            Faculty::create([
                'UID' => $user->id,
                'First_Name' => $request->first_name,
                'Last_Name' => $request->last_name,
            ]);
        } elseif ($request->role === 'admin') {
            Admin::create([
                'UID' => $user->id,
                'First_Name' => $request->first_name,
                'Last_Name' => $request->last_name,
            ]);
        } elseif ($request->role === 'librarian') {
            Librarian::create([
                'UID' => $user->id,
                'First_Name' => $request->first_name,
                'Last_Name' => $request->last_name,
            ]);
        }

        if ($user->is_approved) {
            Auth::login($user);
            return redirect()->route('home');
        }

        return redirect('/signin')->with('status', 'Registration successful! Faculty and librarian accounts require admin approval.');
    }

    public function showPendingApprovals()
    {
        $users = User::where('is_approved', false)
                     ->whereIn('role', ['faculty', 'librarian'])
                     ->with(['campus', 'faculty', 'librarian'])
                     ->get();
        return view('homeAdmin', compact('users'));
    }

    public function approveUser(Request $request, User $user)
    {
        $user->update(['is_approved' => true]);
        return redirect()->route('admin.approvals')->with('status', 'User approved successfully.');
    }

    public function rejectUser(Request $request, User $user)
    {
        $user->delete();
        return redirect()->route('admin.approvals')->with('status', 'User rejected and deleted.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/signin');
    }
}