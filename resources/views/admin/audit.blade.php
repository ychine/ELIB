<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap&family=Kulim+Park:ital,wght@0,200;0,300;0,400;0,600;0,700;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @vite(['resources/css/app.css', 'resources/css/output.css', 'resources/css/kulimpark.css', 'resources/css/Inter.css', 'resources/css/kantumruypro.css'])

  <title>Audit Trail | ISU StudyGo</title>

  <style>
    html, body, .main-content { overflow-x: hidden; }

    .sidebar {
      width: 4rem;
      transition: all 0.3s cubic-bezier(0.215, 0.610, 0.355, 1);
      overflow: hidden;
      padding-left: 0.5rem;
      padding-right: 0.5rem;
      cursor: pointer;
    }
    .sidebar.expanded { width: 18rem; }
    .sidebar .label {
      opacity: 0;
      transform: translateX(-10px);
      transition: all 0.3s ease;
      white-space: nowrap;
      padding-left: 1rem;
    }
    .sidebar.expanded .label { opacity: 1; transform: translateX(0); }
    .isu-studygo-border-logo { opacity: 0; transition: opacity 0.3s ease; }
    .isu-studygo-logo { opacity: 1; transition: opacity 0.3s ease; }
    .sidebar.expanded .isu-studygo-border-logo { opacity: 1; }
    .sidebar.expanded .isu-studygo-logo { opacity: 0; }
    .sidebar-content { display: flex; flex-direction: column; align-items: center; transition: all 0.3s ease; min-height: 100%; }
    .sidebar-icons { transform: translate(35%, 5%); transition: transform 0.3s ease; }
    .sidebar.expanded .sidebar-icons { transform: translateX(20px); }
    .sidebar.expanded .sidebar-content { align-items: flex-start; padding-left: 0.5rem; padding-right: 0.5rem; }
    .sidebar.expanded + .main-content { margin-left: 15rem; margin-top: 0; }

    .glass-nav {
      background: transparent;
      transition: all 0.3s ease;
    }
    .glass-nav.scrolled {
      background: linear-gradient(rgba(4, 30, 10, 0.9), rgba(4, 30, 10, 0.7), rgba(4, 30, 10, 0.5), rgba(255, 255, 255, 0.0));
      background-position: 50% center;
      background-size: cover;
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
    }

    @supports not (backdrop-filter: blur(16px)) {
      .glass-nav.scrolled {
        background: linear-gradient(rgba(34, 197, 94, 0.2), rgba(34, 197, 94, 0.2)), url('{{ Vite::asset('resources/images/library.jpg') }}');
        background-position: 50% center;
        background-size: cover;
      }
    }

    .glass-nav.scrolled::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-position: 50% center;
      background-size: cover;
      filter: blur(8px) !important;
      -webkit-filter: blur(8px) !important;
      z-index: -1;
    }

    .glass-nav .nav-item {
      visibility: hidden;
      transition: visibility 0.3s ease;
    }
    .glass-nav.scrolled .nav-item { visibility: visible; }

    .searchbar {
      background: rgba(217, 217, 217, 1);
      color: #000;
      transition: all 0.3s ease;
    }
  </style>
