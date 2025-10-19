<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Auth routes
|--------------------------------------------------------------------------
*/
Route::get('/signin', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/signin', [AuthController::class, 'login'])->name('login.post');

// logout (POST)
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/signin')->with('status', 'Logged out');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Protected routes - require auth
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        $role = Auth::user()->role ?? null;

        if ($role === 'admin') {
            return redirect()->route('home.admin');
        }

        if ($role === 'faculty' || $role === 'student') {
            return redirect()->route('home.user');
        }

        Auth::logout();
        return redirect('/signin');
    })->name('home');

  
    Route::get('/homeAdmin', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        return view('homeAdmin');
    })->name('home.admin');

    Route::get('/homeUser', function () {
        if (!in_array(Auth::user()->role, ['student','faculty'])) {
            abort(403);
        }
        return view('homeUser');
    })->name('home.user');
});
