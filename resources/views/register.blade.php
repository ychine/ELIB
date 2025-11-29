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
    /* Ensure links are green, not blue */
    .auth-card a,
    .relative.flex.flex-col a {
      color: #15803d !important;
      text-decoration: none;
    }
    .auth-card a:hover,
    .relative.flex.flex-col a:hover {
      text-decoration: underline;
    }
    /* Custom checkbox styling */
    input[type="checkbox"] {
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      width: 1.25rem;
      height: 1.25rem;
      border: 2px solid #9ca3af;
      border-radius: 4px;
      background-color: #fff;
      cursor: pointer;
      position: relative;
      transition: all 0.2s ease;
      flex-shrink: 0;
    }
    input[type="checkbox"]:hover {
      border-color: #15803d;
      background-color: #f0fdf4;
    }
    input[type="checkbox"]:checked {
      background-color: #15803d;
      border-color: #15803d;
    }
    input[type="checkbox"]:checked::after {
      content: '✓';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: white;
      font-size: 0.875rem;
      font-weight: bold;
      line-height: 1;
    }
    input[type="checkbox"]:focus {
      outline: 2px solid rgba(21, 128, 61, 0.4);
      outline-offset: 2px;
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
        <h2 style="color:#FFF;text-shadow:0 4px 4px #000;font-family:'Kulim Park',sans-serif;font-size:4.1vw;font-style:normal;font-weight:700;line-height:80.402%;letter-spacing:-4.05px;white-space:nowrap;">Cultivating</h2>
          <h2 style="color:#FFF;text-shadow:0 4px 4px #000;font-family:'Kulim Park',sans-serif;font-size:4.1vw;font-style:normal;font-weight:700;line-height:80.402%;letter-spacing:-4.05px;white-space:nowrap;">knowledge</h2>
          <h2 style="color:#FFF;text-shadow:0 4px 4px #000;font-family:'Kulim Park',sans-serif;font-size:4.1vw;font-style:normal;font-weight:700;line-height:80.402%;letter-spacing:-4.05px;white-space:nowrap;">within our home</h2>
          <h2 style="color:#FFF;text-shadow:0 4px 4px #000;font-family:'Kulim Park',sans-serif;font-size:4.1vw;font-style:normal;font-weight:700;line-height:80.402%;letter-spacing:-4.05px;white-space:nowrap;">of learning</h2>
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

      <div class="relative z-10 flex-1 translate-y-[-5%] flex items-start justify-center px-4 sm:px-6 lg:px-16 pt-0 sm:pt-2">
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
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="border-b-3 border-gray-300 focus-within:border-green-700 rounded-lg auth-field">
                  <input type="password" name="password" id="password" required placeholder="Password"
                         class="w-full px-3 py-1 sm:py-3 outline-none bg-transparent rounded-lg focus:bg-white text-sm sm:text-base">
                </div>
                <div class="border-b-3 border-gray-300 focus-within:border-green-700 rounded-lg auth-field">
                  <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Confirm Password"
                         class="w-full px-3 py-1 sm:py-3 outline-none bg-transparent rounded-lg focus:bg-white text-sm sm:text-base">
                </div>
              </div>
              <div class="flex items-center gap-2 mt-2">
                <input type="checkbox" id="showPasswords">
                <label for="showPasswords" class="text-xs sm:text-sm text-gray-600 kantumruy-pro-regular cursor-pointer select-none">Show passwords</label>
              </div>
              <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters with uppercase, lowercase, number, and special character (-_@$!%*#?&)</p>
              @error('password')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
              @enderror
              @error('password_confirmation')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm sm:text-base kantumruy-pro-bold font-semibold mb-2">Role</label>
                <div class="border-b-3 border-gray-300 focus-within:border-green-700 rounded-lg auth-field">
                  <select name="role" id="role" required
                          class="w-full px-3 py-1 sm:py-3 outline-none bg-transparent rounded-lg focus:bg-white text-sm sm:text-base">
                    <option value="">Select Role</option>
                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                    <option value="faculty" {{ old('role') == 'faculty' ? 'selected' : '' }}>Faculty</option>
                    <option value="librarian" {{ old('role') == 'librarian' ? 'selected' : '' }}>Librarian</option>
                    <!-- no admin po for security -->
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

            <!-- Student-specific fields -->
            <div id="studentFields" style="display: none;" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm sm:text-base kantumruy-pro-bold font-semibold mb-2">Course</label>
                <div class="border-b-3 border-gray-300 focus-within:border-green-700 rounded-lg auth-field">
                  <select name="course_id" id="course_id"
                          class="w-full px-3 py-1 sm:py-3 outline-none bg-transparent rounded-lg focus:bg-white text-sm sm:text-base">
                    <option value="">Select Course</option>
                    @foreach($courses ?? [] as $course)
                      <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                        {{ $course->code }} - {{ $course->name }}
                      </option>
                    @endforeach
                  </select>
                </div>
                @error('course_id')
                  <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="block text-sm sm:text-base kantumruy-pro-bold font-semibold mb-2">Student Number</label>
                <div class="border-b-3 border-gray-300 focus-within:border-green-700 rounded-lg auth-field">
                  <input type="text" name="student_number" id="student_number" value="{{ old('student_number') }}" 
                         placeholder="23-001" pattern="^23-\d{3,}$"
                         class="w-full px-3 py-1 sm:py-3 outline-none bg-transparent rounded-lg focus:bg-white text-sm sm:text-base">
                </div>
                <p class="text-xs text-gray-500 mt-1">Format: 23-XXX (e.g., 23-001)</p>
                @error('student_number')
                  <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
              </div>
            </div>

            <button type="submit"
                    class="w-full bg-green-800 text-white py-3 sm:py-4 rounded-md hover:bg-green-900 transition text-sm sm:text-base auth-submit">
              Register
            </button>
          </form>

          <!-- Footer -->
          <div class="mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-300">
            <div class="text-center space-y-2">
              <p class="text-[5px] sm:text-sm text-gray-600 kantumruy-pro-regular">
                &copy; {{ date('Y') }} ISU StudyGo. All rights reserved.
              </p>
              <p class="text-xs sm:text-sm text-gray-600 kantumruy-pro-regular">
                Need help? <a href="mailto:isustudygo@gmail.com" class="text-green-700 font-semibold hover:underline">Contact Support</a>
              </p>
            </div>
          </div>

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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  @include('partials.globalLoader')
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Toggle password visibility for both fields with checkbox
      const showPasswordsCheckbox = document.getElementById('showPasswords');
      const passwordInput = document.getElementById('password');
      const passwordConfirmInput = document.getElementById('password_confirmation');
      
      if (showPasswordsCheckbox && passwordInput && passwordConfirmInput) {
        showPasswordsCheckbox.addEventListener('change', function() {
          const type = this.checked ? 'text' : 'password';
          passwordInput.type = type;
          passwordConfirmInput.type = type;
        });
    }

    // Show/hide student fields based on role selection
      const roleSelect = document.getElementById('role');
      if (roleSelect) {
        roleSelect.addEventListener('change', function() {
      const studentFields = document.getElementById('studentFields');
      const courseSelect = document.getElementById('course_id');
      const studentNumberInput = document.getElementById('student_number');
      
      if (this.value === 'student') {
        studentFields.style.display = 'grid';
        courseSelect.required = true;
        studentNumberInput.required = true;
      } else {
        studentFields.style.display = 'none';
        courseSelect.required = false;
        studentNumberInput.required = false;
        courseSelect.value = '';
        studentNumberInput.value = '';
      }
    });
    
    // Trigger on page load if role is already selected
        if (roleSelect.value === 'student') {
          roleSelect.dispatchEvent(new Event('change'));
        }
    }

      // Real-time password validation feedback (reuse variables from above)
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
          const password = this.value;
          const passwordContainer = this.closest('.grid').parentElement;
          let hintElement = passwordContainer.querySelector('.password-strength-hint');
        
        // Remove existing hint
        if (hintElement) {
          hintElement.remove();
        }
        
        if (password.length > 0) {
          const requirements = {
            length: password.length >= 8,
            lowercase: /[a-z]/.test(password),
            uppercase: /[A-Z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[-_@$!%*#?&]/.test(password),
          };
          
          const missing = [];
          if (!requirements.length) missing.push('8 characters');
          if (!requirements.lowercase) missing.push('lowercase letter');
          if (!requirements.uppercase) missing.push('uppercase letter');
          if (!requirements.number) missing.push('number');
          if (!requirements.special) missing.push('special character');
          
          if (missing.length > 0) {
            const hint = document.createElement('p');
            hint.className = 'text-xs text-amber-600 mt-1 password-strength-hint';
            hint.textContent = 'Missing: ' + missing.join(', ');
              passwordContainer.appendChild(hint);
            }
          }
          
          // Check password match in real-time
          if (passwordConfirmInput && passwordConfirmInput.value.length > 0) {
            checkPasswordMatch();
        }
      });
    }
    
      // Real-time password match validation
      function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = passwordConfirmInput.value;
        const passwordContainer = passwordInput.closest('.grid').parentElement;
        let matchHint = passwordContainer.querySelector('.password-match-hint');
        
        if (confirmPassword.length > 0) {
          if (password !== confirmPassword) {
            if (!matchHint) {
              matchHint = document.createElement('p');
              matchHint.className = 'text-xs text-red-600 mt-1 password-match-hint';
              passwordContainer.appendChild(matchHint);
            }
            matchHint.textContent = 'Passwords do not match';
            passwordConfirmInput.setCustomValidity('Passwords do not match');
          } else if (password.length > 0) {
            if (!matchHint) {
              matchHint = document.createElement('p');
              matchHint.className = 'text-xs text-green-600 mt-1 password-match-hint';
              passwordContainer.appendChild(matchHint);
            }
            matchHint.textContent = 'Passwords match';
            passwordConfirmInput.setCustomValidity('');
          } else {
            if (matchHint) {
              matchHint.remove();
            }
            passwordConfirmInput.setCustomValidity('');
          }
        } else {
          if (matchHint) {
            matchHint.remove();
          }
          passwordConfirmInput.setCustomValidity('');
        }
      }
      
      if (passwordConfirmInput && passwordInput) {
        passwordConfirmInput.addEventListener('input', checkPasswordMatch);
      }
    });
  </script>
</body>
</html>
