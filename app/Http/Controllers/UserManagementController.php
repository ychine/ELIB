<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    // app/Http/Controllers/UserManagementController.php

    public function index(Request $request)
    {
        $query = User::with('campus');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhereExists(function ($sub) use ($search) {
                      $sub->select(\DB::raw(1))
                          ->from('admin')
                          ->whereColumn('admin.UID', 'users.id')
                          ->where(fn($s) => $s->where('First_Name', 'like', "%{$search}%")
                                             ->orWhere('Last_Name', 'like', "%{$search}%"));
                  })
                  ->orWhereExists(function ($sub) use ($search) {
                      $sub->select(\DB::raw(1))
                          ->from('librarian')
                          ->whereColumn('librarian.UID', 'users.id')
                          ->where(fn($s) => $s->where('First_Name', 'like', "%{$search}%")
                                             ->orWhere('Last_Name', 'like', "%{$search}%"));
                  })
                  ->orWhereExists(function ($sub) use ($search) {
                      $sub->select(\DB::raw(1))
                          ->from('student')
                          ->whereColumn('student.UID', 'users.id')
                          ->where(fn($s) => $s->where('First_Name', 'like', "%{$search}%")
                                             ->orWhere('Last_Name', 'like', "%{$search}%"));
                  })
                  ->orWhereExists(function ($sub) use ($search) {
                      $sub->select(\DB::raw(1))
                          ->from('faculty')
                          ->whereColumn('faculty.UID', 'users.id')
                          ->where(fn($s) => $s->where('First_Name', 'like', "%{$search}%")
                                             ->orWhere('Last_Name', 'like', "%{$search}%"));
                  });
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        if ($campus = $request->input('campus')) {
            $query->where('Campus_ID', (int)$campus);
        }

        $users = $query->latest()->get();

        $users->load([
            'admin:UID,First_Name,Last_Name',
            'librarian:UID,First_Name,Last_Name',
            'student:UID,First_Name,Last_Name',
            'faculty:UID,First_Name,Last_Name'
        ]);

        $users->each(function ($user) {
            $profile = match ($user->role) {
                'admin'     => $user->admin,
                'librarian' => $user->librarian,
                'student'   => $user->student,
                'faculty'   => $user->faculty,
                default     => null,
            };
            $user->full_name = $profile
                ? trim($profile->First_Name . ' ' . $profile->Last_Name)
                : 'N/A';
        });

        if ($request->ajax() || $request->wantsJson()) {
            return view('partials.user-table', compact('users'))->render();
        }

        return view('userManagement', compact('users'));
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);

        // Load the correct profile based on role
        $profile = match ($user->role) {
            'admin'     => $user->admin,
            'librarian' => $user->librarian,
            'student'   => $user->student,
            'faculty'   => $user->faculty,
            default     => null,
        };

        return response()->json([
            'first_name'   => $profile?->First_Name ?? '',
            'last_name'    => $profile?->Last_Name ?? '',
            'email'        => $user->email,
            'role'         => $user->role,
            'campus_id'    => $user->Campus_ID,
            'is_approved'  => $user->is_approved,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $id,
            'role'       => 'required|in:admin,librarian,student,faculty',
            'campus_id'  => 'nullable|exists:campus,Campus_ID',
        ]);

        // Update User
        $user->update([
            'email'       => $request->email,
            'role'        => $request->role,
            'Campus_ID'   => $request->campus_id,
            'is_approved' => $request->is_approved ? 1 : 0,
        ]);

        // Update Profile
        $profileModel = match ($request->role) {
            'admin'     => \App\Models\Admin::class,
            'librarian' => \App\Models\Librarian::class,
            'student'   => \App\Models\Student::class,
            'faculty'   => \App\Models\Faculty::class,
        };

        $profileModel::updateOrCreate(
            ['UID' => $user->id],
            [
                'First_Name' => $request->first_name,
                'Last_Name'  => $request->last_name,
            ]
        );

        return redirect()->route('admin.users')->with('status', 'User updated!');
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Delete profile first
        match ($user->role) {
            'admin'     => $user->admin?->delete(),
            'librarian' => $user->librarian?->delete(),
            'student'   => $user->student?->delete(),
            'faculty'   => $user->faculty?->delete(),
        };

        $user->delete();

        return response()->json(['success' => true]);
    }
}