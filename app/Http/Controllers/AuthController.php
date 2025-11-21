<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Faculty;
use App\Models\Admin;
use App\Models\Librarian;
use App\Models\Student;
use App\Models\VerifyCode;
use App\Models\Campus;
use App\Models\Resource;
use App\Models\Borrower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class AuthController extends Controller
{
    // ────────────────────── SHOW FORMS ──────────────────────
    public function showLoginForm(Request $request)
    {
        // Ensure session is started to generate CSRF token
        $request->session()->start();
        return view('signin');
    }

    public function showRegisterForm()
    {
        $campuses = Campus::all();
        return view('register', compact('campuses'));
    }

    // ────────────────────── LOGIN ──────────────────────
    public function login(Request $request)
    {
        // Ensure session is started
        $request->session()->start();
        
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            // Don't regenerate CSRF token here - it causes 419 errors
            $user = Auth::user();

            // EMAIL VERIFICATION CHECK (skip for admin)
            if ($user->role !== 'admin' && !$user->hasVerifiedEmail()) {
                Auth::logout();
                return redirect()->route('login')->withErrors(['email' => 'Please verify your email first.']);
            }

            // APPROVAL CHECK (faculty, librarian, student)
            if (in_array($user->role, ['faculty', 'librarian', 'student'])) {
                if (!$user->is_approved) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect('/signin')->with('error', 'Your account is pending admin approval.');
                }
            }

            // Redirect based on role - use direct routes to avoid redirect chain issues
            if ($user->role === 'admin') {
                return redirect()->route('admin.approvals');
            } elseif ($user->role === 'librarian') {
                return redirect()->route('home.librarian');
            } else {
                // For students and faculty, go directly to home.user
                return redirect()->route('home.user');
            }
        }

        return redirect()->route('login')->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /* --------------------------------------------------------------
       REGISTER – create user, profile, send 6-digit code
       -------------------------------------------------------------- */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
            'role'       => ['required', 'in:student,faculty,librarian,admin'],
            'campus_id'  => ['required', 'exists:campus,Campus_ID'],
        ]);

        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store registration data in session (NOT in database yet)
        $request->session()->put('registration_data', [
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => $request->password, // Will be hashed later
            'role'       => $request->role,
            'campus_id'  => $request->campus_id,
            'code'       => $code,
            'code_expires_at' => now()->addMinutes(15),
        ]);

        // Send verification email
        try {
            Mail::to($request->email)->send(new VerificationCodeMail($code));
            \Log::info("Verification email sent to {$request->email} with code: {$code}");
        } catch (\Exception $e) {
            \Log::error("Failed to send email: " . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send verification email. Please try again.']);
        }

        return redirect()->route('verify.code');
    }

    /* --------------------------------------------------------------
       CREATE PROFILE (Student / Faculty / Admin / Librarian)
       -------------------------------------------------------------- */
    private function createProfile(User $user, Request $request)
    {
        $data = [
            'UID'        => $user->id,
            'First_Name' => $request->first_name,
            'Last_Name'  => $request->last_name,
        ];

        match ($user->role) {
            'student'   => Student::create($data),
            'faculty'   => Faculty::create($data),
            'admin'     => Admin::create($data),
            'librarian' => Librarian::create($data),
            default     => null,
        };
    }

    /* --------------------------------------------------------------
       SHOW THE 6-INPUT VERIFICATION PAGE
       -------------------------------------------------------------- */
    public function showVerifyCode(Request $request)
    {
        $registrationData = $request->session()->get('registration_data');

        if (!$registrationData) {
            return redirect()->route('register')
                ->withErrors(['email' => 'Session expired – please register again.']);
        }

        return view('auth.verify-code', [
            'email' => $registrationData['email']
        ]);
    }

    /* --------------------------------------------------------------
       VALIDATE THE CODE
       -------------------------------------------------------------- */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $registrationData = $request->session()->get('registration_data');

        if (!$registrationData) {
            return redirect()->route('register')
                ->withErrors(['email' => 'Session expired – please register again.']);
        }

        // Check if code matches
        if ($request->code !== $registrationData['code']) {
            return back()->withErrors(['code' => 'Invalid code.']);
        }

        // Check if code expired
        if (now()->greaterThan($registrationData['code_expires_at'])) {
            $request->session()->forget('registration_data');
            return redirect()->route('register')
                ->withErrors(['email' => 'Code has expired. Please register again.']);
        }

        // Code is valid! Now create the user
        $user = User::create([
            'email'            => $registrationData['email'],
            'password'         => Hash::make($registrationData['password']),
            'role'             => $registrationData['role'],
            'Campus_ID'        => $registrationData['campus_id'],
            'is_approved'      => in_array($registrationData['role'], ['student', 'admin']),
            'email_verified_at' => now(), // Set timestamp
        ]);

        // Double-check: Explicitly save the email_verified_at field
        $user->email_verified_at = now();
        $user->save();

        // Create the role-specific profile
        $this->createProfileFromData($user, $registrationData);

        // Clean up session
        $request->session()->forget('registration_data');

        // Check if user needs approval
        if (in_array($user->role, ['faculty', 'librarian']) && !$user->is_approved) {
            return redirect()->route('login')
                ->with('status', 'pending-approval');
        }

        return redirect()->route('login')
            ->with('status', 'verification-success');
    }

    private function createProfileFromData(User $user, array $data)
    {
        $profileData = [
            'UID'        => $user->id,
            'First_Name' => $data['first_name'],
            'Last_Name'  => $data['last_name'],
        ];

        match ($user->role) {
            'student'   => Student::create($profileData),
            'faculty'   => Faculty::create($profileData),
            'admin'     => Admin::create($profileData),
            'librarian' => Librarian::create($profileData),
            default     => null,
        };
    }

    // ────────────────────── ADMIN APPROVALS ──────────────────────
    public function showPendingApprovals()
    {
        $users = User::where('is_approved', false)
                     ->whereIn('role', ['faculty', 'librarian'])
                     ->with(['campus', 'faculty', 'librarian'])
                     ->get();
        
        // Dashboard stats
        $totalUsers = User::where('is_approved', true)->count();
        $pendingApprovals = User::where('is_approved', false)->count();
        $totalResources = \App\Models\Resource::count();
        $totalBorrows = \App\Models\Borrower::count();
        $activeBorrows = \App\Models\Borrower::where('isReturned', false)->count();
        
        return view('homeAdmin', compact('users', 'totalUsers', 'pendingApprovals', 'totalResources', 'totalBorrows', 'activeBorrows'));
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

    // ────────────────────── AUDIT TRAIL ──────────────────────
    public function auditTrail()
    {
        // Get audit trail data (user actions, approvals, etc.)
        $auditLogs = [];
        // TODO: Implement actual audit trail logging system
        return view('admin.audit', compact('auditLogs'));
    }

    // ────────────────────── RESOURCE ANALYTICS ──────────────────────
    public function resourceAnalytics()
    {
        $totalResources = Resource::count();
        $resourcesByType = Resource::selectRaw('Type, count(*) as count')
            ->groupBy('Type')
            ->get();
        $topViewed = Resource::orderBy('views', 'desc')->limit(10)->get();
        $recentUploads = Resource::orderBy('created_at', 'desc')->limit(10)->get();
        
        return view('admin.analytics', compact('totalResources', 'resourcesByType', 'topViewed', 'recentUploads'));
    }

    // ────────────────────── LOGOUT ──────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/signin');
    }
}