</head>
<body class="bg-gray-50">
  <div class="w-full h-[100vh] flex">

    <!-- Navigation -->
    <div class="fixed w-full top-0 left-0 flex justify-between items-center px-4 py-2 z-10 glass-nav">
      <span class="text-5xl jersey-20-regular pl-3 text-white"></span>
      <div class="relative flex items-center">
        <input class="searchbar pl-7 pr-10 sm:w-[545px] h-11 rounded-[34px] shadow-[inset_0px_4px_4px_rgba(0,0,0,0.25)]" type="text" placeholder="Search for books, papers..">
        <img 
          src="{{ Vite::asset('resources/images/Search.png') }}" 
          alt="Search icon" 
          class="absolute right-5 w-6 h-6"
        />
      </div>
      <div class="text-md flex space-x-4 gap-5 pr-6 plus-jakarta-sans-semibold text-white">
      </div>
    </div>
    
    @include('partials.universalSidebar')

    <div class="flex bg-gray-200 flex-col flex-1 transition-all duration-300 main-content">
      <div class="hero-container relative w-full greenhue z-1">
        <img 
          src="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}" 
          alt="ISU Logo" 
          class="absolute right-0 w-15 h-15 m-7"
        />
        <h5 class="absolute text-white right-0 m-7 mr-10 translate-y-30 kulim-park-semibold">One ISU</h5>
        <img 
          src="{{ Vite::asset('resources/images/libgreenptr.jpg') }}" 
          alt="Library" 
          class="w-full h-50 z-[-1] object-cover absolute"
          style="object-position: 70% middle;"
        />
        <div class="herotext h-50 ml-30 flex relative z-2">
          <div class="column">
            <h1 style="transform: translateY(50%); line-height: 86.402%; font-family: 'Kulim Park', sans-serif; font-weight: 600; letter-spacing: -1.3px; font-size: 45px; text-shadow: 0 4px 4px #000; color: #FFF;">
              Bridging knowledge <br>
              from one campus <br>
              to another
            </h1>
          </div>
        </div>
        <div class="homediv lg:mx-[10%] mt-5 rounded-md">
          <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold kulim-park-bold tracking-tight mb-4">Audit Trail</h2>
        </div>
        <div class="homediv lg:mx-[10%] rounded-2xl bg-white shadow-lg p-6">
          <p class="text-gray-600">Audit trail logging system will be implemented here.</p>
          @if(empty($auditLogs))
            <p class="text-gray-500 mt-4">No audit logs available yet.</p>
          @else
            <table class="w-full kantumruy-pro-regular tracking-tight bg-white shadow rounded-lg mt-4">
              <thead>
                <tr class="bg-gray-200">
                  <th class="p-3 text-left">Date</th>
                  <th class="p-3 text-left">User</th>
                  <th class="p-3 text-left">Action</th>
                  <th class="p-3 text-left">Details</th>
                </tr>
              </thead>
              <tbody>
                @foreach($auditLogs as $log)
                  <tr>
                    <td class="p-3">{{ $log->created_at ?? 'N/A' }}</td>
                    <td class="p-3">{{ $log->user ?? 'N/A' }}</td>
                    <td class="p-3">{{ $log->action ?? 'N/A' }}</td>
                    <td class="p-3">{{ $log->details ?? 'N/A' }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          @endif
        </div>
      </div>
    </div>
  </div>

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

  <script>
    function initGlassNavScroll() {
      const nav = document.querySelector('.glass-nav');
      if (!nav) return;

      // Find the library green image specifically
      const heroImage = document.querySelector('.hero-container img[src*="libgreenptr"]');
      const heroContainer = document.querySelector('.hero-container');

      const updateNavBlur = () => {
        if (!heroImage && !heroContainer) {
          nav.classList.add('scrolled');
          return;
        }

        // Use the image if available, otherwise use the container
        const reference = heroImage || heroContainer;
        const rect = reference.getBoundingClientRect();
        const tolerance = nav.offsetHeight + 16;

        // Add scrolled class when scrolled past the image/container
        if (rect.bottom <= tolerance) {
          nav.classList.add('scrolled');
        } else {
          nav.classList.remove('scrolled');
        }
      };

      window.addEventListener('load', updateNavBlur, { once: true });
      window.addEventListener('scroll', updateNavBlur, { passive: true });
      window.addEventListener('resize', updateNavBlur);

      if (heroImage instanceof HTMLImageElement && !heroImage.complete) {
        heroImage.addEventListener('load', updateNavBlur, { once: true });
      }

      updateNavBlur();
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initGlassNavScroll);
    } else {
      initGlassNavScroll();
    }
  </script>

  @include('partials.globalLoader')
</body>
</html>

