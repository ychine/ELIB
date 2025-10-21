<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - ISU StudyGo</title>
  <link rel="icon" href="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Kulim+Park:ital,wght@0,200;0,300;0,400;0,600;0,700;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
  @vite('resources/css/app.css')
  @vite('resources/css/kulimpark.css')  
  @vite('resources/css/Inter.css')

  <style>
    .parent {
      min-height: 100vh; /* Changed from h-screen */
    }
  </style>
</head>
<body class="bg-gray-100">
  <div class="parent p-4 sm:p-6 lg:p-10 px-4 sm:px-6 lg:px-[14vw] w-full min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-[#000000]">
    <span class="absolute top-1/2 left-1/2 w-[60%] h-[90%] -translate-x-1/2 -translate-y-1/2 bg-green-400 rounded-full blur-[214.3px]"></span>

    <div class="relative hidden lg:block">
     <div class="absolute inset-0 shadow-[14px_4px_32.5px_0_rgba(0,0,0,0.45)] z-[5] pointer-events-none">
        <img 
        src="{{ Vite::asset('resources/images/librarygreen.jpg') }}" 
        alt="Library" 
        class="w-full rounded-l-4xl h-full object-cover" 
        style="background: lightgray 0px 0.061px / 214.356% 94.17% no-repeat;"
        >
    </div>
    <!-- hero -->
     
     <div class="absolute inset-0 rounded-l-4xl p-10 lg:p-16 flex items-start z-10">
        <div class="text-white drop-shadow-lg max-w-xl">
          <h2 style="color:#FFF;text-shadow:0 4px 4px #000;font-family:'Kulim Park',sans-serif;font-size:clamp(2rem, 4.5vw, 81px);font-style:normal;font-weight:700;line-height:86.402%;letter-spacing:-4.05px;white-space:nowrap;">Bridging</h2>
          <h2 style="color:#FFF;text-shadow:0 4px 4px #000;font-family:'Kulim Park',sans-serif;font-size:clamp(2rem, 4.5vw, 81px);font-style:normal;font-weight:700;line-height:86.402%;letter-spacing:-4.05px;white-space:nowrap;">knowledge from</h2>
          <h2 style="color:#FFF;text-shadow:0 4px 4px #000;font-family:'Kulim Park',sans-serif;font-size:clamp(2rem, 4.5vw, 81px);font-style:normal;font-weight:700;line-height:86.402%;letter-spacing:-4.05px;white-space:nowrap;">one campus to</h2>
          <h2 style="color:#FFF;text-shadow:0 4px 4px #000;font-family:'Kulim Park',sans-serif;font-size:clamp(2rem, 4.5vw, 81px);font-style:normal;font-weight:700;line-height:86.402%;letter-spacing:-4.05px;white-space:nowrap;">another</h2>
        <p class="mt-10 font-semibold text-lg sm:text-xl">One ISU</p>
        </div>
    </div>
    
    </div>

    <div class="relative flex flex-col h-full lg:rounded-r-4xl bg-gray-100">
      <div class="relative z-10 flex items-center justify-between px-4 sm:px-6 lg:px-12 pt-4 sm:pt-6">
        <a href="{{ url('/') }}" class="inline-flex items-center text-green-700 hover:text-green-800 text-sm sm:text-base">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5 mr-2"><path fill-rule="evenodd" d="M9.53 4.47a.75.75 0 010 1.06L5.06 10h15.19a.75.75 0 010 1.5H5.06l4.47 4.47a.75.75 0 11-1.06 1.06l-5.75-5.75a.75.75 0 010-1.06l5.75-5.75a.75.75 0 011.06 0z" clip-rule="evenodd"/></svg>
          Cancel
        </a>
        <img src="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}" alt="ISU Logo" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full shadow"/>
      </div>

      <div class="relative z-10 flex-1 flex items-start justify-center px-4 sm:px-6 lg:px-16 pt-4 sm:pt-6 lg:pt-10">
        <div class="w-full max-w-md sm:max-w-lg lg:max-w-xl">
          <div class="mb-4 sm:mb-6 text-left">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold">Login to <span class="text-green-700">ISU</span><span class="align-top text-sm sm:text-base lg:text-lg font-bold">StudyGo</span></h1>
            <p class="text-gray-600 mt-2 text-sm sm:text-base">Login using your email and password.</p>
          </div>

          @if($errors->any())
            <div class="bg-red-100 text-red-700 p-2 sm:p-3 rounded mb-4 text-sm">
              {{ $errors->first() }}
            </div>
          @endif

          <form method="POST" action="/signin" class="space-y-4 sm:space-y-6">
            @csrf
            <div>
              <label class="block text-sm sm:text-base font-semibold mb-2">Email</label>
              <div class="border-b-2 border-gray-300 focus-within:border-green-700 rounded-sm">
                <input type="email" name="email" required placeholder="Enter your email"
                       class="w-full px-3 py-3 sm:py-4 outline-none bg-gray-100 rounded-md focus:bg-white text-sm sm:text-base">
              </div>
            </div>

            <div>
              <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 gap-2 sm:gap-0">
                <label class="block text-sm sm:text-base font-semibold">Password</label>
                <a href="#" class="text-green-700 text-xs sm:text-sm hover:underline">Forgot password?</a>
              </div>
              <div class="border-b-2 border-gray-300 focus-within:border-green-700 rounded-sm">
                <input type="password" name="password" required placeholder="Enter your password"
                       class="w-full px-3 py-3 sm:py-4 outline-none bg-gray-100 rounded-md focus:bg-white text-sm sm:text-base">
              </div>
            </div>

            <button type="submit"
                    class="w-full bg-green-800 text-white py-3 sm:py-4 rounded-md hover:bg-green-900 transition text-sm sm:text-base">Login</button>
          </form>

          @if (Route::has('register'))
          <p class="text-center text-gray-600 mt-4 sm:mt-6 text-sm sm:text-base">No account yet?
            <a href="{{ route('register') }}" class="text-green-700 font-semibold hover:underline">Register here!</a>
          </p>
          @endif
        </div>
      </div>

      <img 
        src="{{ Vite::asset('resources/images/wave.png') }}" 
        alt="Wave"
        class="absolute bottom-0 left-0 right-0 w-full h-20 sm:h-24 lg:rounded-br-4xl lg:h-auto object-fill pointer-events-none"
      />
    </div>
  </div>
</body>
</html>