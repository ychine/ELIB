<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap&family=Kulim+Park:ital,wght@0,200;0,300;0,400;0,600;0,700;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @vite(['resources/css/app.css', 'resources/css/output.css', 'resources/css/kulimpark.css', 'resources/css/Inter.css', 'resources/css/kantumruypro.css'])

  <title>Resource Analytics | ISU StudyGo</title>

  <style>
    html, body, .main-content { overflow-x: hidden; }

    .sidebar {
      width: 4rem;
      transition: all 0.3s cubic-bezier(0.215, 0.610, 0.355, 1);
      overflow: hidden;
      padding-left: 0.5rem;
      padding-right: 0.5rem;
      cursor: pointer;
    }
    .sidebar.expanded { width: 18rem; }
    .sidebar .label {
      opacity: 0;
      transform: translateX(-10px);
      transition: all 0.3s ease;
      white-space: nowrap;
      padding-left: 1rem;
    }
    .sidebar.expanded .label { opacity: 1; transform: translateX(0); }
    .isu-studygo-border-logo { opacity: 0; transition: opacity 0.3s ease; }
    .isu-studygo-logo { opacity: 1; transition: opacity 0.3s ease; }
    .sidebar.expanded .isu-studygo-border-logo { opacity: 1; }
    .sidebar.expanded .isu-studygo-logo { opacity: 0; }
    .sidebar-content { display: flex; flex-direction: column; align-items: center; transition: all 0.3s ease; min-height: 100%; }
    .sidebar-icons { transform: translate(35%, 5%); transition: transform 0.3s ease; }
    .sidebar.expanded .sidebar-icons { transform: translateX(20px); }
    .sidebar.expanded .sidebar-content { align-items: flex-start; padding-left: 0.5rem; padding-right: 0.5rem; }
    .sidebar.expanded + .main-content { margin-left: 15rem; margin-top: 0; }

    .glass-nav {
      background: transparent;
      transition: all 0.3s ease;
    }
    .glass-nav.scrolled {
      background: linear-gradient(rgba(4, 30, 10, 0.9), rgba(4, 30, 10, 0.7), rgba(4, 30, 10, 0.5), rgba(255, 255, 255, 0.0));
      background-position: 50% center;
      background-size: cover;
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
    }

    @supports not (backdrop-filter: blur(16px)) {
      .glass-nav.scrolled {
        background: linear-gradient(rgba(34, 197, 94, 0.2), rgba(34, 197, 94, 0.2)), url('{{ Vite::asset('resources/images/library.jpg') }}');
        background-position: 50% center;
        background-size: cover;
      }
    }

    .glass-nav.scrolled::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-position: 50% center;
      background-size: cover;
      filter: blur(8px) !important;
      -webkit-filter: blur(8px) !important;
      z-index: -1;
    }

    .glass-nav .nav-item {
      visibility: hidden;
      transition: visibility 0.3s ease;
    }
    .glass-nav.scrolled .nav-item { visibility: visible; }

    .searchbar {
      background: rgba(217, 217, 217, 1);
      color: #000;
      transition: all 0.3s ease;
    }

    @media (max-width: 1024px) {
      .sidebar { display: none; }
      .main-content { padding-bottom: 5rem; }
    }

    /* Page Title Styling - Match Sidebar Colors */
    .page-title-container {
      background: #166534;
      border-radius: 0.75rem;
      padding: 1rem 1.5rem;
      margin-bottom: 1.5rem;
      box-shadow: inset 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
    }
    .page-title-container.active {
      background: #22c55e;
      box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.5);
    }
    .page-title-container h2 {
      color: white;
      margin: 0;
    }

    /* Profile Dropdown in Header */
    .profile-dropdown-container {
      position: relative;
    }
    .profile-trigger-btn {
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 0.5rem;
      background: rgba(255, 255, 255, 0.2);
      border: 2px solid rgba(255, 255, 255, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 600;
      font-size: 0.875rem;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .profile-trigger-btn:hover {
      background: rgba(255, 255, 255, 0.3);
    }
    .profile-dropdown-menu {
      position: absolute;
      top: calc(100% + 0.5rem);
      right: 0;
      background: white;
      border-radius: 0.75rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      min-width: 200px;
      overflow: hidden;
      z-index: 50;
      display: none;
    }
    .profile-dropdown-menu.show {
      display: block;
    }
    .profile-dropdown-item {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      width: 100%;
      padding: 0.75rem 1rem;
      background: transparent;
      border: none;
      color: #1f2937;
      font-size: 0.875rem;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.2s ease;
      text-align: left;
      font-family: 'Kantumruy Pro', sans-serif;
    }
    .profile-dropdown-item:hover {
      background: #f3f4f6;
    }
    .profile-dropdown-item.logout {
      color: #dc2626;
      border-top: 1px solid #e5e7eb;
    }
    .profile-dropdown-item.logout:hover {
      background: #fef2f2;
    }
  </style>
</head>
<body class="bg-gray-50">
  <div class="w-full h-[100vh] flex">

    <!-- Navigation -->
    <div class="fixed w-full top-0 left-0 flex justify-between items-center px-4 py-2 z-10 glass-nav">
      <span class="text-5xl jersey-20-regular pl-3 text-white"></span>
      <div class="relative flex items-center">
        <input class="searchbar pl-7 pr-10 sm:w-[545px] h-11 rounded-[34px] shadow-[inset_0px_4px_4px_rgba(0,0,0,0.25)]" type="text" placeholder="Search for books, papers..">
        <img 
          src="{{ Vite::asset('resources/images/Search.png') }}" 
          alt="Search icon" 
          class="absolute right-5 w-6 h-6"
        />
      </div>
      <div class="text-md flex space-x-4 gap-5 pr-6 plus-jakarta-sans-semibold text-white">
        <!-- Profile Dropdown -->
        <div class="profile-dropdown-container">
          <button type="button" class="profile-trigger-btn" onclick="toggleProfileDropdown(event)">
            @php
              $user = auth()->user();
              $userName = 'U';
              if ($user->faculty) {
                $name = ($user->faculty->First_Name ?? '') . ' ' . ($user->faculty->Last_Name ?? '');
                $parts = explode(' ', trim($name));
                $userName = count($parts) >= 2 ? strtoupper($parts[0][0] . $parts[count($parts)-1][0]) : strtoupper(substr($name, 0, 2));
              } elseif ($user->librarian) {
                $name = ($user->librarian->First_Name ?? '') . ' ' . ($user->librarian->Last_Name ?? '');
                $parts = explode(' ', trim($name));
                $userName = count($parts) >= 2 ? strtoupper($parts[0][0] . $parts[count($parts)-1][0]) : strtoupper(substr($name, 0, 2));
              } elseif ($user->student) {
                $name = ($user->student->First_Name ?? '') . ' ' . ($user->student->Last_Name ?? '');
                $parts = explode(' ', trim($name));
                $userName = count($parts) >= 2 ? strtoupper($parts[0][0] . $parts[count($parts)-1][0]) : strtoupper(substr($name, 0, 2));
              } elseif ($user->admin) {
                $name = ($user->admin->First_Name ?? '') . ' ' . ($user->admin->Last_Name ?? '');
                $parts = explode(' ', trim($name));
                $userName = count($parts) >= 2 ? strtoupper($parts[0][0] . $parts[count($parts)-1][0]) : strtoupper(substr($name, 0, 2));
              }
            @endphp
            {{ $userName }}
          </button>
          <div class="profile-dropdown-menu" id="profileDropdown">
            <button type="button" class="profile-dropdown-item" onclick="openAccountSettings()">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
              <span>Account Settings</span>
            </button>
            <form method="POST" action="{{ route('logout') }}" class="profile-dropdown-item-form">
              @csrf
              <button type="submit" class="profile-dropdown-item logout">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span>Logout</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    @include('partials.universalSidebar')

    <div class="flex bg-gray-200 flex-col flex-1 transition-all duration-300 main-content">
      <div class="hero-container relative w-full greenhue z-1">
        <img 
          src="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}" 
          alt="ISU Logo" 
          class="absolute right-0 w-15 h-15 m-7"
        />
        <h5 class="absolute text-white right-0 m-7 mr-10 translate-y-30 kulim-park-semibold">One ISU</h5>
        <img 
          src="{{ Vite::asset('resources/images/libgreenptr.jpg') }}" 
          alt="Library" 
          class="w-full h-50 z-[-1] object-cover absolute"
          style="object-position: 70% middle;"
        />
        <div class="herotext h-50 ml-30 flex relative z-2">
          <div class="column">
            <h1 style="transform: translateY(50%); line-height: 86.402%; font-family: 'Kulim Park', sans-serif; font-weight: 600; letter-spacing: -1.3px; font-size: 45px; text-shadow: 0 4px 4px #000; color: #FFF;">
              Bridging knowledge <br>
              from one campus <br>
              to another
            </h1>
          </div>
        </div>
        <div class="px-4 lg:px-[5%] pt-4">
          <div class="page-title-container active">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold kulim-park-bold tracking-tight">Resource Analytics</h2>
          </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="px-4 lg:px-[5%] grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
          <div class="rounded-2xl bg-white shadow-lg p-6">
            <h3 class="text-lg font-semibold kulim-park-semibold text-gray-700 mb-2">Total Resources</h3>
            <p class="text-3xl font-bold text-green-700">{{ $totalResources }}</p>
          </div>
        </div>

        <!-- Resources by Type -->
        <div class="px-4 lg:px-[5%] rounded-2xl bg-white shadow-lg p-6 mb-6">
          <h2 class="text-1xl sm:text-2xl lg:text-3xl font-extrabold kulim-park-bold tracking-tight mb-6">Resources by Type</h2>
          @if($resourcesByType->isEmpty())
            <p class="text-gray-600">No resources found.</p>
          @else
            <div class="overflow-x-auto">
              <table class="w-full kantumruy-pro-regular tracking-tight bg-white shadow rounded-lg min-w-[400px]">
                <thead>
                  <tr class="bg-gray-200">
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Count</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($resourcesByType as $type)
                    <tr>
                      <td class="p-3">{{ $type->Type ?? 'Unknown' }}</td>
                      <td class="p-3">{{ $type->count }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>

        <!-- Top Viewed Resources -->
        <div class="px-4 lg:px-[5%] rounded-2xl bg-white shadow-lg p-6 mb-6">
          <h2 class="text-1xl sm:text-2xl lg:text-3xl font-extrabold kulim-park-bold tracking-tight mb-6">Top Viewed Resources</h2>
          @if($topViewed->isEmpty())
            <p class="text-gray-600">No resources found.</p>
          @else
            <div class="overflow-x-auto">
              <table class="w-full kantumruy-pro-regular tracking-tight bg-white shadow rounded-lg min-w-[600px]">
                <thead>
                  <tr class="bg-gray-200">
                    <th class="p-3 text-left">Resource Name</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Views</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($topViewed as $resource)
                    <tr>
                      <td class="p-3">{{ $resource->Resource_Name ?? 'N/A' }}</td>
                      <td class="p-3">{{ $resource->Type ?? 'N/A' }}</td>
                      <td class="p-3">{{ $resource->views ?? 0 }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>

        <!-- Recent Uploads -->
        <div class="px-4 lg:px-[5%] rounded-2xl bg-white shadow-lg p-6">
          <h2 class="text-1xl sm:text-2xl lg:text-3xl font-extrabold kulim-park-bold tracking-tight mb-6">Recent Uploads</h2>
          @if($recentUploads->isEmpty())
            <p class="text-gray-600">No recent uploads.</p>
          @else
            <div class="overflow-x-auto">
              <table class="w-full kantumruy-pro-regular tracking-tight bg-white shadow rounded-lg min-w-[600px]">
                <thead>
                  <tr class="bg-gray-200">
                    <th class="p-3 text-left">Resource Name</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Upload Date</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($recentUploads as $resource)
                    <tr>
                      <td class="p-3">{{ $resource->Resource_Name ?? 'N/A' }}</td>
                      <td class="p-3">{{ $resource->Type ?? 'N/A' }}</td>
                      <td class="p-3">{{ $resource->created_at ? $resource->created_at->format('Y-m-d') : 'N/A' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

  <script>
    function initGlassNavScroll() {
      const nav = document.querySelector('.glass-nav');
      if (!nav) return;

      // Find the library green image specifically
      const heroImage = document.querySelector('.hero-container img[src*="libgreenptr"]');
      const heroContainer = document.querySelector('.hero-container');

      const updateNavBlur = () => {
        if (!heroImage && !heroContainer) {
          nav.classList.add('scrolled');
          return;
        }

        // Use the image if available, otherwise use the container
        const reference = heroImage || heroContainer;
        const rect = reference.getBoundingClientRect();
        const tolerance = nav.offsetHeight + 16;

        // Add scrolled class when scrolled past the image/container
        if (rect.bottom <= tolerance) {
          nav.classList.add('scrolled');
        } else {
          nav.classList.remove('scrolled');
        }
      };

      window.addEventListener('load', updateNavBlur, { once: true });
      window.addEventListener('scroll', updateNavBlur, { passive: true });
      window.addEventListener('resize', updateNavBlur);

      if (heroImage instanceof HTMLImageElement && !heroImage.complete) {
        heroImage.addEventListener('load', updateNavBlur, { once: true });
      }

      updateNavBlur();
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initGlassNavScroll);
    } else {
      initGlassNavScroll();
    }
  </script>

  <script>
    function toggleProfileDropdown(event) {
      event.stopPropagation();
      const dropdown = document.getElementById('profileDropdown');
      dropdown.classList.toggle('show');
    }

    function openAccountSettings() {
      const event = new CustomEvent('sidebar:open-account-settings', {
        bubbles: true,
      });
      window.dispatchEvent(event);
      document.dispatchEvent(event);
      document.getElementById('profileDropdown').classList.remove('show');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
      const dropdown = document.getElementById('profileDropdown');
      const trigger = document.querySelector('.profile-trigger-btn');
      if (dropdown && !dropdown.contains(event.target) && !trigger.contains(event.target)) {
        dropdown.classList.remove('show');
      }
    });
  </script>
  <!-- Mobile Bottom Navigation -->
  @php
    $user = auth()->user();
    $role = $user->role ?? 'user';
    $currentRoute = Route::currentRouteName();
    
    if ($role === 'admin') {
        $menuItems = [
            ['key' => 'admin.approvals', 'label' => 'Dashboard', 'href' => route('admin.approvals'), 'icon' => Vite::asset('resources/images/Dashboard.png'), 'iconActive' => Vite::asset('resources/images/DashboardToggled.png')],
            ['key' => 'admin.users', 'label' => 'User Management', 'href' => route('admin.users'), 'icon' => Vite::asset('resources/images/umgmt.png'), 'iconActive' => Vite::asset('resources/images/umgmttoggle.png')],
            ['key' => 'admin.audit', 'label' => 'Audit Trail', 'href' => route('admin.audit'), 'icon' => Vite::asset('resources/images/Dashboard.png'), 'iconActive' => Vite::asset('resources/images/DashboardToggled.png')],
            ['key' => 'admin.analytics', 'label' => 'Resource Analytics', 'href' => route('admin.analytics'), 'icon' => Vite::asset('resources/images/Dashboard.png'), 'iconActive' => Vite::asset('resources/images/DashboardToggled.png')],
        ];
    }
    
    $userName = 'User';
    if ($user->admin) {
        $userName = ($user->admin->First_Name ?? '') . ' ' . ($user->admin->Last_Name ?? '');
    }
    $userName = trim($userName) ?: 'User';
    
    $profileData = ['name' => $userName, 'email' => $user->email ?? 'user@example.com', 'profile_picture' => null];
    $profileMenu = [
        ['label' => 'Account Settings', 'action' => 'account-settings'],
        ['label' => 'Logout', 'action' => 'logout'],
    ];
  @endphp
  <div 
      id="mobile-bottom-nav-root"
      data-menu-items="{{ json_encode($menuItems ?? []) }}"
      data-active-route="{{ $currentRoute }}"
      data-profile="{{ json_encode($profileData) }}"
      data-profile-menu="{{ json_encode($profileMenu) }}"
      data-logout-url="{{ route('logout') }}"
  ></div>
  
  @include('partials.globalLoader')
</body>
</html>

