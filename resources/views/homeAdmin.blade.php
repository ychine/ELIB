<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap&family=Kulim+Park:ital,wght@0,200;0,300;0,400;0,600;0,700;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/css/kulimpark.css', 'resources/css/Inter.css', 'resources/css/kantumruypro.css'])

  <title>Admin Dashboard | ISU StudyGo</title>

  <style>
    /* ---------- Sidebar (click-to-toggle) ---------- */
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
    .isu-studygo-logo       { opacity: 1; transition: opacity 0.3s ease; }
    .sidebar.expanded .isu-studygo-border-logo { opacity: 1; }
    .sidebar.expanded .isu-studygo-logo       { opacity: 0; }

    .sidebar-content { display: flex; flex-direction: column; align-items: center; min-height: 100%; transition: all 0.3s ease; }
    .sidebar-icons   { transform: translate(35%, 5%); transition: transform 0.3s ease; }
    .sidebar.expanded .sidebar-icons { transform: translateX(20px); }
    .sidebar.expanded .sidebar-content { align-items: flex-start; padding-left: 0.5rem; padding-right: 0.5rem; }

    .sidebar.expanded + .main-content { margin-left: 15rem; margin-top: 0; }


    .isu-studygo-border-logo {
      opacity: 0;
    }

    .isu-studygo-logo {
      opacity: 1;
      transition: all 0.3s ease;
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
     
      </div>
    </div>
    
    <!-- Sidebar -->
    <div class="fixed top-0 left-0 h-full bg-[#149637] shadow-[5px_-10px_22.5px_2px_rgba(0,0,0,0.59)] rounded-tr-[50px] sidebar z-20 pt-8">
      <div class="sidebar-content space-y-2 text-white">
        <!-- Logo -->
        <img src="{{ Vite::asset('resources/images/ISUStudyGoBorder.svg') }}" alt="Logo" class="w-full h-20 isu-studygo-border-logo"/>
        <img src="{{ Vite::asset('resources/images/ISUclpsd.svg') }}" alt="Logo" class="w-full h-10 translate-y-[20px] absolute isu-studygo-logo"/>

        <!-- Dashboard (Current Page) -->
        <a href="{{ route('admin.approvals') }}" class="w-full h-12 bg-green-500 rounded-xl flex items-center gap-3 cursor-pointer">
          <img src="{{ Vite::asset('resources/images/DashboardToggled.png') }}" alt="Dashboard" class="w-7 h-7 sidebar-icons"/>
          <span class="label kulim-park-regular text-lg">Dashboard</span>
        </a>

        <!-- User Management -->
        <a href="{{ route('admin.users') }}" class="w-full h-12 bg-green-800 rounded-xl shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)] flex items-center gap-3 cursor-pointer hover:bg-green-700 transition-colors">
          <img src="{{ Vite::asset('resources/images/umgmt.png') }}" alt="User Management" class="w-7 h-7 sidebar-icons"/>
          <span class="label kulim-park-regular text-lg">User Management</span>
        </a>

        <!-- Featured -->
        <a href="#" class="w-full h-12 bg-green-800 rounded-xl shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)] flex items-center gap-3 cursor-pointer hover:bg-green-700 transition-colors">
          <img src="{{ Vite::asset('resources/images/Featured.png') }}" alt="Featured" class="w-7 h-7 translate-y-[-1px] translate-x-[1px] sidebar-icons"/>
          <span class="label kulim-park-regular text-lg">Featured</span>
        </a>

        <!-- Community Uploads -->
        <a href="#" class="w-full h-12 bg-green-800 rounded-xl shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)] flex items-center gap-3 cursor-pointer hover:bg-green-700 transition-colors">
          <img src="{{ Vite::asset('resources/images/Member.png') }}" alt="Community" class="w-7 h-7 sidebar-icons"/>
          <span class="label kulim-park-regular text-lg">Community Uploads</span>
        </a>

        <!-- Your Shelf -->
        <a href="#" class="w-full h-12 bg-green-800 rounded-xl shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)] flex items-center gap-3 cursor-pointer hover:bg-green-700 transition-colors">
          <img src="{{ Vite::asset('resources/images/Book Shelf.png') }}" alt="Shelf" class="w-7 h-7 sidebar-icons"/>
          <span class="label kulim-park-regular text-lg">Your Shelf</span>
        </a>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="mt-auto w-full h-12 bg-green-800 rounded-xl shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)] flex items-center gap-3">
          @csrf
          <button type="submit" class="flex items-center gap-3 w-full h-full bg-transparent border-none text-white cursor-pointer hover:bg-red-700 transition-colors rounded-xl">
            <i class="fa-solid fa-sign-out-alt text-2xl sidebar-icons"></i>
            <span class="label text-lg">Logout</span>
          </button>
        </form>
      </div>
    </div>

    <div class="flex bg-gray-200 flex-col flex-1 transition-all duration-300 main-content">
      <!-- Container for the hero section -->
      <div class="hero-container relative w-full greenhue z-1">
        <img 
          src="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}" 
          alt="ISU Logo" 
          class="absolute right-0 w-15 h-15 m-7"
          style=""
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
          <h2 class="text-1xl sm:text-2xl lg:text-3xl font-extrabold kulim-park-bold tracking-tight mb-6">Pending User Approvals</h2>

          @if(session('status'))
            <div class="bg-green-100 text-green-700 p-3  rounded mb-4 text-sm">
              {{ session('status') }}
            </div>
          @endif

          @if($users->isEmpty())
            <p class="text-gray-600">No pending approvals.</p>
          @else
            <table class="w-full  kantumruy-pro-regular tracking-tight bg-white shadow rounded-lg">
              <thead>
                <tr class="bg-gray-200">
                  <th class="p-3 text-left">Name</th>
                  <th class="p-3 text-left">Email</th>
                  <th class="p-3 text-left">Role</th>
                  <th class="p-3 text-left">Campus</th>
                  <th class="p-3 text-left">Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($users as $user)
                  <tr>
                    <td class="p-3">
                      @if($user->faculty)
                        {{ $user->faculty->First_Name }} {{ $user->faculty->Last_Name }}
                      @elseif($user->librarian)
                        {{ $user->librarian->First_Name }} {{ $user->librarian->Last_Name }}
                      @else
                        N/A
                      @endif
                    </td>
                    <td class="p-3">{{ $user->email }}</td>
                    <td class="p-3">{{ ucfirst($user->role) }}</td>
                    <td class="p-3">{{ $user->campus ? $user->campus->Campus_Name : 'N/A' }}</td>
                    <td class="p-3">
                      <form action="{{ route('admin.approve', $user->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-700 text-white px-3 py-1 rounded hover:bg-green-800">Approve</button>
                      </form>
                      <form action="{{ route('admin.reject', $user->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-700 text-white px-3 py-1 rounded hover:bg-red-800">Reject</button>
                      </form>
                    </td>
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
    window.addEventListener('scroll', () => {
      const nav = document.querySelector('.glass-nav');
      const libraryImg = document.querySelector('.main-content img');
      const libraryHeight = libraryImg.offsetHeight; 
      const scrollPosition = window.scrollY;

      if (scrollPosition > libraryHeight) {
        nav.classList.add('scrolled');
      } else {
        nav.classList.remove('scrolled');
      }
    });
  </script>

  <!-- Sidebar Click Toggle -->
  <script>
    const sidebar = document.querySelector('.sidebar');
    const items = document.querySelectorAll('.sidebar .cursor-pointer, .sidebar button, .sidebar form');

    sidebar.addEventListener('click', (e) => {
      if (e.target === sidebar || e.target.closest('.sidebar-content') === sidebar.querySelector('.sidebar-content')) {
        sidebar.classList.toggle('expanded');
      }
    });

    items.forEach(item => item.addEventListener('click', e => e.stopPropagation()));

    document.addEventListener('click', (e) => {
      if (sidebar.classList.contains('expanded') && !sidebar.contains(e.target)) {
        sidebar.classList.remove('expanded');
      }
    });
  </script>
</body>
</html>