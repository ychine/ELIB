<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ISU StudyGo</title>
    <link rel="icon" href="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap&family=Kulim+Park:ital,wght@0,200;0,300;0,400;0,600;0,700;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/kulimpark.css', 'resources/css/Inter.css', 'resources/css/kantumruypro.css'])

    <style>
        .parent { min-height: 100vh; }
        .name-fields, .role-campus-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        @media (max-width: 640px) {
            .name-fields, .role-campus-fields { grid-template-columns: 1fr; }
        }
        .input-field {
            padding: 0.5rem 0.75rem;
            height: 2.25rem;
        }
        select.input-field { height: 2.75rem; }

        /* ────── TOAST ────── */
        #toast {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            min-width: 300px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            z-index: 1000;
            opacity: 0;
            transform: translateX(100%);
            transition: all .4s cubic-bezier(.175,.885,.32,1.275);
        }
        #toast.show { opacity:1; transform:translateX(0); }
        #toast .icon {
            width:48px;height:48px;background:#DCFCE7;border-radius:50%;
            display:flex;align-items:center;justify-content:center;font-size:1.5rem;
        }
        #toast .icon i {color:#16A34A;}
        #toast .close {background:none;border:none;font-size:1.25rem;color:#9CA3AF;cursor:pointer;}
    </style>
