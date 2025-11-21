<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap&family=Kulim+Park:ital,wght@0,200;0,300;0,400;0,600;0,700;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/css/kulimpark.css', 'resources/css/Inter.css', 'resources/css/kantumruypro.css'])

  <title>User Management | ISU StudyGo</title>

  <style>
    .sidebar { width:4rem; transition:all .3s cubic-bezier(.215,.610,.355,1); overflow:hidden; padding-left:.5rem; padding-right:.5rem; cursor:pointer; }
    .sidebar.expanded { width:18rem; }
    .sidebar .label { opacity:0; transform:translateX(-10px); transition:all .3s ease; white-space:nowrap; padding-left:1rem; }
    .sidebar.expanded .label { opacity:1; transform:translateX(0); }
    .isu-studygo-border-logo { opacity:0; transition:opacity .3s ease; }
    .isu-studygo-logo { opacity:1; transition:opacity .3s ease; }
    .sidebar.expanded .isu-studygo-border-logo { opacity:1; }
    .sidebar.expanded .isu-studygo-logo { opacity:0; }
    .sidebar-content { display:flex; flex-direction:column; align-items:center; min-height:100%; transition:all .3s ease; }
    .sidebar-icons { transform:translate(35%,5%); transition:transform .3s ease; }
    .sidebar.expanded .sidebar-icons { transform:translateX(20px); }
    .sidebar.expanded .sidebar-content { align-items:flex-start; padding-left:.5rem; padding-right:.5rem; }
    .sidebar.expanded + .main-content { margin-left:15rem; }

    .searchbar:focus { outline:none; box-shadow:0 0 0 3px rgba(34,197,94,.5),0 0 10px 3px rgba(0,0,0,.5); }
    .glass-nav { background:transparent; transition:all .3s ease; }
    .glass-nav.scrolled { background:linear-gradient(rgba(4,30,10,.9),rgba(4,30,10,.7),rgba(4,30,10,.5),rgba(255,255,255,0)); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px); }
    @supports not (backdrop-filter:blur(16px)) { .glass-nav.scrolled { background:linear-gradient(rgba(34,197,94,.2),rgba(34,197,94,.2)),url('{{ Vite::asset('resources/images/library.jpg') }}'); } }
    .glass-nav.scrolled::before { content:''; position:absolute; inset:0; background-position:50% center; background-size:cover; filter:blur(8px); -webkit-filter:blur(8px); z-index:-1; }
    .searchbar { background:rgba(217,217,217,1); color:#000; transition:all .3s ease; }

    /* Modal */
    .modal { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:50; justify-content:center; align-items:center; }
    .modal.active { display:flex; }
    .modal-content { background:white; border-radius:12px; width:90%; max-width:600px; max-height:90vh; overflow-y:auto; box-shadow:0 10px 30px rgba(0,0,0,0.3); }
    .modal-header { padding:1.5rem; border-bottom:1px solid #e5e7eb; }
    .modal-body { padding:1.5rem; }
    .modal-footer { padding:1rem 1.5rem; border-top:1px solid #e5e7eb; display:flex; justify-content:flex-end; gap:0.5rem; }

    /* Accordion */
    .accordion-header { background: #f3f4f6; padding: 1rem; cursor: pointer; display: flex; justify-content: space-between; align-items: center; font-weight: 600; font-family: 'Kantumruy Pro', sans-serif; }
    .accordion-content { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; padding: 0 1rem; }
    .accordion-content.open { max-height: 500px; padding: 1rem; /* Adjust based on content */ }
    .accordion-icon { transition: transform 0.3s ease; font-size: 1.2rem; }
    .accordion-icon.open { transform: rotate(180deg); }

    /* Softer Checkboxes */
    input[type="checkbox"] { appearance: none; width: 1.25rem; height: 1.25rem; border: 2px solid #d1d5db; border-radius: 0.375rem; background-color: #f9fafb; transition: all 0.2s ease-in-out; }
    input[type="checkbox"]:checked { background-color: #22c55e; border-color: #22c55e; background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6 10.586l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e"); }

    /* Theme Fonts */
    .kantumruy-pro-regular { font-family: 'Kantumruy Pro', sans-serif; }
    .kulim-park-bold { font-family: 'Kulim Park', sans-serif; font-weight: 700; }

    /* Loading */
    .loading { opacity:0.6; pointer-events:none; }
  </style>
</head>
<body class="bg-yellow-50">
<div class="w-full h-[100vh] flex">

  <!-- NAVBAR -->
  <div class="fixed w-full top-0 left-0 flex justify-between items-center px-4 py-2 z-10 glass-nav">
    <span class="text-5xl jersey-20-regular pl-3 text-white"></span>
    <div class="relative flex items-center">
      <input class="searchbar pl-7 pr-10 sm:w-[545px] h-11 rounded-[34px] shadow-[inset_0px_4px_4px_rgba(0,0,0,0.25)]" type="text" placeholder="Search for books, papers..">
      <img src="{{ Vite::asset('resources/images/Search.png') }}" alt="Search" class="absolute right-5 w-6 h-6">
    </div>
    <div class="text-md flex space-x-4 gap-5 pr-6 plus-jakarta-sans-semibold text-white">
      <span class="bg-green-800 rounded-3xl px-3 py-1 border-2 border-amber-400 text-[13px] kantumruy-pro-regular">ADMIN</span>
    </div>
  </div>

  <!-- Universal Sidebar -->
  @include('partials.universalSidebar')

  <!-- MAIN CONTENT -->
  <div class="flex bg-gray-200 flex-col flex-1 transition-all duration-300 main-content">
    <div class="hero-container relative w-full greenhue z-1">
      <img src="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}" alt="ISU Logo" class="absolute right-0 w-15 h-15 m-7">
      <h5 class="absolute text-white right-0 m-7 mr-10 translate-y-30 kulim-park-semibold">One ISU</h5>
      <img src="{{ Vite::asset('resources/images/libgreenptr.jpg') }}" alt="Library" class="w-full h-50 z-[-1] object-cover absolute" style="object-position:70% middle;">
      <div class="herotext h-50 ml-30 flex relative z-2">
        <div class="column">
          <h1 style="transform:translateY(50%); line-height:86.402%; font-family:'Kulim Park',sans-serif; font-weight:600; letter-spacing:-1.3px; font-size:45px; text-shadow:0 4px 4px #000; color:#FFF;">
            Bridging knowledge <br>from one campus <br>to another
          </h1>
        </div>
      </div>

      <!-- USER MANAGEMENT -->
      <div class="homediv lg:mx-[10%] mt-5 rounded-2xl bg-white shadow-lg p-6">
        <h2 class="text-3xl font-extrabold kulim-park-bold mb-6">User Management</h2>
        
        @if(session('success'))
          <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
          <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
          <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <!-- TABS NAVIGATION -->
        <ul class="flex flex-wrap border-b border-gray-200 mb-6">
          <li class="mr-1">
            <a href="{{ route('admin.users') }}" class="tab-link inline-block py-2 px-4 font-semibold {{ request('role') !== 'librarian' && request('role') !== 'admin' && request('role') !== 'faculty' && request('role') !== 'student' ? 'text-green-600 border-b-2 border-green-600' : 'text-gray-500 hover:text-gray-700' }}">All</a>
          </li>
          <li class="mr-1">
            <a href="{{ route('admin.users', ['role' => 'librarian']) }}" class="tab-link inline-block py-2 px-4 font-semibold {{ request('role') === 'librarian' ? 'text-green-600 border-b-2 border-green-600' : 'text-gray-500 hover:text-gray-700' }}">Librarians & Roles</a>
          </li>
          <li class="mr-1">
            <a href="{{ route('admin.users', ['role' => 'admin']) }}" class="tab-link inline-block py-2 px-4 font-semibold {{ request('role') === 'admin' ? 'text-green-600 border-b-2 border-green-600' : 'text-gray-500 hover:text-gray-700' }}">Administrators</a>
          </li>
          <li class="mr-1">
            <a href="{{ route('admin.users', ['role' => 'faculty']) }}" class="tab-link inline-block py-2 px-4 font-semibold {{ request('role') === 'faculty' ? 'text-green-600 border-b-2 border-green-600' : 'text-gray-500 hover:text-gray-700' }}">Faculty</a>
          </li>
          <li class="mr-1">
            <a href="{{ route('admin.users', ['role' => 'student']) }}" class="tab-link inline-block py-2 px-4 font-semibold {{ request('role') === 'student' ? 'text-green-600 border-b-2 border-green-600' : 'text-gray-500 hover:text-gray-700' }}">Students</a>
          </li>
        </ul>

        <!-- ACCORDION FOR MANAGE LIBRARIAN ROLES (Only in Librarians Tab) -->
        @if(request('role') === 'librarian')
          <div class="accordion mb-6">
            <div class="accordion-header kantumruy-pro-regular" onclick="toggleAccordion(this)">
              <span>Manage Librarian Positions</span>
              <span class="accordion-icon">▼</span>
            </div>
            <div class="accordion-content kantumruy-pro-regular">
              <form id="positionForm" method="POST" class="space-y-4">
                @csrf
                <div>
                  <label for="position_select" class="block font-medium">Select Position to Edit (or create new)</label>
                  <select id="position_select" name="position_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" onchange="loadPosition(this.value)">
                    <option value="">Create New Position</option>
                    @foreach(\App\Models\LibrarianPosition::all() as $position)
                      <option value="{{ $position->id }}">{{ $position->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div>
                  <label for="name" class="block font-medium">Position Name</label>
                  <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>
                <div>
                  <label class="block font-medium">Permissions</label>
                  <div class="flex gap-4 mt-2">
                    <label class="flex items-center gap-2">
                      <input type="checkbox" name="permissions[add]" id="perm_add">
                      <span>Add</span>
                    </label>
                    <label class="flex items-center gap-2">
                      <input type="checkbox" name="permissions[edit]" id="perm_edit">
                      <span>Edit</span>
                    </label>
                    <label class="flex items-center gap-2">
                      <input type="checkbox" name="permissions[archive]" id="perm_archive">
                      <span>Archive</span>
                    </label>
                    <label class="flex items-center gap-2">
                      <input type="checkbox" name="permissions[delete]" id="perm_delete">
                      <span>Delete</span>
                    </label>
                  </div>
                </div>
                <button type="submit" id="saveBtn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Save Position</button>
                <button type="button" id="deleteBtn" onclick="deletePosition()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700" style="display: none;">Delete Position</button>
              </form>
            </div>
          </div>
        @endif

        <!-- SEARCH + FILTERS -->
        <div class="mb-6 space-y-4 md:space-y-0 md:flex md:gap-4">
          <input type="text" id="searchInput" placeholder="Search by name or email..."
                 value="{{ request('search') }}"
                 class="kantumruy-pro-regular tracking-tight flex-1 px-4 py-2 border-2 border-gray-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500">

          <select id="roleFilter" class="kantumruy-pro-regular tracking-tight px-10 py-2 border-2 border-gray-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">All Roles</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="librarian" {{ request('role') == 'librarian' ? 'selected' : '' }}>Librarian</option>
            <option value="faculty" {{ request('role') == 'faculty' ? 'selected' : '' }}>Faculty</option>
            <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
          </select>

          <select id="campusFilter" class="kantumruy-pro-regular tracking-tight px-10 py-2 border-2 border-gray-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">All Campuses</option>
            @foreach(\App\Models\Campus::all() as $campus)
              <option value="{{ $campus->Campus_ID }}" {{ request('campus') == $campus->Campus_ID ? 'selected' : '' }}>
                {{ $campus->Campus_Name }}
              </option>
            @endforeach
          </select>

          <button id="clearBtn" class="kantumruy-pro-regular bg-gray-600 text-white h-11 w-15 rounded-xl hover:bg-gray-700">Clear</button>
        </div>

        <!-- TABLE  -->
        <div id="tableContainer">
          @include('partials.user-table', ['users' => $users])
        </div>
      </div>
    </div>
  </div>
</div>

<!-- EDIT MODAL -->
<div id="editModal" class="modal">
  <div class="modal-content kantumruy-pro-regular tracking-tight">
    <div class="modal-header">
      <h3 class="text-xl font-bold">Edit User</h3>
    </div>
    <form id="editForm" method="POST">
      @csrf @method('PATCH')
      <div class="modal-body space-y-4">
        <div>
          <label class="block font-medium">First Name</label>
          <input type="text" name="first_name" id="first_name" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        <div>
          <label class="block font-medium">Last Name</label>
          <input type="text" name="last_name" id="last_name" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        <div>
          <label class="block font-medium">Email</label>
          <input type="email" name="email" id="email" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        <div>
          <label class="block font-medium">Role</label>
          <select name="role" id="role" class="w-full px-3 py-2 border rounded-lg" onchange="togglePositionField()">
            <option value="admin">Admin</option>
            <option value="librarian">Librarian</option>
            <option value="faculty">Faculty</option>
            <option value="student">Student</option>
          </select>
        </div>
        <div id="positionField" style="display: none;">
          <label class="block font-medium">Position</label>
          <select name="position_id" id="position_id" class="w-full px-3 py-2 border rounded-lg">
            <option value="">Unassigned</option>
            @foreach(\App\Models\LibrarianPosition::all() as $position)
              <option value="{{ $position->id }}">{{ $position->name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block font-medium">Campus</label>
          <select name="campus_id" id="campus_id" class="w-full px-3 py-2 border rounded-lg">
            @foreach(\App\Models\Campus::all() as $campus)
              <option value="{{ $campus->Campus_ID }}">{{ $campus->Campus_Name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="flex items-center gap-2">
            <input type="checkbox" name="is_approved" id="is_approved" value="1">
            <span>Approved</span>
          </label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800">Save</button>
        <button type="button" onclick="deleteUser()" class="px-4 py-2 bg-red-700 text-white rounded-lg hover:bg-red-800">Delete User</button>
      </div>
    </form>
  </div>
</div>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
  let currentUserId = null;
  let debounceTimer;
  // -------------------------------------------------
  // 1. FETCH TABLE (AJAX)
  // -------------------------------------------------
  function fetchTable() {
    const search = document.getElementById('searchInput').value;
    const role = document.getElementById('roleFilter').value;
    const campus = document.getElementById('campusFilter').value;
    const url = new URL('/admin/users', window.location.origin);
    if (search) url.searchParams.set('search', search);
    if (role) url.searchParams.set('role', role);
    if (campus) url.searchParams.set('campus', campus);
    window.history.replaceState({}, '', url);
    const container = document.getElementById('tableContainer');
    container.innerHTML = '<p class="text-center py-4">Loading...</p>';
    fetch(url, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.text())
    .then(html => {
      container.innerHTML = html;
      attachRowClickHandlers(); // RE‑ATTACH AFTER AJAX
    });
  }
  // -------------------------------------------------
  // 2. ATTACH ROW CLICK HANDLERS (initial + after AJAX)
  // -------------------------------------------------
  function attachRowClickHandlers() {
    document.querySelectorAll('tr[data-user-id]').forEach(row => {
      row.onclick = () => openEditModal(row.dataset.userId);
    });
  }
  // Run once on page load
  document.addEventListener('DOMContentLoaded', attachRowClickHandlers);
  // -------------------------------------------------
  // 3. INPUT / FILTER EVENTS
  // -------------------------------------------------
  document.getElementById('searchInput').addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(fetchTable, 300);
  });
  ['roleFilter', 'campusFilter'].forEach(id => {
    document.getElementById(id).addEventListener('change', fetchTable);
  });
  document.getElementById('clearBtn').addEventListener('click', () => {
    document.getElementById('searchInput').value = '';
    document.getElementById('roleFilter').value = '';
    document.getElementById('campusFilter').value = '';
    fetchTable();
  });
  // -------------------------------------------------
  // 4. EDIT MODAL
  // -------------------------------------------------
  function openEditModal(id) {
    currentUserId = id;
    fetch(`/admin/users/${id}/edit`)
      .then(r => r.json())
      .then(data => {
        document.getElementById('first_name').value = data.first_name || '';
        document.getElementById('last_name').value = data.last_name || '';
        document.getElementById('email').value = data.email;
        document.getElementById('role').value = data.role;
        document.getElementById('campus_id').value = data.campus_id || '';
        document.getElementById('is_approved').checked = data.is_approved;
        document.getElementById('position_id').value = data.position_id || '';
        document.getElementById('editForm').action = `/admin/users/${id}`;
        togglePositionField();
        document.getElementById('editModal').classList.add('active');
      })
      .catch(() => alert('Failed to load user data.'));
  }
  function togglePositionField() {
    const role = document.getElementById('role').value;
    document.getElementById('positionField').style.display = role === 'librarian' ? 'block' : 'none';
  }
  function closeModal() {
    document.getElementById('editModal').classList.remove('active');
  }
  function deleteUser() {
    if (!confirm('Permanently delete this user?')) return;
    fetch(`/admin/users/${currentUserId}`, {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(() => {
      closeModal();
      fetchTable();
    })
    .catch(() => alert('Delete failed.'));
  }
  // Close modal when clicking outside
  window.addEventListener('click', e => {
    const modal = document.getElementById('editModal');
    if (e.target === modal) closeModal();
  });
  // -------------------------------------------------
  // 5. SIDEBAR & NAVBAR SCROLL
  // -------------------------------------------------
  const sidebar = document.querySelector('.sidebar');
  const sidebarItems = document.querySelectorAll('.sidebar .cursor-pointer, .sidebar button, .sidebar form');
  sidebar.addEventListener('click', e => {
    if (e.target === sidebar || e.target.closest('.sidebar-content') === sidebar.querySelector('.sidebar-content')) {
      sidebar.classList.toggle('expanded');
    }
  });
  sidebarItems.forEach(i => i.addEventListener('click', e => e.stopPropagation()));
  document.addEventListener('click', e => {
    if (sidebar.classList.contains('expanded') && !sidebar.contains(e.target)) {
      sidebar.classList.remove('expanded');
    }
  });
  window.addEventListener('scroll', () => {
    const nav = document.querySelector('.glass-nav');
    const img = document.querySelector('.main-content img');
    const height = img?.offsetHeight ?? 0;
    nav.classList.toggle('scrolled', window.scrollY > height);
  });
  // 6. Accordion Toggle
  function toggleAccordion(element) {
    const content = element.nextElementSibling;
    const icon = element.querySelector('.accordion-icon');
    content.classList.toggle('open');
    icon.classList.toggle('open');
  }
  // 7. Manage Roles Form - Load permissions for selected position
  function loadPosition(positionId) {
    const nameInput = document.getElementById('name');
    const addCheck = document.getElementById('perm_add');
    const editCheck = document.getElementById('perm_edit');
    const archiveCheck = document.getElementById('perm_archive');
    const deleteCheck = document.getElementById('perm_delete');
    const form = document.getElementById('positionForm');
    const deleteBtn = document.getElementById('deleteBtn');
    const saveBtn = document.getElementById('saveBtn');

    // Remove existing _method if any
    let methodInput = form.querySelector('input[name="_method"]');
    if (methodInput) methodInput.remove();

    if (positionId) {
      form.action = `/admin/positions/${positionId}`;
      methodInput = document.createElement('input');
      methodInput.type = 'hidden';
      methodInput.name = '_method';
      methodInput.value = 'PATCH';
      form.appendChild(methodInput);
      fetch(`/admin/positions/${positionId}/edit`, {
        headers: { 'Accept': 'application/json' }
      })
        .then(r => {
          if (!r.ok) {
            throw new Error('Failed to load');
          }
          return r.json();
        })
        .then(data => {
          nameInput.value = data.name;
          addCheck.checked = data.permissions.add || false;
          editCheck.checked = data.permissions.edit || false;
          archiveCheck.checked = data.permissions.archive || false;
          deleteCheck.checked = data.permissions.delete || false;

          const isProtected = data.protected;
          nameInput.disabled = isProtected;
          addCheck.disabled = isProtected;
          editCheck.disabled = isProtected;
          archiveCheck.disabled = isProtected;
          deleteCheck.disabled = isProtected;
          saveBtn.style.display = isProtected ? 'none' : 'block';
          deleteBtn.style.display = isProtected ? 'none' : 'block';
        })
        .catch(() => alert('Failed to load position.'));
    } else {
      form.action = '{{ route("admin.positions.store") }}';
      nameInput.value = '';
      addCheck.checked = false;
      editCheck.checked = false;
      archiveCheck.checked = false;
      deleteCheck.checked = false;
      nameInput.disabled = false;
      addCheck.disabled = false;
      editCheck.disabled = false;
      archiveCheck.disabled = false;
      deleteCheck.disabled = false;
      saveBtn.style.display = 'block';
      deleteBtn.style.display = 'none';
    }
  }
  // New: Delete position via AJAX
  function deletePosition() {
    const positionId = document.getElementById('position_select').value;
    if (!positionId) return; // No position selected
    if (!confirm('Delete this position? Users assigned to it will be reset to default (unassigned).')) return;
    fetch(`/admin/positions/${positionId}`, {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(r => {
      if (!r.ok) {
        return r.json().then(errData => { throw errData.error || 'Failed to delete position.'; });
      }
      return r.json();
    })
    .then(() => {
      alert('Position deleted successfully.');
      location.reload(); // Reload to update the position select dropdown
    })
    .catch(err => alert(err));
  }

  // Initialize form action on page load (for new position)
  document.addEventListener('DOMContentLoaded', () => {
    loadPosition(document.getElementById('position_select').value);
  });
</script>
  @include('partials.globalLoader')
</body>
</html>