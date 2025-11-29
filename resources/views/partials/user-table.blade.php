{{-- resources/views/partials/user-table.blade.php --}}
@if($users->isEmpty())
  <p class="text-gray-600">No users found.</p>
@else
  <div class="overflow-x-auto kantumruy-pro-regular tracking-tight">
    <table class="w-full bg-white shadow rounded border border-gray-200 min-w-[1000px]">
      <thead>
        <tr class="bg-gray-200">
          <th class="p-3 text-left">Name</th>
          <th class="p-3 text-left">Email</th>
          <th class="p-3 text-left">Role</th>
          <th class="p-3 text-left">Campus</th>
          @if($role === 'librarian')
            <th class="p-3 text-left">Position</th>
            <th class="p-3 text-left">Permissions</th>
          @endif
          <th class="p-3 text-left">Created At</th>
          <th class="p-3 text-left">Last Login</th>
          <th class="p-3 text-left">Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
          <tr class=" border-b border-gray-400 hover:bg-gray-100 cursor-pointer" data-user-id="{{ $user->id }}">
            <td class="p-3">
              <div class="flex items-center gap-2">
                @if($user->profile_picture)
                  <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->full_name }}" class="w-8 h-8 rounded-full object-cover">
                @else
                  <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-xs font-semibold text-gray-600">
                    {{ strtoupper(substr($user->full_name, 0, 2)) }}
                  </div>
                @endif
                {{ $user->full_name }}
              </div>
            </td>
            <td class="p-3">{{ $user->email }}</td>
            <td class="p-3">{{ ucfirst($user->role) }}</td>
            <td class="p-3">{{ $user->campus?->Campus_Name ?? 'N/A' }}</td>
            @if($role === 'librarian')
              <td class="p-3">{{ $user->librarian?->position?->name ?? 'Unassigned' }}</td>
              <td class="p-3">
                Add: {{ $user->librarian?->position?->permissions['add'] ? 'Yes' : 'No' }} | 
                Archive: {{ $user->librarian?->position?->permissions['archive'] ? 'Yes' : 'No' }} | 
                Delete: {{ $user->librarian?->position?->permissions['delete'] ? 'Yes' : 'No' }}
              </td>
            @endif
            <td class="p-3">{{ $user->created_at?->format('M d, Y') ?? 'Never' }}</td>
            <td class="p-3">
              <div class="flex flex-col gap-1">
                <span class="text-xs text-gray-600">{{ $user->last_login_formatted ?? 'Never' }}</span>
                @if($user->is_online)
                  <span class="inline-flex items-center gap-1 text-xs">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    Online
                  </span>
                @else
                  <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                    <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                    Offline
                  </span>
                @endif
              </div>
            </td>
            <td class="p-3">
              <span class="px-2 py-1 text-xs rounded-full {{ $user->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                {{ $user->is_approved ? 'Approved' : 'Pending' }}
              </span>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endif