@php
    // Reuse the same menu items and user data from universalSidebar
    $user = auth()->user();
    $role = $user->role ?? 'user';
    $currentRoute = Route::currentRouteName();
    
    // Define menu items based on role (same as sidebar)
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
    
    // Get user info (same as sidebar)
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
    
    $profileData = [
        'name' => $userName,
        'email' => $user->email ?? 'user@example.com',
        'profile_picture' => null, // Add avatar path if available
    ];
    
    // Profile menu items (same as sidebar dropdown)
    $profileMenu = [
        ['label' => 'Account Settings', 'action' => 'account-settings'],
        ['label' => 'Logout', 'action' => 'logout'],
    ];
@endphp

<!-- Mobile Bottom Navigation Root -->
<div 
    id="mobile-bottom-nav-root"
    data-menu-items="{{ json_encode($menuItems) }}"
    data-active-route="{{ $currentRoute }}"
    data-profile="{{ json_encode($profileData) }}"
    data-profile-menu="{{ json_encode($profileMenu) }}"
    data-logout-url="{{ route('logout') }}"
    style="display: block; min-height: 0;"
></div>

