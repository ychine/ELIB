{{-- resources/views/userManagement.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Kulim+Park:ital,wght@0,200;0,300;0,400;0,600;0,700;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/css/output.css', 'resources/css/Inter.css', 'resources/css/kulimpark.css'])

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
    <div class="text-md flex space-x-4 gap-5 pr-6 plus-jakarta-sans-semibold text-white"></div>
  </div>

  <!-- SIDEBAR -->
  <div class="fixed top-0 left-0 h-full bg-[#149637] shadow-[5px_-10px_22.5px_2px_rgba(0,0,0,0.59)] rounded-tr-[50px] sidebar z-20 pt-8">
    <div class="sidebar-content space-y-2 text-white">
      <img src="{{ Vite::asset('resources/images/ISUStudyGoBorder.svg') }}" alt="Logo" class="w-full h-20 isu-studygo-border-logo">
      <img src="{{ Vite::asset('resources/images/ISUclpsd.svg') }}" alt="Logo" class="w-full h-10 translate-y-[20px] absolute isu-studygo-logo">

      <a href="{{ route('admin.approvals') }}" class=" hover:bg-green-700 shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)]  w-full h-12 rounded-xl flex items-center gap-3 cursor-pointer {{ request()->routeIs('admin.approvals') ? 'bg-green-800' : 'bg-green-800' }}">
        <img src="{{ Vite::asset('resources/images/Dashboard.png') }}" alt="Dashboard" class="w-7 h-7 sidebar-icons">
        <span class="label kulim-park-regular text-lg">Dashboard</span>
      </a>

      <a href="{{ route('admin.users') }}" class="w-full h-12 bg-green-500 rounded-xl  flex items-center gap-3 cursor-pointertransition-colors">
        <img src="{{ Vite::asset('resources/images/umgmttoggle.png') }}" alt="User Management" class="w-7 h-7 sidebar-icons">
        <span class="label kulim-park-regular text-lg">User Management</span>
      </a>

      <a href="#" class="w-full h-12 bg-green-800 rounded-xl shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)] flex items-center gap-3 cursor-pointer hover:bg-green-700 transition-colors">
        <img src="{{ Vite::asset('resources/images/Featured.png') }}" alt="Featured" class="w-7 h-7 translate-y-[-1px] translate-x-[1px] sidebar-icons">
        <span class="label kulim-park-regular text-lg">Featured</span>
      </a>
      <a href="#" class="w-full h-12 bg-green-800 rounded-xl shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)] flex items-center gap-3 cursor-pointer hover:bg-green-700 transition-colors">
        <img src="{{ Vite::asset('resources/images/Member.png') }}" alt="Community" class="w-7 h-7 sidebar-icons">
        <span class="label kulim-park-regular text-lg">Community Uploads</span>
      </a>
      <a href="#" class="w-full h-12 bg-green-800 rounded-xl shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)] flex items-center gap-3 cursor-pointer hover:bg-green-700 transition-colors">
        <img src="{{ Vite::asset('resources/images/Book Shelf.png') }}" alt="Shelf" class="w-7 h-7 sidebar-icons">
        <span class="label kulim-park-regular text-lg">Your Shelf</span>
      </a>

      <form method="POST" action="{{ route('logout') }}" class="mt-auto w-full h-12 bg-green-800 rounded-xl shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)] flex items-center gap-3">
        @csrf
        <button type="submit" class="flex items-center gap-3 w-full h-full bg-transparent border-none text-white cursor-pointer hover:bg-red-700 transition-colors rounded-xl">
          <i class="fa-solid fa-sign-out-alt text-2xl sidebar-icons"></i>
          <span class="label text-lg">Logout</span>
        </button>
      </form>
    </div>
  </div>

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

        @if(session('status'))
          <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">{{ session('status') }}</div>
        @endif

        <!--  SEARCH + FILTERS -->
        <div class="mb-6 space-y-4 md:space-y-0 md:flex md:gap-4">
          <input type="text" id="searchInput" placeholder="Search by name or email..."
                 value="{{ request('search') }}"
                 class="flex-1 px-4 py-2 border-2 border-gray-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500">

          <select id="roleFilter" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">All Roles</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="librarian" {{ request('role') == 'librarian' ? 'selected' : '' }}>Librarian</option>
            <option value="faculty" {{ request('role') == 'faculty' ? 'selected' : '' }}>Faculty</option>
            <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
          </select>

          <select id="campusFilter" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">All Campuses</option>
            @foreach(\App\Models\Campus::all() as $campus)
              <option value="{{ $campus->Campus_ID }}" {{ request('campus') == $campus->Campus_ID ? 'selected' : '' }}>
                {{ $campus->Campus_Name }}
              </option>
            @endforeach
          </select>

          <button id="clearBtn" class="bg-gray-600 text-white h-11 w-15 rounded-lg hover:bg-gray-700">Clear</button>
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
  <div class="modal-content">
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
          <select name="role" id="role" class="w-full px-3 py-2 border rounded-lg">
            <option value="admin">Admin</option>
            <option value="librarian">Librarian</option>
            <option value="faculty">Faculty</option>
            <option value="student">Student</option>
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
    const role   = document.getElementById('roleFilter').value;
    const campus = document.getElementById('campusFilter').value;

    const url = new URL('/admin/users', window.location.origin);
    if (search) url.searchParams.set('search', search);
    if (role)   url.searchParams.set('role',   role);
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
      attachRowClickHandlers();   // RE‑ATTACH AFTER AJAX
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
    document.getElementById('roleFilter').value   = '';
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
        document.getElementById('first_name').value   = data.first_name || '';
        document.getElementById('last_name').value    = data.last_name  || '';
        document.getElementById('email').value        = data.email;
        document.getElementById('role').value         = data.role;
        document.getElementById('campus_id').value    = data.campus_id || '';
        document.getElementById('is_approved').checked = data.is_approved;
        document.getElementById('editForm').action    = `/admin/users/${id}`;
        document.getElementById('editModal').classList.add('active');
      })
      .catch(() => alert('Failed to load user data.'));
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
    const nav   = document.querySelector('.glass-nav');
    const img   = document.querySelector('.main-content img');
    const height = img?.offsetHeight ?? 0;
    nav.classList.toggle('scrolled', window.scrollY > height);
  });
</script>
</body>
</html>