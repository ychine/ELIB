<?php

namespace App\Http\Controllers;

use App\Models\LibrarianPosition;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions.add' => 'boolean',
            'permissions.archive' => 'boolean',
            'permissions.delete' => 'boolean',
        ]);

        $permissions = [
            'add' => $request->input('permissions.add', false),
            'archive' => $request->input('permissions.archive', false),
            'delete' => $request->input('permissions.delete', false),
        ];

        LibrarianPosition::create([
            'name' => $request->name,
            'permissions' => $permissions,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Position created successfully.');
    }

    public function edit($id)
    {
        $position = LibrarianPosition::findOrFail($id);
        return response()->json([
            'name' => $position->name,
            'permissions' => $position->permissions,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions.add' => 'boolean',
            'permissions.archive' => 'boolean',
            'permissions.delete' => 'boolean',
        ]);

        $position = LibrarianPosition::findOrFail($id);

        $permissions = [
            'add' => $request->input('permissions.add', false),
            'archive' => $request->input('permissions.archive', false),
            'delete' => $request->input('permissions.delete', false),
        ];

        $position->update([
            'name' => $request->name,
            'permissions' => $permissions,
        ]);

        return back()->with('success', 'Position updated successfully.');
    }
}