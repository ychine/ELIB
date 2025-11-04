{{-- resources/views/partials/user-table.blade.php --}}
@if($users->isEmpty())
  <p class="text-gray-600">No users found.</p>
@else
  <div class="overflow-x-auto kantumruy-pro-regular tracking-tight">
    <table class="w-full bg-white shadow rounded-lg">
      <thead>
        <tr class="bg-gray-200">
          <th class="p-3 text-left">Name</th>
          <th class="p-3 text-left">Email</th>
          <th class="p-3 text-left">Role</th>
          <th class="p-3 text-left">Campus</th>
          <th class="p-3 text-left">Created At</th>
          <th class="p-3 text-left">Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
          <tr class=" border-b border-gray-400 hover:bg-gray-100 cursor-pointer" data-user-id="{{ $user->id }}">
            <td class="p-3">{{ $user->full_name }}</td>
            <td class="p-3">{{ $user->email }}</td>
            <td class="p-3">{{ ucfirst($user->role) }}</td>
            <td class="p-3">{{ $user->campus?->Campus_Name ?? 'N/A' }}</td>
            <td class="p-3">{{ $user->created_at?->format('M d, Y') ?? 'Never' }}</td>
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