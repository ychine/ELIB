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
        $courses = \App\Models\Course::all();

        return view('register', compact('campuses', 'courses'));
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
            // Regenerate session to prevent session fixation attacks
            // This will automatically handle CSRF token regeneration
            $request->session()->regenerate();
            $user = Auth::user();

            // BANNED CHECK - Must be first check before any other operations
            if ($user->is_banned) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/signin')->with('error', "You're banned. Please contact support if you think this is a mistake.");
            }

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
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',      // at least one lowercase letter
                'regex:/[A-Z]/',      // at least one uppercase letter
                'regex:/[0-9]/',      // at least one digit
                'regex:/[-_@$!%*#?&]/', // at least one special character (-_@$!%*#?&)
            ],
            'role' => ['required', 'in:student,faculty,librarian,admin'],
            'campus_id' => ['required', 'exists:campus,Campus_ID'],
        ];

        // Add course and student_number validation for students
        if ($request->role === 'student') {
            $rules['course_id'] = ['required', 'exists:courses,id'];
            $rules['student_number'] = ['required', 'string', 'max:20', 'regex:/^23-\d{3,}$/'];
        }

        $messages = [
            'password.min' => 'The password must be at least 8 characters.',
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (-_@$!%*#?&).',
            'password.confirmed' => 'The password confirmation does not match.',
        ];

        $request->validate($rules, $messages);

        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store registration data in session (NOT in database yet)
        $registrationData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password, // Will be hashed later
            'role' => $request->role,
            'campus_id' => $request->campus_id,
            'code' => $code,
            'code_expires_at' => now()->addMinutes(15),
        ];

        // Add course and student_number for students
        if ($request->role === 'student') {
            $registrationData['course_id'] = $request->course_id;
            $registrationData['student_number'] = $request->student_number;
        }

        $request->session()->put('registration_data', $registrationData);

        // Send verification email - don't block registration if it fails
        try {
            // If mail driver is 'log', just log it and continue
            if (config('mail.default') === 'log') {
                \Log::info("Verification code for {$request->email}: {$code}");
            } else {
                // Try to send email, but don't fail registration if it errors
                try {
                    Mail::to($request->email)->send(new VerificationCodeMail($code));
                    \Log::info("Verification email sent to {$request->email} with code: {$code}");
                } catch (\Exception $mailException) {
                    // Log the error but continue with registration
                    \Log::warning("Email sending failed for {$request->email}: ".$mailException->getMessage());
                    \Log::info("Verification code for {$request->email}: {$code} (email failed, code logged)");
                    // Continue - don't block registration
                }
            }
        } catch (\Exception $e) {
            // Catch any other errors and log but continue
            \Log::error('Email error: '.$e->getMessage());
            \Log::info("Verification code for {$request->email}: {$code} (error occurred, code logged)");
            // Don't return error - let user proceed to verification page
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

        // Add course and student_number for students
        if ($user->role === 'student') {
            $profileData['course_id'] = $data['course_id'] ?? null;
            $profileData['student_number'] = $data['student_number'] ?? null;
        }

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

        // Get audit logs for graphs - fill in missing days with 0
        $loginData = \App\Models\AuditLog::where('action', 'login')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Get Community Uploads and Featured counts for last 30 days (separate lines like Analytics)
        $communityUploadsData = Resource::where('Type', 'Community Uploads')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $featuredData = Resource::where('Type', 'Featured')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Generate array for last 30 days with data or 0
        $recentLogins = collect(range(0, 29))->map(function ($daysAgo) use ($loginData) {
            $date = now()->subDays($daysAgo)->format('Y-m-d');
            $logEntry = $loginData->get($date);

            return [
                'date' => $date,
                'count' => $logEntry ? (int) $logEntry->count : 0,
            ];
        })->reverse()->values();

        $communityUploadsChart = collect(range(0, 29))->map(function ($daysAgo) use ($communityUploadsData) {
            $date = now()->subDays($daysAgo)->format('Y-m-d');
            $logEntry = $communityUploadsData->get($date);

            return [
                'date' => $date,
                'count' => $logEntry ? (int) $logEntry->count : 0,
            ];
        })->reverse()->values();

        $featuredChart = collect(range(0, 29))->map(function ($daysAgo) use ($featuredData) {
            $date = now()->subDays($daysAgo)->format('Y-m-d');
            $logEntry = $featuredData->get($date);

            return [
                'date' => $date,
                'count' => $logEntry ? (int) $logEntry->count : 0,
            ];
        })->reverse()->values();

        $stats = [
            'totalUsers' => User::where('is_approved', true)->count(),
            'pendingApprovals' => User::where('is_approved', false)->count(),
            'totalResources' => Resource::count(),
            'totalBorrows' => Borrower::count(),
            'activeBorrows' => Borrower::where('isReturned', false)->count(),
            'onlineUsers' => User::where('is_online', true)->count(),
            'recentLogins' => $recentLogins,
            'communityUploadsChart' => $communityUploadsChart,
            'featuredChart' => $featuredChart,
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

        // Get Community Uploads and Featured counts for last 30 days (line graph)
        $communityUploadsData = Resource::where('Type', 'Community Uploads')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $featuredData = Resource::where('Type', 'Featured')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Generate array for last 30 days with data or 0
        $communityUploadsChart = collect(range(0, 29))->map(function ($daysAgo) use ($communityUploadsData) {
            $date = now()->subDays($daysAgo)->format('Y-m-d');
            $logEntry = $communityUploadsData->get($date);

            return [
                'date' => $date,
                'count' => $logEntry ? (int) $logEntry->count : 0,
            ];
        })->reverse()->values();

        $featuredChart = collect(range(0, 29))->map(function ($daysAgo) use ($featuredData) {
            $date = now()->subDays($daysAgo)->format('Y-m-d');
            $logEntry = $featuredData->get($date);

            return [
                'date' => $date,
                'count' => $logEntry ? (int) $logEntry->count : 0,
            ];
        })->reverse()->values();

        // Get reports
        $reports = \App\Models\ResourceReport::with([
            'resource',
            'reporter.admin',
            'reporter.librarian',
            'reporter.student',
            'reporter.faculty',
            'resource.owner',
        ])
            ->latest()
            ->get()
            ->map(function ($report) {
                $reporter = $report->reporter;
                $reporterName = 'Unknown';

                if ($reporter) {
                    // Get name from role-specific relationship
                    $profile = match ($reporter->role) {
                        'admin' => $reporter->admin,
                        'librarian' => $reporter->librarian,
                        'student' => $reporter->student,
                        'faculty' => $reporter->faculty,
                        default => null,
                    };

                    $reporterName = $profile
                        ? trim(($profile->First_Name ?? '').' '.($profile->Last_Name ?? ''))
                        : 'Unknown';
                }

                return [
                    'id' => $report->id,
                    'resource_id' => $report->resource_id,
                    'resource_name' => $report->resource->Resource_Name ?? 'Unknown',
                    'reporter_name' => $reporterName,
                    'reporter_email' => $reporter->email ?? 'N/A',
                    'reason' => $report->reason,
                    'description' => $report->description,
                    'status' => $report->status,
                    'flagged_by_librarian' => $report->flagged_by_librarian ?? false,
                    'created_at' => $report->created_at,
                ];
            })
            ->values();

        // Get flagged accounts (banned users)
        $flaggedAccounts = \App\Models\User::where('is_banned', true)
            ->with(['admin', 'librarian', 'student', 'faculty'])
            ->get()
            ->map(function ($user) {
                $profile = match ($user->role) {
                    'admin' => $user->admin,
                    'librarian' => $user->librarian,
                    'student' => $user->student,
                    'faculty' => $user->faculty,
                    default => null,
                };

                // Get the ban date from audit log if available
                $banLog = \App\Models\AuditLog::where('user_id', $user->id)
                    ->where('action', 'admin_ban_user')
                    ->latest()
                    ->first();

                return [
                    'id' => $user->id,
                    'name' => $profile ? trim(($profile->First_Name ?? '').' '.($profile->Last_Name ?? '')) : 'Unknown',
                    'email' => $user->email,
                    'role' => $user->role,
                    'flag_reason' => 'Banned by Admin',
                    'flagged_at' => $banLog ? $banLog->created_at : $user->updated_at,
                ];
            })
            ->values();

        return Inertia::render('Admin/Analytics', [
            'summary' => $summary,
            'resourcesByType' => $resourcesByType,
            'topViewed' => $topViewed,
            'recentUploads' => $recentUploads,
            'reports' => $reports,
            'flaggedAccounts' => $flaggedAccounts,
            'communityUploadsChart' => $communityUploadsChart,
            'featuredChart' => $featuredChart,
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

    // ────────────────────── REPORT ACTIONS ──────────────────────
    public function deleteResourceFromReport(Request $request, $report)
    {
        $report = \App\Models\ResourceReport::findOrFail($report);
        $resource = \App\Models\Resource::findOrFail($request->resource_id);

        // Delete the resource
        $resource->authors()->detach();
        $resource->tags()->detach();

        // Delete files
        if ($resource->File_Path && \Illuminate\Support\Facades\Storage::disk('public')->exists($resource->File_Path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($resource->File_Path);
        }
        if ($resource->thumbnail_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($resource->thumbnail_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($resource->thumbnail_path);
        }

        $resource->delete();

        // Update report status
        $report->update([
            'status' => 'resolved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => 'Resource deleted by admin',
        ]);

        // Log audit
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'admin_delete_resource',
            'model_type' => \App\Models\Resource::class,
            'model_id' => $resource->Resource_ID,
            'description' => "Deleted resource from report: {$resource->Resource_Name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Resource deleted successfully.');
    }

    public function banUserFromReport(Request $request, $report)
    {
        $report = \App\Models\ResourceReport::findOrFail($report);
        $resource = \App\Models\Resource::findOrFail($request->resource_id);

        if (! $resource->owner_id) {
            return back()->with('error', 'Resource has no owner to ban.');
        }

        $user = \App\Models\User::findOrFail($resource->owner_id);

        // Ban the user
        $user->update(['is_banned' => true]);

        // Update report status
        $report->update([
            'status' => 'resolved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => 'User banned by admin',
        ]);

        // Log audit
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'admin_ban_user',
            'model_type' => \App\Models\User::class,
            'model_id' => $user->id,
            'description' => "Banned user: {$user->email}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'User banned successfully.');
    }

    public function markFalseAlarm(Request $request, $report)
    {
        $report = \App\Models\ResourceReport::with('resource')->findOrFail($report);

        // Update report status
        $report->update([
            'status' => 'resolved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => 'Marked as false alarm by admin',
        ]);

        // Log audit
        $resourceName = $report->resource->Resource_Name ?? 'Unknown';
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'admin_mark_false_alarm',
            'model_type' => \App\Models\ResourceReport::class,
            'model_id' => $report->id,
            'description' => "Marked report as false alarm for resource: {$resourceName}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Report marked as false alarm.');
    }
}
