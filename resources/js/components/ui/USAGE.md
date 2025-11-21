# Universal Sidebar Usage Guide

## Quick Start

### 1. Include the Sidebar Partial in Your Blade Template

Replace your existing sidebar HTML with:

```blade
@include('partials.universalSidebar')
```

The partial automatically:
- Detects the user's role (admin, librarian, user)
- Generates appropriate menu items
- Sets up user data
- Configures logos

### 2. Adjust Main Content Margin

The sidebar will automatically adjust the main content margin when expanded. Make sure your main content has the class `main-content`:

```blade
<div class="main-content">
    <!-- Your page content -->
</div>
```

### 3. Remove Old Sidebar Code

Remove the old sidebar HTML from your blade files. The new sidebar is handled entirely by Vue.

### 4. Remove Profile from Header

Remove the "Profile" text/link from your header navigation. The profile is now in the sidebar.

## Example: Updating homeUser.blade.php

**Before:**
```blade
<!-- Old sidebar code -->
<div class="sidebar">
    <!-- ... old sidebar HTML ... -->
</div>
```

**After:**
```blade
@include('partials.universalSidebar')

<div class="main-content">
    <!-- Your existing content -->
</div>
```

## Customization

### Custom Menu Items

If you need to customize menu items for a specific page, you can override the menu items:

```blade
@php
    $customMenuItems = [
        [
            'key' => 'custom.page',
            'label' => 'Custom Page',
            'href' => route('custom.page'),
            'icon' => Vite::asset('resources/images/custom-icon.png'),
        ],
        // ... more items
    ];
@endphp

<div 
    id="universal-sidebar-root"
    data-menu-items="{{ json_encode($customMenuItems) }}"
    data-active-route="{{ Route::currentRouteName() }}"
    data-user="{{ json_encode($userData) }}"
    data-logos="{{ json_encode($logos) }}"
    data-default-expanded="false"
></div>
@include('partials.globalLoader')
```

### Custom User Data

```blade
@php
    $userData = [
        'name' => 'Custom Name',
        'email' => 'custom@example.com',
        'avatar' => '/path/to/avatar.jpg',
        'campus' => 'Custom Campus',
    ];
@endphp
```

## Account Settings Overlay

The Account Settings overlay opens automatically when the profile card is clicked. It includes:

- Profile information editing
- Password change
- Avatar upload (placeholder for now)

To handle save events, you can listen to the `save` event in your Vue app or handle it server-side via the form submission.

## Styling

The sidebar uses the following color scheme:
- Background: `#149637` (green)
- Active item: `#22c55e` (lighter green)
- Inactive items: `#166534` (darker green)
- Hover: `#15803d` (medium green)

You can customize these in `UniversalSidebar.vue` if needed.

## Responsive Behavior

- **Desktop (>768px)**: Sidebar is visible, can be expanded/collapsed
- **Mobile (≤768px)**: Sidebar is hidden (you may want to add a mobile menu separately)

## Tooltips

When the sidebar is collapsed, hovering over menu items shows tooltips on the right side with the page name.




