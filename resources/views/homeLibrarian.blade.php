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

  @vite(['resources/css/app.css', 'resources/css/output.css', 'resources/css/Inter.css', 'resources/css/kulimpark.css', 'resources/css/kantumruypro.css'])

  <title>Librarian Dashboard | ISU StudyGo</title>

  <style>
    /* Sidebar Styles */
    .sidebar {
      width: 4rem;
      transition: all 0.3s cubic-bezier(0.215, 0.610, 0.355, 1);
      overflow: hidden;
      padding-left: 0.5rem;
      padding-right: 0.5rem;
      cursor: pointer;
    }

    .sidebar.expanded {
      width: 18rem;
    }

    .sidebar .label {
      opacity: 0;
      transform: translateX(-10px);
      transition: all 0.3s ease;
      white-space: nowrap;
      padding-left: 1rem;
    }

    .sidebar.expanded .label {
      opacity: 1;
      transform: translateX(0);
    }

    .isu-studygo-border-logo {
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .isu-studygo-logo {
      opacity: 1;
      transition: opacity 0.3s ease;
    }

    .sidebar.expanded .isu-studygo-border-logo {
      opacity: 1;
    }

    .sidebar.expanded .isu-studygo-logo {
      opacity: 0;
    }

    .sidebar-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: all 0.3s ease;
      min-height: 100%;
    }

    .sidebar-icons {
      transform: translate(35%, 5%);
      transition: transform 0.3s ease;
    }

    .sidebar.expanded .sidebar-icons {
      transform: translateX(20px);
    }

    .sidebar.expanded .sidebar-content {
      align-items: flex-start;
      padding-left: 0.5rem;
      padding-right: 0.5rem;
    }

    .sidebar.expanded + .main-content {
      margin-left: 15rem;
      margin-top: 0;
    }


    .sidebar .cursor-pointer {
      cursor: pointer;
    }

    .searchbar:focus {
      outline: none;
      box-shadow: 
        0 0 0 3px rgba(34, 197, 94, 0.5), 
        0 0 10px 3px rgba(0, 0, 0, 0.5);
      transition: all 0.3s ease-in-out;
    }

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

    .glass-nav.scrolled .nav-item {
      visibility: visible;
    }

    .searchbar {
      background: rgba(217, 217, 217, 1); 
      color: #000; 
      transition: all 0.3s ease;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
      .sidebar {
        display: none;
      }

      .bottom-nav {
        display: flex;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 4rem;
        background: #149637;
        z-index: 20;
        flex-direction: row;
        justify-content: space-around;
        align-items: center;
        box-shadow: 0 -5px 10px rgba(0, 0, 0, 0.3);
      }

      .bottom-nav .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex: 1;
        height: 100%;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: background 0.3s ease;
      }

      .bottom-nav .nav-item:hover {
        background: rgba(255, 255, 255, 0.1);
      }

      .bottom-nav .nav-item img {
        width: 1.5rem;
        height: 1.5rem;
      }

      .bottom-nav .nav-item span {
        color: white;
        font-size: 0.75rem;
        font-family: 'Kulim Park', sans-serif;
        margin-top: 0.25rem;
      }

      .main-content {
        padding-bottom: 4.5rem;
      }

      .glass-nav {
        padding: 0.5rem 1rem;
      }

      .glass-nav .searchbar {
        width: 100%;
        max-width: 300px;
      }

      .glass-nav .text-md {
        display: none;
      }

      .herotext h1 {
        font-size: 2rem;
        line-height: 1.2;
        transform: translateY(50%);
        margin-left: 1rem;
      }

      .homediv {
        margin: 1rem;
        margin-left: 1rem;
      }

      .homediv p {
        margin: 1rem;
        line-height: 1.5;
      }
    }

    @media (min-width: 769px) {
      .bottom-nav {
        display: none;
      }
      .sidebar {
        display: block;
      }
    }
  </style>
</head>
<body class="bg-yellow-50">
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
        <!-- Role badge removed -->
      </div>
    </div>

    <!-- Universal Sidebar -->
    @include('partials.universalSidebar')

    <!-- Bottom Navigation for Small Screens -->
    <div class="bottom-nav">
      <a href="{{ route('home.librarian') }}" class="nav-item">
        <img src="{{ Vite::asset('resources/images/DashboardToggled.png') }}" alt="Dashboard" class="w-7 h-7 sidebar-icons"/>
          <span class="label kulim-park-regular text-lg">Dashboard</span>
      </a>
      <a href="{{ route('featured') }}" class="nav-item">
        <img src="{{ Vite::asset('resources/images/Featured.png') }}" alt="Featured" />
        <span>Featured</span>
      </a>
      <a href="{{ route('community.uploads') }}" class="nav-item">
        <img src="{{ Vite::asset('resources/images/Member.png') }}" alt="Community Uploads" />
        <span>Community</span>
      </a>
      <a href="{{ route('your.shelf') }}" class="nav-item">
        <img src="{{ Vite::asset('resources/images/Book Shelf.png') }}" alt="Your Shelf" />
        <span>Shelf</span>
      </a>
      <form method="POST" action="{{ route('logout') }}" class="nav-item">
        @csrf
        <button type="submit" class="flex flex-col items-center justify-center w-full h-full bg-transparent border-none">
          <i class="fa-solid fa-sign-out-alt text-xl text-white"></i>
          <span>Logout</span>
        </button>
      </form>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col flex-1 transition-all duration-300 main-content bg-gray-200">
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
          <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold kulim-park-bold tracking-tight mb-4">Dashboard</h2>
        </div>
        <div class="homediv lg:mx-[10%] rounded-2xl bg-white shadow-lg p-6">

        </div>
       
      </div>
    </div>
  </div>

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

  <!-- Scroll Effect for Navbar -->
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

  <script>
    const sidebar = document.querySelector('.sidebar');

    if (sidebar) {
      sidebar.addEventListener('click', (event) => {
        if (event.target.closest('.sidebar a, .sidebar button, .sidebar form')) return;
        sidebar.classList.toggle('expanded');
      });

      document.addEventListener('click', (event) => {
        if (sidebar.classList.contains('expanded') && !sidebar.contains(event.target)) {
          sidebar.classList.remove('expanded');
        }
      });
    }
  </script>
  @include('partials.globalLoader')
</body>
</html>