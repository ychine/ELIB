<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LibrarianPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->input('role', 'all');
        $search = $request->input('search');
        $campus = $request->input('campus');

        $query = User::with('campus');

        if ($role !== 'all') {
            $query->where('role', $role);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhereExists(function ($sub) use ($search) {
                      $sub->select(DB::raw(1))
                          ->from('admin')
                          ->whereColumn('admin.UID', 'users.id')
                          ->where(fn($s) => $s->where('First_Name', 'like', "%{$search}%")
                                             ->orWhere('Last_Name', 'like', "%{$search}%"));
                  })
                  ->orWhereExists(function ($sub) use ($search) {
                      $sub->select(DB::raw(1))
                          ->from('librarian')
                          ->whereColumn('librarian.UID', 'users.id')
                          ->where(fn($s) => $s->where('First_Name', 'like', "%{$search}%")
                                             ->orWhere('Last_Name', 'like', "%{$search}%"));
                  })
                  ->orWhereExists(function ($sub) use ($search) {
                      $sub->select(DB::raw(1))
                          ->from('student')
                          ->whereColumn('student.UID', 'users.id')
                          ->where(fn($s) => $s->where('First_Name', 'like', "%{$search}%")
                                             ->orWhere('Last_Name', 'like', "%{$search}%"));
                  })
                  ->orWhereExists(function ($sub) use ($search) {
                      $sub->select(DB::raw(1))
                          ->from('faculty')
                          ->whereColumn('faculty.UID', 'users.id')
                          ->where(fn($s) => $s->where('First_Name', 'like', "%{$search}%")
                                             ->orWhere('Last_Name', 'like', "%{$search}%"));
                  });
            });
        }

        if ($campus) {
            $query->where('Campus_ID', (int)$campus);
        }

        $query->with([
            'admin:UID,First_Name,Last_Name',
            'librarian:UID,First_Name,Last_Name,position_id',
            'librarian.position:permissions,name',
            'student:UID,First_Name,Last_Name',
            'faculty:UID,First_Name,Last_Name'
        ]);

        $query->where('is_approved', 1);

        $users = $query->latest()->paginate(10);

        // Inserted here: Force fresh load
        $users->getCollection()->transform(function ($user) {
            return $user->fresh(['librarian.position']);
        });

        $users->getCollection()->each(function ($user) {
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
            return view('partials.user-table', compact('users', 'role'))->render();
        }

        return view('userManagement', compact('users', 'role'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $profile = match ($user->role) {
            'admin'     => $user->admin,
            'librarian' => $user->librarian,
            'student'   => $user->student,
            'faculty'   => $user->faculty,
            default     => null,
        };

        $position = $user->role === 'librarian' ? $user->librarian?->position : null;

        return response()->json([
            'first_name'   => $profile?->First_Name ?? '',
            'last_name'    => $profile?->Last_Name ?? '',
            'email'        => $user->email,
            'role'         => $user->role,
            'campus_id'    => $user->Campus_ID,
            'is_approved'  => $user->is_approved,
            'position_id'  => $position?->id ?? '',
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
            'position_id' => 'nullable|exists:librarian_positions,id',
        ]);

        $user->update([
            'email'       => $request->email,
            'role'        => $request->role,
            'Campus_ID'   => $request->campus_id,
            'is_approved' => $request->is_approved ? 1 : 0,
        ]);

        $profileModel = match ($request->role) {
            'admin'     => \App\Models\Admin::class,
            'librarian' => \App\Models\Librarian::class,
            'student'   => \App\Models\Student::class,
            'faculty'   => \App\Models\Faculty::class,
        };

        $profileData = [
            'First_Name' => $request->first_name,
            'Last_Name'  => $request->last_name,
        ];

        if ($request->role === 'librarian' && $request->has('position_id')) {
            \App\Models\Librarian::where('UID', $user->id)->update(['position_id' => $request->position_id]);
        }

        $profileModel::updateOrCreate(
            ['UID' => $user->id],
            $profileData
        );

        return redirect()->route('admin.users')->with('status', 'User updated!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

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