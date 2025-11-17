<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\HomeController; 
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\ShelfController;

Route::middleware(['auth'])->group(function () {
    Route::get('/borrow/show/{id}', [App\Http\Controllers\BorrowController::class, 'showBorrow'])
    ->name('borrow.show');
    Route::get('/yourshelf', [ShelfController::class, 'index'])->name('yourshelf');
    Route::get('/view-book/{id}', [ShelfController::class, 'viewBook']);
    Route::get('/viewer/{id}', function($id){
        return view('pdf-viewer', ['id'=>$id, 'user'=>auth()->user()]);
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

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
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
            if (!$user->is_approved) {
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

            
        });

    /* USER & LIBRARIAN DASHBOARDS */
    Route::get('/homeUser', [HomeController::class, 'index'])->name('home.user');
    Route::get('/resources/{id}/view', [HomeController::class, 'show'])->name('resources.view');
    // ADD THIS BLOCK HERE
    Route::post('/borrow/request', [BorrowController::class, 'store'])->name('borrow.request');
    // NEW: Route for incrementing views
    Route::post('/resources/{resource}/view', [ResourceController::class, 'incrementView'])->name('resources.increment.view');

    Route::get('/homeLibrarian', function () {
        if (Auth::user()->role !== 'librarian') abort(403);
        return view('homeLibrarian');
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
        Route::get('/resources/{resource}', [ResourceController::class, 'show'])->name('resources.show');
        Route::patch('/resources/{resource}', [ResourceController::class, 'update'])->name('resources.update');
    });

    /* LIBRARIAN FEATURED */
    Route::get('/featured', function () {
        if (Auth::user()->role !== 'librarian') abort(403);
        return view('featured');
    })->name('featured');

    /* LIBRARIAN COMMUNITY UPLOADS */
    Route::get('/community-uploads', function () {
        if (Auth::user()->role !== 'librarian') abort(403);
        return view('communityuploads');
    })->name('community.uploads');

    /* LIBRARIAN YOUR SHELF */
    Route::get('/your-shelf', function () {
        if (Auth::user()->role !== 'librarian') abort(403);
        return view('yourshelf');
    })->name('your.shelf');
});