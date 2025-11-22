{{-- resources/views/register.blade.php --}}
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
    .parent { min-height: 100vh; position: relative; overflow: hidden; }
    .auth-card {
      border-radius: 32px;
      background: rgba(255, 255, 255, 0.92);
      box-shadow: 0 35px 120px rgba(15, 23, 42, 0.3);
      border: 1px solid rgba(255, 255, 255, 0.35);
      backdrop-filter: blur(14px);
    }
    .auth-card::before {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: inherit;
      pointer-events: none;
      border: 1px solid rgba(34, 197, 94, 0.18);
    }
    .auth-field {
      background: rgba(255, 255, 255, 0.9);
      border: 1px solid rgba(15, 23, 42, 0.08);
      box-shadow: inset 0 1px 3px rgba(15, 23, 42, 0.06);
      transition: border-color 200ms ease, background 200ms ease, transform 200ms ease, box-shadow 200ms ease;
    }
    .auth-field:focus-within {
      border-color: rgba(34, 197, 94, 0.65);
      background: #ffffff;
      transform: translateY(-1px);
      box-shadow: 0 24px 45px rgba(34, 197, 94, 0.14);
    }
    .auth-field input,
    .auth-field select {
      background: transparent;
      color: #0f172a;
    }
    .auth-submit {
      border-radius: 16px;
      font-weight: 600;
      letter-spacing: 0.02em;
      box-shadow: 0 20px 60px rgba(34, 197, 94, 0.35);
    }
    @media (max-width: 1024px) {
      .parent {
        grid-template-columns: 1fr;
        padding-top: 20vw;
      }
      .auth-card {
        border-radius: 24px;
      }
    }
    @media (max-width: 640px) {
      .auth-card {
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.3);
      }
      .auth-card img {
        width: 56px;
        height: 56px;
      }
    }

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
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    #toast.show {
      opacity: 1;
      transform: translateX(0);
    }
    #toast .icon {
      width: 48px;
      height: 48px;
      background: #FEF3C7;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
    }
    #toast .icon i {
      color: #D97706;
    }
    #toast .message {
      flex: 1;
    }
    #toast .message h4 {
      font-family: 'Kantumruy Pro', sans-serif;
      margin: 0;
      font-weight: 600;
      color: #1F2937;
    }
    #toast .message p {
      font-family: 'Inter', sans-serif;
      margin: 0.25rem 0 0;
      color: #6B7280;
      font-size: 0.875rem;
    }
    #toast .close {
      background: none;
      border: none;
      font-size: 1.25rem;
      color: #9CA3AF;
      cursor: pointer;
    }
  </style>
