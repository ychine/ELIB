<?php

namespace App\Http\Controllers;

use App\Models\LibrarianPosition;
use App\Models\User;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:librarian_positions,name',
            'permissions.add' => 'nullable',
            'permissions.edit' => 'nullable',
            'permissions.archive' => 'nullable',
            'permissions.delete' => 'nullable',
        ]);

        $permissions = [
            'add' => $request->boolean('permissions.add'),
            'edit' => $request->boolean('permissions.edit'),
            'archive' => $request->boolean('permissions.archive'),
            'delete' => $request->boolean('permissions.delete'),
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

        $defaults = ['add' => false, 'edit' => false, 'archive' => false, 'delete' => false];
        if ($position->name === 'University Librarian') {
            $defaults = ['add' => true, 'edit' => true, 'archive' => true, 'delete' => true];
        } elseif ($position->name === 'Librarian') {
            $defaults = ['add' => false, 'edit' => false, 'archive' => false, 'delete' => false];
        }

        $permissions = array_merge($defaults, $position->permissions ?? []);

        $isProtected = in_array($position->name, ['Librarian', 'University Librarian']);

        return response()->json([
            'name' => $position->name,
            'permissions' => $permissions,
            'protected' => $isProtected,
        ]);
    }

    public function update(Request $request, $id)
    {
        $position = LibrarianPosition::findOrFail($id);

        if (in_array($position->name, ['Librarian', 'University Librarian'])) {
            return back()->with('error', 'Cannot update protected position.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:librarian_positions,name,' . $id,
            'permissions.add' => 'nullable',
            'permissions.edit' => 'nullable',
            'permissions.archive' => 'nullable',
            'permissions.delete' => 'nullable',
        ]);

        $permissions = [
            'add' => $request->boolean('permissions.add'),
            'edit' => $request->boolean('permissions.edit'),
            'archive' => $request->boolean('permissions.archive'),
            'delete' => $request->boolean('permissions.delete'),
        ];

        $position->update([
            'name' => $request->name,
            'permissions' => $permissions,
        ]);

        return back()->with('success', 'Position updated successfully.');
    }

    public function destroy($id)
    {
        $position = LibrarianPosition::findOrFail($id);

        if (in_array($position->name, ['Librarian', 'University Librarian'])) {
            return response()->json(['error' => 'Cannot delete protected position.'], 403);
        }

        User::where('position_id', $id)->update(['position_id' => null]);

        $position->delete();

        return response()->json(['message' => 'Position deleted successfully.']);
    }
}