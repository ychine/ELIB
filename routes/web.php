<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ShelfController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth'])->group(function () {
    Route::get('/borrow/show/{id}', [App\Http\Controllers\BorrowController::class, 'showBorrow'])
        ->name('borrow.show');
    Route::get('/yourshelf', [ShelfController::class, 'index'])->name('yourshelf');
    Route::post('/return/{id}', [ShelfController::class, 'returnBook'])->name('return.book');
    Route::get('/view-book/{id}', [ShelfController::class, 'viewBook'])
        ->name('view.book');
    Route::get('/viewer/{id}', function ($id) {
        return view('pdf-viewer', ['id' => $id, 'user' => auth()->user()]);
    })->name('viewer');
});

Route::get('/signin', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/signin', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/verify-code', [AuthController::class, 'showVerifyCode'])
    ->name('verify.code');
Route::post('/verify-code', [AuthController::class, 'verifyCode'])
    ->name('verify.code.post');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password/send-code', [AuthController::class, 'sendResetCode'])->name('password.email');
Route::post('/forgot-password/reset', [AuthController::class, 'resetPasswordWithCode'])->name('password.reset.code');

Route::post('/logout', function (Request $request) {
    $user = Auth::user();
    if ($user) {
        // Update online status
        $user->update(['is_online' => false]);

        // Log logout
        \App\Models\AuditLog::create([
            'user_id' => $user->id,
            'action' => 'logout',
            'description' => 'User logged out',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Handle Inertia requests
    if ($request->header('X-Inertia')) {
        return Inertia::location('/signin');
    }

    return redirect('/signin')->with('status', 'Logged out');
})->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/email/verify', [Illuminate\Foundation\Auth\EmailVerificationPromptController::class, '__invoke'])
        ->name('verification.notice');

    Route::post('/email/verification-notification', [Illuminate\Foundation\Auth\EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('/email/verify/{id}/{hash}', [Illuminate\Foundation\Auth\VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    /* ROOT REDIRECT */
    Route::get('/', function () {
        $user = Auth::user();
        $role = $user->role ?? null;

        if ($role === 'admin') {
            return redirect()->route('admin.approvals');
        }

        if (in_array($role, ['faculty', 'student', 'librarian'])) {
            if (! $user->is_approved) {
                Auth::logout();

                return redirect('/signin')->with('error', 'Your account is pending admin approval.');
            }

            return redirect()->route($role === 'librarian' ? 'home.librarian' : 'home.user');
        }

        Auth::logout();

        return redirect('/signin');
    })->name('home');

    /* ADMIN ROUTES */
    Route::middleware(['role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/', [AuthController::class, 'showPendingApprovals'])->name('approvals');

            Route::get('/users', [UserManagementController::class, 'index'])->name('users');
            Route::get('/users/{id}/edit', [UserManagementController::class, 'edit']);
            Route::patch('/users/{id}', [UserManagementController::class, 'update']);
            Route::delete('/users/{id}', [UserManagementController::class, 'destroy']);

            Route::post('/approve/{user}', [AuthController::class, 'approveUser'])->name('approve');
            Route::delete('/reject/{user}', [AuthController::class, 'rejectUser'])->name('reject');

            // Routes for positions management
            Route::post('/positions', [PositionController::class, 'store'])->name('positions.store');
            Route::get('/positions/{id}/edit', [PositionController::class, 'edit']);
            Route::patch('/positions/{id}', [PositionController::class, 'update']);
            Route::delete('/positions/{id}', [PositionController::class, 'destroy']);

            Route::get('/audit', [AuthController::class, 'auditTrail'])->name('audit');
            Route::get('/analytics', [AuthController::class, 'resourceAnalytics'])->name('analytics');
            Route::post('/reports/{report}/delete-resource', [AuthController::class, 'deleteResourceFromReport'])->name('admin.reports.delete.resource');
            Route::post('/reports/{report}/ban-user', [AuthController::class, 'banUserFromReport'])->name('admin.reports.ban.user');
            Route::post('/reports/{report}/false-alarm', [AuthController::class, 'markFalseAlarm'])->name('admin.reports.false.alarm');

        });

    /* USER & LIBRARIAN DASHBOARDS */
    Route::middleware('auth')->group(function () {
        Route::get('/homeUser', [HomeController::class, 'index'])->name('home.user');
        Route::get('/resources/search', [HomeController::class, 'search'])->name('resources.search');
        Route::get('/resources/{id}/view', [HomeController::class, 'show'])->name('resources.view');
        Route::post('/borrow/request', [BorrowController::class, 'store'])->name('borrow.request');
        Route::delete('/borrow/cancel/{id}', [BorrowController::class, 'cancel'])->name('borrow.cancel');

        // Community Upload - Allow all authenticated users to upload
        Route::post('/resources/community-upload', [ResourceController::class, 'storeCommunityUpload'])->name('resources.community.upload');
        Route::post('/resources/report', [ResourceController::class, 'report'])->name('resources.report');
    });
    // NEW: Route for incrementing views
    Route::post('/resources/{resource}/view', [ResourceController::class, 'incrementView'])->name('resources.increment.view');
    // Rating route
    Route::post('/ratings', [App\Http\Controllers\RatingController::class, 'store'])->name('ratings.store');

    Route::get('/homeLibrarian', function () {
        if (Auth::user()->role !== 'librarian') {
            abort(403);
        }

        $user = Auth::user();
        $pendingBorrows = \App\Models\Borrower::where('isReturned', 0)
            ->whereNull('Approved_Date')
            ->count();
        $activeBorrows = \App\Models\Borrower::where('isReturned', 0)
            ->whereNotNull('Approved_Date')
            ->count();
        $totalResources = \App\Models\Resource::count();

        return Inertia::render('Librarian/Dashboard', [
            'stats' => [
                'pendingBorrows' => $pendingBorrows,
                'activeBorrows' => $activeBorrows,
                'totalResources' => $totalResources,
            ],
        ]);
    })->name('home.librarian');

    // Borrowers page for librarian
    Route::middleware(['role:librarian'])->group(function () {
        Route::get('/borrowers', [BorrowController::class, 'index'])->name('borrowers');
        Route::post('/borrow/approve/{id}', [BorrowController::class, 'approve'])->name('borrow.approve');
        Route::post('/borrow/reject/{id}', [BorrowController::class, 'reject'])->name('borrow.reject');
        Route::get('/borrower/{id}/details', [BorrowController::class, 'details'])->name('borrower.details');
    });

    /* LIBRARIAN RESOURCE MANAGEMENT */
    Route::middleware(['role:librarian'])->group(function () {
        Route::get('/resource-management', [ResourceController::class, 'index'])->name('resource.management');
        Route::post('/resources', [ResourceController::class, 'store'])->name('resources.store');
        // Flag route must come before {resource} route to avoid route conflict
        Route::post('/resources/flag', [ResourceController::class, 'flag'])->name('resources.flag');
        Route::get('/resources/{resource}', [ResourceController::class, 'show'])->name('resources.show');
        Route::patch('/resources/{resource}', [ResourceController::class, 'update'])->name('resources.update');
        Route::delete('/resources/{resource}', [ResourceController::class, 'destroy'])->name('resources.destroy');
        Route::post('/resources/{id}/approve-community', [ResourceController::class, 'approveCommunityUpload'])->name('resources.approve.community');
        Route::post('/resources/{id}/reject-community', [ResourceController::class, 'rejectCommunityUpload'])->name('resources.reject.community');
    });

    /* FEATURED - Available to all authenticated users */
    Route::get('/featured', [HomeController::class, 'featured'])->name('featured');

    /* COMMUNITY UPLOADS - Available to all authenticated users */
    Route::get('/community-uploads', [HomeController::class, 'communityUploads'])->name('community.uploads');

    /* LIBRARIAN ROLES MANAGEMENT */
    Route::middleware(['role:librarian'])->group(function () {
        Route::get('/librarian/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('librarian.roles');
        Route::patch('/librarian/users/{id}/position', [App\Http\Controllers\RoleController::class, 'updateLibrarianPosition'])->name('librarian.users.position.update');
    });

    /* YOUR SHELF - Available to all authenticated users */
    Route::get('/yourshelf', [ShelfController::class, 'index'])->name('yourshelf');

    /* PROFILE */
    Route::middleware('auth')->group(function () {
        Route::post('/profile/picture', [App\Http\Controllers\ProfileController::class, 'updateProfilePicture'])->name('profile.picture.update');
        Route::delete('/profile/picture', [App\Http\Controllers\ProfileController::class, 'removeProfilePicture'])->name('profile.picture.remove');
        Route::post('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    });
});