</head>
<body class="bg-gray-100">
  <div class="parent p-4 sm:p-6 lg:pt-[5vw] px-4 lg:pb-[2vw] sm:px-6 lg:px-[16vw] w-full min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-[#000000]">
   <img
    src="{{ Vite::asset('resources/images/ISUStudyGoBorder.svg') }}"
    alt="ISUStudyGo Logo"
    class="
      hidden          
      lg:block       

      absolute left-1/2 -translate-x-1/2 top-10
      lg:w-[5%] z-10
      transition-opacity duration-300
    ">
    <span class="absolute top-1/2 left-1/2 w-[70vw] h-[30vw] -translate-x-1/2 -translate-y-1/2 bg-[#63F068] rounded-full blur-[50vw]"></span>
    
    <!-- LEFT SIDE (HIDDEN ON MOBILE) -->
    <div class="relative shadow-[0_8px_24px_rgba(0,0,0,0.9)] z-10 hidden lg:block p-4 overflow-visible rounded-l-4xl">
      <div class="absolute inset-0 z-[5] pointer-events-none">
        <img src="{{ Vite::asset('resources/images/librarygreen.jpg') }}" alt="Library" class="w-full rounded-l-4xl h-full object-cover">
      </div>
      <div class="absolute inset-0 rounded-l-4xl p-10 lg:p-16 z-10">
        <div class="text-white drop-shadow-lg max-w-xl">
          <h2 style="color:#FFF;text-shadow:0 4px 4px #000;font-family:'Kulim Park',sans-serif;font-size:4.1vw;font-style:normal;font-weight:700;line-height:80.402%;letter-spacing:-4.05px;white-space:nowrap;">Bridging</h2>
          <h2 style="color:#FFF;text-shadow:0 4px 4px #000;font-family:'Kulim Park',sans-serif;font-size:4.1vw;font-style:normal;font-weight:700;line-height:80.402%;letter-spacing:-4.05px;white-space:nowrap;">knowledge from</h2>
          <h2 style="color:#FFF;text-shadow:0 4px 4px #000;font-family:'Kulim Park',sans-serif;font-size:4.1vw;font-style:normal;font-weight:700;line-height:80.402%;letter-spacing:-4.05px;white-space:nowrap;">one campus to</h2>
          <h2 style="color:#FFF;text-shadow:0 4px 4px #000;font-family:'Kulim Park',sans-serif;font-size:4.1vw;font-style:normal;font-weight:700;line-height:80.402%;letter-spacing:-4.05px;white-space:nowrap;">another</h2>
        </div>
        <p class="absolute kulim-park-light bottom-10 lg:bottom-16 font-semibold text-lg sm:text-xl text-white">One ISU</p>
      </div>
    </div>

    <!-- RIGHT SIDE (FORM) -->
    <div class="relative flex flex-col h-full lg:rounded-r-4xl bg-gray-100 z-1">
      <div class="relative z-10 flex items-center justify-between px-4 sm:px-6 lg:px-12 pt-2 sm:pt-4">
        <div class="flex-shrink-0"></div>
        <img src="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}" alt="ISU Logo" class="w-[3vw] h-[3vw] translate-y-2.5 sm:w-12 sm:h-12 rounded-full shadow" />
      </div>

      <div class="relative z-10 flex-1 flex items-start justify-center px-4 sm:px-6 lg:px-16 pt-0 sm:pt-2">
        <div class="w-full max-w-md sm:max-w-lg lg:max-w-xl">
          <div class="mb-4 sm:mb-6 text-left">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold kulim-park-bold tracking-tight flex items-center gap-2">
              <span>Register to</span>
              <span class="text-green-700">
                <img src="{{ Vite::asset('resources/images/ISUStudyGo.svg') }}" alt="ISUStudyGo Logo" class="inline-block h-8 sm:h-10 lg:h-12 w-auto" />
              </span>
            </h1>
            <p class="text-gray-600 mt-2 kantumruy-pro-regular tracking-tight text-sm sm:text-base">Create your account to get started.</p>
          </div>

          <!-- ERROR MESSAGES -->
          @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
              <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <!-- SUCCESS / INFO -->
          @if(session('status'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
              {{ session('status') }}
            </div>
          @endif

          <!-- REGISTRATION FORM -->
          <form method="POST" action="{{ route('register.post') }}" class="space-y-4 sm:space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm sm:text-base kantumruy-pro-bold font-semibold mb-2">First Name</label>
                <div class="border-b-3 border-gray-300 focus-within:border-green-700 rounded-lg auth-field">
                  <input type="text" name="first_name" value="{{ old('first_name') }}" required placeholder="John"
                         class="w-full px-3 py-1 sm:py-3 outline-none bg-transparent rounded-lg focus:bg-white text-sm sm:text-base">
                </div>
                @error('first_name')
                  <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="block text-sm sm:text-base kantumruy-pro-bold font-semibold mb-2">Last Name</label>
                <div class="border-b-3 border-gray-300 focus-within:border-green-700 rounded-lg auth-field">
                  <input type="text" name="last_name" value="{{ old('last_name') }}" required placeholder="Doe"
                         class="w-full px-3 py-1 sm:py-3 outline-none bg-transparent rounded-lg focus:bg-white text-sm sm:text-base">
                </div>
                @error('last_name')
                  <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
              </div>
            </div>

            <div>
              <label class="block text-sm sm:text-base kantumruy-pro-bold font-semibold mb-2">Email</label>
              <div class="border-b-3 border-gray-300 focus-within:border-green-700 rounded-lg auth-field">
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="example@mail.com"
                       class="w-full px-3 py-1 sm:py-3 outline-none bg-transparent rounded-lg focus:bg-white text-sm sm:text-base">
              </div>
              @error('email')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="block text-sm sm:text-base kantumruy-pro-bold font-semibold mb-2">Password</label>
              <div class="border-b-3 border-gray-300 focus-within:border-green-700 rounded-lg auth-field">
                <input type="password" name="password" required placeholder="*********"
                       class="w-full px-2 py-1 sm:py-3 outline-none bg-transparent rounded-lg focus:bg-white text-sm sm:text-base">
              </div>
              @error('password')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="block text-sm sm:text-base kantumruy-pro-bold font-semibold mb-2">Confirm Password</label>
              <div class="border-b-3 border-gray-300 focus-within:border-green-700 rounded-lg auth-field">
                <input type="password" name="password_confirmation" required placeholder="*********"
                       class="w-full px-2 py-1 sm:py-3 outline-none bg-transparent rounded-lg focus:bg-white text-sm sm:text-base">
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm sm:text-base kantumruy-pro-bold font-semibold mb-2">Role</label>
                <div class="border-b-3 border-gray-300 focus-within:border-green-700 rounded-lg auth-field">
                  <select name="role" required
                          class="w-full px-3 py-1 sm:py-3 outline-none bg-transparent rounded-lg focus:bg-white text-sm sm:text-base">
                    <option value="">Select Role</option>
                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                    <option value="faculty" {{ old('role') == 'faculty' ? 'selected' : '' }}>Faculty</option>
                    <option value="librarian" {{ old('role') == 'librarian' ? 'selected' : '' }}>Librarian</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                  </select>
                </div>
                @error('role')
                  <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="block text-sm sm:text-base kantumruy-pro-bold font-semibold mb-2">Campus</label>
                <div class="border-b-3 border-gray-300 focus-within:border-green-700 rounded-lg auth-field">
                  <select name="campus_id" required
                          class="w-full px-3 py-1 sm:py-3 outline-none bg-transparent rounded-lg focus:bg-white text-sm sm:text-base">
                    <option value="">Select Campus</option>
                    @foreach($campuses as $campus)
                      <option value="{{ $campus->Campus_ID }}" {{ old('campus_id') == $campus->Campus_ID ? 'selected' : '' }}>
                        {{ $campus->Campus_Name }}
                      </option>
                    @endforeach
                  </select>
                </div>
                @error('campus_id')
                  <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
              </div>
            </div>

            <button type="submit"
                    class="w-full bg-green-800 text-white py-3 sm:py-4 rounded-md hover:bg-green-900 transition text-sm sm:text-base auth-submit">
              Register
            </button>
          </form>

          @if (Route::has('login'))
            <p class="text-center kantumruy-pro-regular tracking-tight text-gray-600 mt-4 sm:mt-6 text-sm sm:text-base">
              Already have an account?
              <a href="{{ route('login') }}" class="text-green-700 font-semibold hover:underline">Login here!</a>
            </p>
          @endif
        </div>
      </div>

    
    </div>
  </div>

  <!-- FONT AWESOME FOR ICON -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  @include('partials.globalLoader')
</body>
</html>
