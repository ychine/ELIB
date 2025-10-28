<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/signin', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/signin', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/signin')->with('status', 'Logged out');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        $user = Auth::user();
        $role = $user->role ?? null;

        if ($role === 'admin') {
            return redirect()->route('admin.approvals');
        }

        if ($role === 'faculty' || $role === 'student') {
            if (!$user->is_approved) {
                Auth::logout();
                return redirect('/signin')->with('error', 'Your account is pending admin approval.');
            }
            return redirect()->route('home.user');
        }

        if ($role === 'librarian') {
            if (!$user->is_approved) {
                Auth::logout();
                return redirect('/signin')->with('error', 'Your account is pending admin approval.');
            }
            return redirect()->route('home.librarian');
        }

        Auth::logout();
        return redirect('/signin');
    })->name('home');

    Route::get('/admin', [AuthController::class, 'showPendingApprovals'])->name('admin.approvals')->middleware('role:admin');
    Route::post('/admin/approve/{user}', [AuthController::class, 'approveUser'])->name('admin.approve')->middleware('role:admin');
    Route::delete('/admin/reject/{user}', [AuthController::class, 'rejectUser'])->name('admin.reject')->middleware('role:admin');

    Route::get('/homeUser', function () {
        if (!in_array(Auth::user()->role, ['student', 'faculty'])) {
            abort(403);
        }
        return view('homeUser');
    })->name('home.user');

    Route::get('/homeLibrarian', function () {
        if (Auth::user()->role !== 'librarian') {
            abort(403);
        }
        return view('homeLibrarian');
    })->name('home.librarian');
});