<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - ISU StudyGo</title>
  <link rel="icon" href="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap&family=Kulim+Park:ital,wght@0,200;0,300;0,400;0,600;0,700;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
  @vite('resources/css/app.css')
  @vite('resources/css/kulimpark.css')  
  @vite('resources/css/Inter.css')
  @vite('resources/css/kantumruypro.css')
  <style>
    .parent {
      min-height: 100vh; 
    }
  </style>
</head>
<body class="bg-gray-100">
  <div class="parent p-4 sm:p-6 lg:pt-[5vw] px-4 lg:pb-[2vw]  sm:px-6 lg:px-[16vw] w-full min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-[#000000]">
    <img 
        src="{{ Vite::asset('resources/images/ISUStudyGoBorder.svg') }}" 
        alt="ISUStudyGo Logo" 
        class="absolute   left-1/2 -translate-x-1/2 top-10  lg:w-[5%] z-10" 
    >
    <span class="absolute top-1/2 left-1/2 w-[70vw] h-[30vw] -translate-x-1/2 -translate-y-1/2 bg-[#63F068] rounded-full blur-[50vw]"></span>
    
    <div class="relative shadow-[0_8px_24px_rgba(0,0,0,0.9)] z-10 hidden lg:block p-4 overflow-visible rounded-l-4xl">
      <div class="absolute inset-0 z-[5] pointer-events-none">
        <img 
          src="{{ Vite::asset('resources/images/librarygreen.jpg') }}" 
          alt="Library" 
          class="w-full rounded-l-4xl h-full object-cover" 
          style="background: lightgray 0px 0.061px / 214.356% 94.17% no-repeat;"
        >
      </div>
      <div class="absolute inset-0 rounded-l-4xl p-10 lg:p-16 z-10">
        <div class="text-white drop-shadow-lg max-w-xl">
          <h2 style="color:#FFF;text-shadow:0 4px 4px #000;font-family:'Kulim Park',sans-serif;font-size:4.1vw;font-style:normal;font-weight:700;line-height:86.402%;letter-spacing:-4.05px;white-space:nowrap;">Bridging</h2>
          <h2 style="color:#FFF;text-shadow:0 4px 4px #000;font-family:'Kulim Park',sans-serif;font-size:4.1vw;font-style:normal;font-weight:700;line-height:86.402%;letter-spacing:-4.05px;white-space:nowrap;">knowledge from</h2>
          <h2 style="color:#FFF;text-shadow:0 4px 4px #000;font-family:'Kulim Park',sans-serif;font-size:4.1vw;font-style:normal;font-weight:700;line-height:86.402%;letter-spacing:-4.05px;white-space:nowrap;">one campus to</h2>
          <h2 style="color:#FFF;text-shadow:0 4px 4px #000;font-family:'Kulim Park',sans-serif;font-size:4.1vw;font-style:normal;font-weight:700;line-height:86.402%;letter-spacing:-4.05px;white-space:nowrap;">another</h2>
        </div>
        <p class="absolute kulim-park-light bottom-10 lg:bottom-16 font-semibold text-lg sm:text-xl text-white">One ISU</p>
      </div>
    </div>

    <div class="relative flex flex-col h-full lg:rounded-r-4xl bg-gray-100 z-1">
      <div class="relative z-10 flex items-center justify-between px-4 sm:px-6 lg:px-12 pt-4 sm:pt-6">
        <div class="flex-shrink-0">
        
        </div>
        <img src="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}" alt="ISU Logo" class="w-[3vw] h-[3vw] translate-y-2.5 sm:w-12 sm:h-12 rounded-full shadow" />
      </div>

      <div class="relative z-10 flex-1 flex items-start justify-center px-4 sm:px-6 lg:px-16 pt-4 sm:pt-6 lg:pt-10">
        <div class="w-full max-w-md sm:max-w-lg lg:max-w-xl">
          <div class="mb-4 sm:mb-6 text-left">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold kulim-park-bold tracking-tight flex items-center gap-2">
              <span>Login to</span>
              <span class="text-green-700">
                <img src="{{ Vite::asset('resources/images/ISUStudyGo.svg') }}" alt="ISUStudyGo Logo" class="inline-block  h-8 sm:h-10 lg:h-12 w-auto" />
              </span>
            </h1>
            <p class="text-gray-600 mt-2 kantumruy-pro-regular tracking-tight text-sm sm:text-base">Login using your email and password.</p>
          </div>

          @if($errors->any())
            <div class="bg-red-100 text-red-700 p-2 sm:p-3 rounded mb-4 text-sm">
              {{ $errors->first() }}
            </div>
          @endif

      <form method="POST" action="/signin" class="space-y-4 sm:space-y-6">
        @csrf
        <div>
          <label class="block text-sm sm:text-base kantumruy-pro-bold font-semibold mb-2">Email</label>
          <div class="border-b-3 border-gray-300 focus-within:border-green-700 rounded-lg">
            <input type="email" name="email" required placeholder="Enter your email"
                   class="w-full px-3 py-1 sm:py-3 outline-none bg-[#D9D9D9] rounded-lg focus:bg-white text-sm sm:text-base">
          </div>
        </div>

        <div>
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 gap-2 sm:gap-0">
            <label class="block text-sm sm:text-base kantumruy-pro-bold  font-semibold">Password</label>
            <a href="#" class="text-green-700 text-xs sm:text-sm hover:underline">Forgot password?</a>
          </div>
          <div class="border-b-3 border-gray-300 focus-within:border-green-700 rounded-lg">
            <input type="password" name="password" required placeholder="Enter your password"
                   class="w-full px-2 py-1 sm:py-3 outline-none bg-[#D9D9D9] rounded-lg focus:bg-white text-sm sm:text-base">
          </div>
        </div>

        <button type="submit"
                class="w-full bg-green-800 text-white py-3 sm:py-4 rounded-md hover:bg-green-900 transition text-sm sm:text-base">Login</button>
      </form>

      @if (Route::has('register'))
        <p class="text-center kantumruy-pro-regular tracking-tight  text-gray-600 mt-4 sm:mt-6 text-sm sm:text-base">No account yet?
          <a href="{{ route('register') }}" class="kantumruy-pro-regular tracking-tight  text-green-700 font-semibold hover:underline">Register here!</a>
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