<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Vite;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        // Only load relationships when needed (lazy loading optimization)
        if ($user instanceof User) {
            // Use loadMissing to avoid N+1 queries, but only load what's needed
            $user->loadMissing('campus'); // Always needed for sidebar
            // Load role-specific relation only when needed
            match ($user->role) {
                'admin' => $user->loadMissing('admin'),
                'faculty' => $user->loadMissing('faculty'),
                'librarian' => $user->loadMissing('librarian'),
                'student' => $user->loadMissing('student'),
                default => null,
            };
        }

        $userData = null;
        if ($user) {
            $userData = [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'name' => $this->resolveUserName($user),
                'profile_picture' => $user->profile_picture,
                'is_online' => $user->is_online ?? false,
                'campus' => $user->campus->Campus_Name ?? null,
            ];

            // Add student-specific data
            if ($user->role === 'student' && $user->student) {
                $userData['first_name'] = $user->student->First_Name ?? '';
                $userData['last_name'] = $user->student->Last_Name ?? '';
                $userData['student_number'] = $user->student->student_number ?? '';
                $userData['course_id'] = $user->student->course_id ?? null;
                $userData['course_name'] = $user->student->course->name ?? null;
            } else {
                // For non-students, get first_name and last_name from their profile
                $profile = match ($user->role) {
                    'admin' => $user->admin,
                    'faculty' => $user->faculty,
                    'librarian' => $user->librarian,
                    default => null,
                };
                $userData['first_name'] = $profile->First_Name ?? '';
                $userData['last_name'] = $profile->Last_Name ?? '';
            }
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $userData,
                'logoutUrl' => route('logout'),
            ],
            'sidebar' => $user ? [
                'menuItems' => $this->menuItemsForRole($user),
                'logos' => $this->logos(),
                'activeRoute' => Route::currentRouteName(),
                'role' => $user->role,
                'logoutUrl' => route('logout'),
                'courses' => \App\Models\Course::all()->map(fn ($course) => [
                    'id' => $course->id,
                    'name' => $course->name,
                    'code' => $course->code ?? '',
                ]),
            ] : null,
            'flash' => [
                'success' => fn () => $request->session()->get('status'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'images' => [
                'sealLogo' => Vite::asset('resources/images/FINAL_SEAL.png'),
                'libraryImage' => Vite::asset('resources/images/libgreenptr.jpg'),
                'searchIcon' => Vite::asset('resources/images/Search.png'),
            ],
        ];
    }

    protected function resolveUserName(User $user): string
    {
        $name = $this->relationName($user->admin)
            ?? $this->relationName($user->faculty)
            ?? $this->relationName($user->librarian)
            ?? $this->relationName($user->student);

        return $name ?? 'User';
    }

    protected function relationName(?object $relation): ?string
    {
        if (! $relation) {
            return null;
        }

        $first = $relation->First_Name ?? '';
        $last = $relation->Last_Name ?? '';

        return trim(trim($first).' '.trim($last)) ?: null;
    }

    protected function menuItemsForRole(User $user): array
    {
        return match ($user->role) {
            'admin' => [
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
                    'icon' => Vite::asset('resources/images/audit.png'),
                    'iconActive' => Vite::asset('resources/images/audittoggle.png'),
                ],
                [
                    'key' => 'admin.analytics',
                    'label' => 'Resource Analytics',
                    'href' => route('admin.analytics'),
                    'icon' => Vite::asset('resources/images/analytics.png'),
                    'iconActive' => Vite::asset('resources/images/analyticstoggle.png'),
                ],
            ],
            'librarian' => [
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
                    'icon' => Vite::asset('resources/images/role.png'),
                    'iconActive' => Vite::asset('resources/images/roletoggle.png'),
                ],
                [
                    'key' => 'community.uploads',
                    'label' => 'Community Uploads',
                    'href' => route('community.uploads'),
                    'icon' => Vite::asset('resources/images/Member.png'),
                ],
            ],
            default => [
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
            ],
        };
    }

    protected function logos(): array
    {
        return [
            'border' => Vite::asset('resources/images/ISUStudyGoBorder.svg'),
            'solid' => Vite::asset('resources/images/ISUclpsd.svg'),
        ];
    }
}
