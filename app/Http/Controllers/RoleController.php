<?php

namespace App\Http\Controllers;

use App\Models\LibrarianPosition;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    public function index(): Response
    {
        $user = Auth::user();
        $isUniversityLibrarian = false;

        if ($user->role === 'librarian' && $user->librarian && $user->librarian->position) {
            $isUniversityLibrarian = $user->librarian->position->name === 'University Librarian';
        }

        $positions = LibrarianPosition::with('creator')
            ->withCount('librarians')
            ->latest()
            ->get()
            ->map(function ($position) {
                return [
                    'id' => $position->id,
                    'name' => $position->name,
                    'permissions' => $position->permissions,
                    'librarians_count' => $position->librarians_count,
                    'created_by' => $position->creator ? $position->creator->full_name : 'System',
                    'created_at' => $position->created_at->format('M d, Y'),
                    'is_protected' => in_array($position->name, ['Librarian', 'University Librarian']),
                ];
            });

        $librarians = User::where('role', 'librarian')
            ->with(['librarian.position', 'campus'])
            ->where('is_approved', true)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->full_name,
                    'email' => $user->email,
                    'position' => $user->librarian?->position?->name ?? 'Unassigned',
                    'position_id' => $user->librarian?->position_id,
                    'campus' => $user->campus?->Campus_Name ?? 'N/A',
                ];
            });

        return Inertia::render('Librarian/Roles', [
            'positions' => $positions,
            'librarians' => $librarians,
            'isUniversityLibrarian' => $isUniversityLibrarian,
        ]);
    }
}
