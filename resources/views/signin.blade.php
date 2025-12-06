{{-- resources/views/signin.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Login - ISU StudyGo</title>
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
      background: rgba(243, 244, 246, 0.9);
      border: 1px solid transparent;
      transition: border-color 200ms ease, background 200ms ease, transform 200ms ease;
    }
    .auth-field:focus-within {
      border-color: rgba(34, 197, 94, 0.6);
      background: #fff;
      transform: translateY(-1px);
      box-shadow: 0 12px 30px rgba(34, 197, 94, 0.15);
    }
    .auth-field input {
      background: transparent;
    }
    .auth-submit {
      border-radius: 16px;
      font-weight: 600;
      letter-spacing: 0.02em;
      box-shadow: 0 20px 60px rgba(34, 197, 94, 0.35);
    }
    .auth-submit:focus-visible {
      outline: 2px solid rgba(34, 197, 94, 0.4);
      outline-offset: 4px;
    }
    @media (max-width: 1024px) {
      .parent {
        grid-template-columns: 1fr;
        padding-top: 4vw;
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
  </style>
</head>
<body class="bg-gray-100">
  <div class="parent p-4 sm:p-6 lg:pt-[5vw] pt-0 px-4 lg:pb-[2vw] sm:px-6 lg:px-[16vw] w-full min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-[#000000]">
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
        <img src="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}" alt="ISU Logo" class="w-16 h-16 lg:w-16 lg:h-16 translate-y-2.5 sm:w-40 sm:h-40 rounded-full shadow" />
      </div>

      <div class="relative z-10 flex-1 flex items-start justify-center px-4 sm:px-6 lg:px-16 pt-0 sm:pt-2">
        <div class="w-full max-w-md sm:max-w-lg lg:max-w-xl">
          <div class="mb-4 sm:mb-6 text-left">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold kulim-park-bold tracking-tight flex items-center gap-2">
              <span>Login to</span>
              <span class="text-green-700">
                <img src="{{ Vite::asset('resources/images/ISUStudyGo.svg') }}" alt="ISUStudyGo Logo" class="inline-block h-8 sm:h-10 lg:h-12 w-auto" />
              </span>
            </h1>
            <p class="text-gray-600 mt-2 kantumruy-pro-regular tracking-tight text-sm sm:text-base">Login using your email and password.</p>
          </div>

          <!-- ERROR MESSAGES -->
          @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
              {{ $errors->first() }}
            </div>
          @endif

          <!-- SUCCESS / INFO -->
          @if(session('status'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
              {{ session('status') }}
            </div>
          @endif

          <!-- ERROR SESSION -->
          @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
              {{ session('error') }}
            </div>
          @endif

          @if(session('status') === 'verification-success')
          <script>
              document.addEventListener('DOMContentLoaded', () => {
                  const toast = document.getElementById('toast');
                  toast.querySelector('h4').textContent = 'Email Verified!';
                  toast.querySelector('p').textContent = 'You can now log in.';
                  toast.classList.remove('hidden');
                  setTimeout(() => toast.classList.add('show'), 10);
                  setTimeout(() => {
                      toast.classList.remove('show');
                      setTimeout(() => toast.classList.add('hidden'), 400);
                  }, 5000);
              });
          </script>
          @endif
          <!-- TOAST (HIDDEN BY DEFAULT) -->
          <div id="toast" class="hidden">
            <div class="icon">
              <i class="fa-solid fa-clock"></i>
            </div>
            <div class="message">
              <h4>Account Pending Approval</h4>
              <p>Your account is awaiting admin approval. Please try again later.</p>
            </div>
            <button class="close" onclick="hideToast()">&times;</button>
          </div>


          <!-- LOGIN FORM -->
          <form method="POST" action="{{ route('login.post') }}" class="space-y-4 sm:space-y-6" id="login-form">
            @csrf
            <div>
              <label class="block text-sm sm:text-base kantumruy-pro-bold font-semibold mb-2">Email</label>
              <div class="border-b-3 border-gray-300 focus-within:border-green-700 rounded-lg auth-field">
                <input type="email" name="email" required placeholder="example@mail.com"
                       class="w-full px-3 py-1 sm:py-3 outline-none bg-[#D9D9D9] rounded-lg focus:bg-white text-sm sm:text-base">
              </div>
            </div>

            <div>
              <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 gap-2 sm:gap-0">
                <label class="block text-sm sm:text-base kantumruy-pro-bold font-semibold">Password</label>
                <a href="{{ route('password.request') }}" class="text-green-700 text-xs sm:text-sm hover:underline">Forgot password?</a>
              </div>
              <div class="border-b-3 border-gray-300 focus-within:border-green-700 rounded-lg auth-field">
                <input type="password" name="password" required placeholder="*********"
                       class="w-full px-2 py-1 sm:py-3 outline-none bg-[#D9D9D9] rounded-lg focus:bg-white text-sm sm:text-base">
              </div>
            </div>

            <button type="submit"
                    class="w-full bg-green-800 text-white py-3 sm:py-4 rounded-md hover:bg-green-900 transition text-sm sm:text-base auth-submit">
              Login
            </button>
          </form>

          @if (Route::has('register'))
            <p class="text-center kantumruy-pro-regular tracking-tight text-gray-600 mt-4 sm:mt-6 text-sm sm:text-base">
              No account yet?
              <a href="{{ route('register') }}" class="text-green-700 font-semibold hover:underline">Register here!</a>
            </p>
          @endif

          <!-- Footer -->
          <div class="mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-300">
            <div class="text-center space-y-2">
              <p class="text-xs sm:text-sm text-gray-600 kantumruy-pro-regular">
                &copy; {{ date('Y') }} ISU StudyGo. All rights reserved.
              </p>
              <p class="text-xs sm:text-sm text-gray-600 kantumruy-pro-regular">
                Need help? <a href="mailto:isustudygo@gmail.com" class="text-green-700 font-semibold hover:underline">Contact Support</a>
              </p>
            </div>
          </div>
        </div>
      </div>

      <img src="{{ Vite::asset('resources/images/wave.png') }}" alt="Wave"
           class="absolute bottom-0 left-0 right-0 w-full h-20 sm:h-24 lg:rounded-br-4xl lg:h-auto object-fill pointer-events-none">
    </div>
  </div>

  <!-- FONT AWESOME FOR ICON -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>


  <!-- TOAST SCRIPT -->
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    @if(session('status') === 'verification-success')
      showToast('Email Verified!', 'You can now log in.', '#DCFCE7', '#16A34A');
    @elseif(session('status') === 'pending-approval')
      showToast('Account Pending Approval', 'Your account is awaiting admin approval. You will be notified once approved.', '#FEF3C7', '#D97706');
    @elseif(session('error') === 'Your account is pending admin approval.')
      showToast('Account Pending Approval', 'Your account is awaiting admin approval. Please try again later.', '#FEF3C7', '#D97706');
    @elseif(session('status') === 'password-reset-success')
      showToast('Password Updated', 'Your password has been reset successfully. Please log in with your new credentials.', '#DCFCE7', '#16A34A');
    @elseif(session('error'))
      showToast('Access Denied', {!! json_encode(session('error')) !!}, '#FEE2E2', '#DC2626');
    @endif
  });

  function showToast(title, message, bgColor = '#DCFCE7', iconColor = '#16A34A') {
    const toast = document.getElementById('toast');
    const icon = toast.querySelector('.icon');
    
    // Update content
    toast.querySelector('h4').textContent = title;
    toast.querySelector('p').textContent = message;
    
    // Update colors
    icon.style.background = bgColor;
    icon.querySelector('i').style.color = iconColor;
    
    // Show toast
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('show'), 10);
    
    // Auto-hide after 6 seconds
    setTimeout(hideToast, 6000);
  }

  function hideToast() {
    const toast = document.getElementById('toast');
    toast.classList.remove('show');
    setTimeout(() => toast.classList.add('hidden'), 400);
  }

  // Handle CSRF token refresh and 419 errors
  document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    
    if (loginForm && csrfToken) {
      // Update CSRF token in form before submission
      loginForm.addEventListener('submit', function(e) {
        const tokenInput = this.querySelector('input[name="_token"]');
        if (tokenInput && csrfToken) {
          tokenInput.value = csrfToken.getAttribute('content');
        }
      });

      // Handle 419 errors by refreshing the page
      window.addEventListener('unhandledrejection', function(event) {
        if (event.reason && event.reason.status === 419) {
          location.reload();
        }
      });
    }
  });
</script>
  @include('partials.globalLoader')
</body>
</html>