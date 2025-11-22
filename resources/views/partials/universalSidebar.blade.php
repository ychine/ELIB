@php
    // Helper function to generate menu items based on role
    $user = auth()->user();
    $role = $user->role ?? 'user';
    $currentRoute = Route::currentRouteName();
    
    // Define menu items based on role
    $menuItems = [];
    
    if ($role === 'admin') {
        $menuItems = [
            [
                'key' => 'admin.approvals',
                'label' => 'Dashboard',
                'href' => route('admin.approvals'),
                'icon' => Vite::asset('resources/images/Dashboard.png'),
                'iconActive' => Vite::asset('resources/images/DashboardToggled.png'),
            ],
            [
                'key' => 'admin.users',
                'label' => 'User Management',
                'href' => route('admin.users'),
                'icon' => Vite::asset('resources/images/umgmt.png'),
                'iconActive' => Vite::asset('resources/images/umgmttoggle.png'),
            ],
            [
                'key' => 'admin.audit',
                'label' => 'Audit Trail',
                'href' => route('admin.audit'),
                'icon' => Vite::asset('resources/images/Dashboard.png'),
                'iconActive' => Vite::asset('resources/images/DashboardToggled.png'),
            ],
            [
                'key' => 'admin.analytics',
                'label' => 'Resource Analytics',
                'href' => route('admin.analytics'),
                'icon' => Vite::asset('resources/images/Dashboard.png'),
                'iconActive' => Vite::asset('resources/images/DashboardToggled.png'),
            ],
        ];
    } elseif ($role === 'librarian') {
        $menuItems = [
            [
                'key' => 'home.librarian',
                'label' => 'Dashboard',
                'href' => route('home.librarian'),
                'icon' => Vite::asset('resources/images/Dashboard.png'),
                'iconActive' => Vite::asset('resources/images/DashboardToggled.png'),
            ],
            [
                'key' => 'borrowers',
                'label' => 'Borrowers',
                'href' => route('borrowers'),
                'icon' => Vite::asset('resources/images/borrowers.png'),
                'iconActive' => Vite::asset('resources/images/borrowerstoggle.png'),
            ],
            [
                'key' => 'resource.management',
                'label' => 'Resource Management',
                'href' => route('resource.management'),
                'icon' => Vite::asset('resources/images/resmgmt.png'),
                'iconActive' => Vite::asset('resources/images/resmgmttoggle.png'),
            ],
            [
                'key' => 'librarian.roles',
                'label' => 'Roles',
                'href' => route('librarian.roles'),
                'icon' => Vite::asset('resources/images/Featured.png'),
            ],

        ];
    } else {
        // Regular user
        $menuItems = [
            [
                'key' => 'home.user',
                'label' => 'Home',
                'href' => route('home.user'),
                'icon' => Vite::asset('resources/images/Home.png'),
                'iconActive' => Vite::asset('resources/images/HomeToggle.png'),
            ],
            [
                'key' => 'featured',
                'label' => 'Featured',
                'href' => route('featured'),
                'icon' => Vite::asset('resources/images/Featured.png'),
            ],
            [
                'key' => 'community.uploads',
                'label' => 'Community Uploads',
                'href' => route('community.uploads'),
                'icon' => Vite::asset('resources/images/Member.png'),
            ],
            [
                'key' => 'yourshelf',
                'label' => 'Your Shelf',
                'href' => route('yourshelf'),
                'icon' => Vite::asset('resources/images/Book Shelf.png'),
            ],
        ];
    }
    
    // Note: Logout is now in the profile dropdown, not in the main menu
    
    // Get user info
    $userName = 'User';
    if ($user->faculty) {
        $userName = ($user->faculty->First_Name ?? '') . ' ' . ($user->faculty->Last_Name ?? '');
    } elseif ($user->librarian) {
        $userName = ($user->librarian->First_Name ?? '') . ' ' . ($user->librarian->Last_Name ?? '');
    } elseif ($user->student) {
        $userName = ($user->student->First_Name ?? '') . ' ' . ($user->student->Last_Name ?? '');
    } elseif ($user->admin) {
        $userName = ($user->admin->First_Name ?? '') . ' ' . ($user->admin->Last_Name ?? '');
    }
    $userName = trim($userName) ?: 'User';
    
    $campusName = 'Unknown Campus';
    if ($user->campus && isset($user->campus->Campus_Name)) {
        $campusName = $user->campus->Campus_Name;
    }
    
    $userData = [
        'name' => $userName,
        'email' => $user->email ?? 'user@example.com',
        'avatar' => null, // Add avatar path if available
        'campus' => $campusName,
    ];
    
    // Logo paths
    $logos = [
        'border' => Vite::asset('resources/images/ISUStudyGoBorder.svg'),
        'solid' => Vite::asset('resources/images/ISUclpsd.svg'),
    ];
@endphp

<div 
    id="universal-sidebar-root"
    data-menu-items="{{ json_encode($menuItems) }}"
    data-active-route="{{ $currentRoute }}"
    data-user="{{ json_encode($userData) }}"
    data-logos="{{ json_encode($logos) }}"
    data-logout-url="{{ route('logout') }}"
    data-role="{{ $role }}"
    data-default-expanded="false"
    style="position: fixed; top: 0; left: 0; height: 100vh; width: 4rem; background: #149637; z-index: 20; display: flex; align-items: center; justify-content: center;"
>
    <!-- Loading placeholder - shows immediately while Vue component mounts -->
    <div style="color: white; font-size: 0.75rem; opacity: 0.7;">Loading...</div>
</div>

@if(!isset($skipVite))
    @include('partials.globalLoader')
@endif