</head>
<body class="bg-gray-100">
<div class="parent p-4 sm:p-6 lg:pt-[5vw] px-4 lg:pb-[2vw] sm:px-6 lg:px-[16vw] w-full min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-[#000000]">

    <!-- LOGO (hidden on <lg) -->
    <img src="{{ Vite::asset('resources/images/ISUStudyGoBorder.svg') }}" alt="ISUStudyGo Logo"
         class="hidden lg:block absolute left-1/2 -translate-x-1/2 top-10 lg:w-[5%] z-10 transition-opacity duration-300">

    <span class="absolute top-1/2 left-1/2 w-[70vw] h-[30vw] -translate-x-1/2 -translate-y-1/2 bg-[#63F068] rounded-full blur-[50vw]"></span>

    <!-- LEFT SIDE (image) -->
    <div class="relative shadow-[0_8px_24px_rgba(0,0,0,0.9)] z-10 hidden lg:block p-4 overflow-visible rounded-l-4xl">
        <div class="absolute inset-0 z-[5] pointer-events-none">
            <img src="{{ Vite::asset('resources/images/librarygreen.jpg') }}" alt="Library"
                 class="w-full rounded-l-4xl h-full object-cover">
        </div>
        <div class="absolute inset-0 rounded-l-4xl p-10 lg:p-16 z-10">
            <div class="text-white drop-shadow-lg max-w-xl">
                <h2 style="color:#FFF;text-shadow:0 4px 4px #000;font-family:'Kulim Park',sans-serif;font-size:4.1vw;font-weight:700;line-height:86.402%;letter-spacing:-4.05px;white-space:nowrap;">Bridging</h2>
                <h2 style="color:#FFF;text-shadow:0 4px 4px #000;font-family:'Kulim Park',sans-serif;font-size:4.1vw;font-weight:700;line-height:86.402%;letter-spacing:-4.05px;white-space:nowrap;">knowledge from</h2>
                <h2 style="color:#FFF;text-shadow:0 4px 4px #000;font-family:'Kulim Park',sans-serif;font-size:4.1vw;font-weight:700;line-height:86.402%;letter-spacing:-4.05px;white-space:nowrap;">one campus to</h2>
                <h2 style="color:#FFF;text-shadow:0 4px 4px #000;font-family:'Kulim Park',sans-serif;font-size:4.1vw;font-weight:700;line-height:86.402%;letter-spacing:-4.05px;white-space:nowrap;">another</h2>
            </div>
            <p class="absolute kulim-park-light bottom-10 lg:bottom-16 font-semibold text-lg sm:text-xl text-white">One ISU</p>
        </div>
    </div>

    <!-- RIGHT SIDE (form) -->
    <div class="relative flex flex-col h-full lg:rounded-r-4xl bg-gray-100 z-1">
        <div class="relative z-10 flex items-center justify-between px-4 sm:px-6 lg:px-12 pt-4 sm:pt-6">
            <div class="flex-shrink-0"></div>
            <img src="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}" alt="ISU Logo"
                 class="w-[3vw] h-[3vw] translate-y-2.5 sm:w-12 sm:h-12 rounded-full shadow" />
        </div>

        <div class="relative z-10 flex-1 flex items-start justify-center px-4 sm:px-6 lg:px-16">
            <div class="w-full max-w-md sm:max-w-lg lg:max-w-xl">

                <div class="mb-4 sm:mb-6 text-left">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold kulim-park-bold tracking-tight flex items-center gap-2">
                        <span>Register to</span>
                        <span class="text-green-700">
                            <img src="{{ Vite::asset('resources/images/ISUStudyGo.svg') }}" alt="ISUStudyGo Logo"
                                 class="inline-block pl-2 h-8 sm:h-10 lg:h-12 w-auto" />
                        </span>
                    </h1>
                    <p class="text-gray-600 mt-2 text-sm sm:text-base">Create your account</p>
                </div>

                @if($errors->any())
                    <div class="bg-red-100 text-red-700 p-2 sm:p-3 rounded mb-4 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register.post') }}" class="tracking-tight kantumruy-pro-regular space-y-4 sm:space-y-6">
                    @csrf

                    <!-- NAME FIELDS -->
                    <div class="name-fields">
                        <div>
                            <label class="block text-sm sm:text-base font-semibold mb-2">First Name</label>
                            <div class="border-b-2 border-gray-300 focus-within:border-green-700 rounded-sm">
                                <input type="text" name="first_name" required placeholder="Enter your first name"
                                       class="w-full input-field outline-none bg-gray-100 rounded-md focus:bg-white text-sm sm:text-base"
                                       value="{{ old('first_name') }}">
                                @error('first_name')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm sm:text-base font-semibold mb-2">Last Name</label>
                            <div class="border-b-2 border-gray-300 focus-within:border-green-700 rounded-sm">
                                <input type="text" name="last_name" required placeholder="Enter your last name"
                                       class="w-full input-field outline-none bg-gray-100 rounded-md focus:bg-white text-sm sm:text-base"
                                       value="{{ old('last_name') }}">
                                @error('last_name')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- EMAIL -->
                    <div>
                        <label class="block text-sm sm:text-base font-semibold mb-2">Email</label>
                        <div class="border-b-2 border-gray-300 focus-within:border-green-700 rounded-sm">
                            <input type="email" name="email" required placeholder="Enter your email"
                                   class="w-full input-field outline-none bg-gray-100 rounded-md focus:bg-white text-sm sm:text-base"
                                   value="{{ old('email') }}">
                            @error('email')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <label class="block text-sm sm:text-base font-semibold mb-2">Password</label>
                        <div class="border-b-2 border-gray-300 focus-within:border-green-700 rounded-sm">
                            <input type="password" name="password" required placeholder="Enter your password"
                                   class="w-full input-field outline-none bg-gray-100 rounded-md focus:bg-white text-sm sm:text-base">
                            @error('password')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm sm:text-base font-semibold mb-2">Confirm Password</label>
                        <div class="border-b-2 border-gray-300 focus-within:border-green-700 rounded-sm">
                            <input type="password" name="password_confirmation" required placeholder="Confirm your password"
                                   class="w-full input-field outline-none bg-gray-100 rounded-md focus:bg-white text-sm sm:text-base">
                        </div>
                    </div>

                    <!-- ROLE + CAMPUS -->
                    <div class="role-campus-fields">
                        <div>
                            <label class="block text-sm sm:text-base font-semibold mb-2">Role</label>
                            <div class="border-b-2 border-gray-300 focus-within:border-green-700 rounded-sm">
                                <select name="role" required class="w-full input-field outline-none bg-gray-100 rounded-md focus:bg-white text-sm sm:text-base">
                                    <option value="" disabled selected>Select your role</option>
                                    <option value="student">Student</option>
                                    <option value="faculty">Faculty</option>
                                    <option value="librarian">Librarian</option>
                              
                                </select>
                                @error('role')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm sm:text-base font-semibold mb-2">Campus</label>
                            <div class="border-b-2 border-gray-300 focus-within:border-green-700 rounded-sm">
                                @if(\App\Models\Campus::count() === 0)
                                    <p class="text-red-600 text-xs">No campuses available. Contact an administrator.</p>
                                @else
                                    <select name="campus_id" required class="w-full input-field outline-none bg-gray-100 rounded-md focus:bg-white text-sm sm:text-base">
                                        <option value="" disabled selected>Select your campus</option>
                                        @foreach(\App\Models\Campus::all() as $campus)
                                            <option value="{{ $campus->Campus_ID }}">{{ $campus->Campus_Name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                @error('campus_id')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-green-800 text-white py-3 rounded-md hover:bg-green-900 transition">
                        Next
                    </button>
                </form>

                @if (Route::has('login'))
                    <p class="text-center text-gray-600 mt-4 kantumruy-pro-regular tracking-tight sm:mt-6 text-sm sm:text-base">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-green-700 font-semibold hover:underline">Click here.</a>
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ────── TOAST (shown after registration) ────── -->
<div id="toast" class="hidden">
    <div class="icon"><i class="fa-solid fa-check"></i></div>
    <div class="message">
        <h4>Account Registered!</h4>
        <p>Please check your e-mail to verify your account.</p>
    </div>
    <button class="close" onclick="hideToast()">×</button>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
    @if(session('status') === 'verification-link-sent')
        showToast();
    @endif

    function showToast() {
        const t = document.getElementById('toast');
        t.classList.remove('hidden');
        setTimeout(() => t.classList.add('show'), 10);
        setTimeout(hideToast, 6000);
    }
    function hideToast() {
        const t = document.getElementById('toast');
        t.classList.remove('show');
        setTimeout(() => t.classList.add('hidden'), 400);
    }
</script>
</body>
</html>