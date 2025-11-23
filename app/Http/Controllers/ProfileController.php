<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Delete old profile picture if exists
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Store new profile picture
        $path = $request->file('profile_picture')->store('profile-pictures', 'public');

        $user->update(['profile_picture' => $path]);

        // Log audit
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'profile_picture_update',
            'description' => 'Updated profile picture',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'profile_picture' => $path,
            'message' => 'Profile picture updated successfully!',
        ]);
    }

    public function removeProfilePicture()
    {
        $user = Auth::user();

        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->update(['profile_picture' => null]);

        // Log audit
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'profile_picture_remove',
            'description' => 'Removed profile picture',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Profile picture removed successfully!');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'student_number' => 'nullable|string|max:20|regex:/^23-\d{3,}$/',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        // Update user email
        $user->update([
            'email' => $validated['email'],
        ]);

        // Update password if provided
        if (! empty($validated['password'])) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        // Update profile name based on role
        $profileModel = match ($user->role) {
            'admin' => \App\Models\Admin::class,
            'librarian' => \App\Models\Librarian::class,
            'student' => \App\Models\Student::class,
            'faculty' => \App\Models\Faculty::class,
            default => null,
        };

        if ($profileModel) {
            $profileData = [
                'First_Name' => $validated['first_name'],
                'Last_Name' => $validated['last_name'],
            ];

            // Add student-specific fields
            if ($user->role === 'student') {
                if (isset($validated['student_number'])) {
                    $profileData['student_number'] = $validated['student_number'];
                }
                if (isset($validated['course_id'])) {
                    $profileData['course_id'] = $validated['course_id'];
                }
            }

            $profileModel::updateOrCreate(
                ['UID' => $user->id],
                $profileData
            );
        }

        // Log audit
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'profile_update',
            'description' => 'Updated profile information',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!',
        ]);
    }
}
