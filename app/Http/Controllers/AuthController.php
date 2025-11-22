<?php

namespace App\Http\Controllers;

use App\Mail\VerificationCodeMail;
use App\Models\Admin;
use App\Models\AuditLog;
use App\Models\Borrower;
use App\Models\Campus;
use App\Models\Faculty;
use App\Models\Librarian;
use App\Models\Resource;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

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

            // Update last login and online status
            $user->update([
                'last_login_at' => now(),
                'is_online' => true,
            ]);

            // Log login
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'login',
                'description' => 'User logged in',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // EMAIL VERIFICATION CHECK (skip for admin)
            if ($user->role !== 'admin' && ! $user->hasVerifiedEmail()) {
                Auth::logout();

                return redirect()->route('login')->withErrors(['email' => 'Please verify your email first.']);
            }

            // APPROVAL CHECK (faculty, librarian, student)
            if (in_array($user->role, ['faculty', 'librarian', 'student'])) {
                if (! $user->is_approved) {
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
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:student,faculty,librarian,admin'],
            'campus_id' => ['required', 'exists:campus,Campus_ID'],
        ]);

        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store registration data in session (NOT in database yet)
        $request->session()->put('registration_data', [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password, // Will be hashed later
            'role' => $request->role,
            'campus_id' => $request->campus_id,
            'code' => $code,
            'code_expires_at' => now()->addMinutes(15),
        ]);

        // Send verification email
        try {
            Mail::to($request->email)->send(new VerificationCodeMail($code));
            \Log::info("Verification email sent to {$request->email} with code: {$code}");
        } catch (\Exception $e) {
            \Log::error('Failed to send email: '.$e->getMessage());

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
            'UID' => $user->id,
            'First_Name' => $request->first_name,
            'Last_Name' => $request->last_name,
        ];

        match ($user->role) {
            'student' => Student::create($data),
            'faculty' => Faculty::create($data),
            'admin' => Admin::create($data),
            'librarian' => Librarian::create($data),
            default => null,
        };
    }

    /* --------------------------------------------------------------
       SHOW THE 6-INPUT VERIFICATION PAGE
       -------------------------------------------------------------- */
    public function showVerifyCode(Request $request)
    {
        $registrationData = $request->session()->get('registration_data');

        if (! $registrationData) {
            return redirect()->route('register')
                ->withErrors(['email' => 'Session expired – please register again.']);
        }

        return view('auth.verify-code', [
            'email' => $registrationData['email'],
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

        if (! $registrationData) {
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
            'email' => $registrationData['email'],
            'password' => Hash::make($registrationData['password']),
            'role' => $registrationData['role'],
            'Campus_ID' => $registrationData['campus_id'],
            'is_approved' => in_array($registrationData['role'], ['student', 'admin']),
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
        if (in_array($user->role, ['faculty', 'librarian']) && ! $user->is_approved) {
            return redirect()->route('login')
                ->with('status', 'pending-approval');
        }

        return redirect()->route('login')
            ->with('status', 'verification-success');
    }

    private function createProfileFromData(User $user, array $data)
    {
        $profileData = [
            'UID' => $user->id,
            'First_Name' => $data['first_name'],
            'Last_Name' => $data['last_name'],
        ];

        match ($user->role) {
            'student' => Student::create($profileData),
            'faculty' => Faculty::create($profileData),
            'admin' => Admin::create($profileData),
            'librarian' => Librarian::create($profileData),
            default => null,
        };
    }

    // ────────────────────── ADMIN APPROVALS ──────────────────────
    public function showPendingApprovals(): Response
    {
        $users = User::where('is_approved', false)
            ->whereIn('role', ['faculty', 'librarian'])
            ->with(['campus', 'faculty', 'librarian'])
            ->get();

        // Get audit logs for graphs
        $recentLogins = \App\Models\AuditLog::where('action', 'login')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn ($log) => [
                'date' => $log->date,
                'count' => $log->count,
            ]);

        $resourceUploads = \App\Models\AuditLog::where('action', 'resource_upload')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn ($log) => [
                'date' => $log->date,
                'count' => $log->count,
            ]);

        $stats = [
            'totalUsers' => User::where('is_approved', true)->count(),
            'pendingApprovals' => User::where('is_approved', false)->count(),
            'totalResources' => Resource::count(),
            'totalBorrows' => Borrower::count(),
            'activeBorrows' => Borrower::where('isReturned', false)->count(),
            'onlineUsers' => User::where('is_online', true)->count(),
            'recentLogins' => $recentLogins,
            'resourceUploads' => $resourceUploads,
        ];

        $pendingUsers = $users->map(fn (User $user) => [
            'id' => $user->id,
            'name' => $this->resolveUserName($user),
            'email' => $user->email,
            'role' => ucfirst($user->role),
            'campus' => $user->campus->Campus_Name ?? null,
            'approveUrl' => route('admin.approve', $user),
            'rejectUrl' => route('admin.reject', $user),
        ])->values();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'pendingUsers' => $pendingUsers,
        ]);
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
    public function auditTrail(): Response
    {
        $auditLogs = \App\Models\AuditLog::with('user')
            ->latest()
            ->paginate(50)
            ->through(function ($log) {
                return [
                    'id' => $log->id,
                    'date' => $log->created_at->format('M d, Y h:i A'),
                    'user' => $log->user ? $log->user->full_name : 'System',
                    'action' => $log->action,
                    'description' => $log->description,
                    'ip_address' => $log->ip_address,
                ];
            });

        return Inertia::render('Admin/Audit', [
            'auditLogs' => $auditLogs,
        ]);
    }

    // ────────────────────── RESOURCE ANALYTICS ──────────────────────
    public function resourceAnalytics(): Response
    {
        $summary = [
            'totalResources' => Resource::count(),
        ];

        $resourcesByType = Resource::selectRaw('Type, count(*) as count')
            ->groupBy('Type')
            ->get()
            ->map(fn ($row) => [
                'type' => $row->Type,
                'count' => $row->count,
            ])
            ->values();

        $topViewed = Resource::orderByDesc('views')
            ->limit(10)
            ->get()
            ->map(fn ($resource) => [
                'id' => $resource->Resource_ID,
                'name' => $resource->Resource_Name,
                'type' => $resource->Type,
                'views' => $resource->views,
            ])
            ->values();

        $recentUploads = Resource::orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(fn ($resource) => [
                'id' => $resource->Resource_ID,
                'name' => $resource->Resource_Name,
                'type' => $resource->Type,
                'uploadedAt' => optional($resource->created_at)?->format('Y-m-d'),
            ])
            ->values();

        return Inertia::render('Admin/Analytics', [
            'summary' => $summary,
            'resourcesByType' => $resourcesByType,
            'topViewed' => $topViewed,
            'recentUploads' => $recentUploads,
        ]);
    }

    private function resolveUserName(User $user): string
    {
        $name = $this->relationName($user->faculty)
            ?? $this->relationName($user->librarian)
            ?? $this->relationName($user->admin);

        return $name ?? 'User';
    }

    private function relationName(?object $relation): ?string
    {
        if (! $relation) {
            return null;
        }

        $first = $relation->First_Name ?? '';
        $last = $relation->Last_Name ?? '';

        return trim(trim($first).' '.trim($last)) ?: null;
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
