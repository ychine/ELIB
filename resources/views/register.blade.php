<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Kulim+Park:ital,wght@0,200;0,300;0,400;0,600;0,700;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/css/output.css', 'resources/css/Inter.css', 'resources/css/kulimpark.css'])

  <title>Resource Management | ISU StudyGo</title>

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
        <span>Profile</span>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="fixed top-0 left-0 h-full bg-[#149637] shadow-[5px_-10px_22.5px_2px_rgba(0,0,0,0.59)] rounded-tr-[50px] sidebar z-20 pt-8">
      <div class="sidebar-content space-y-2 text-white">
        <img 
          src="{{ Vite::asset('resources/images/ISUStudyGoBorder.svg') }}" 
          alt="Library" 
          class="w-full h-20 isu-studygo-border-logo"
        />
        <img 
          src="{{ Vite::asset('resources/images/ISUclpsd.svg') }}" 
          alt="Library" 
          class="w-full h-10 translate-y-[20px] absolute isu-studygo-logo"
        />
        <a href="{{ route('home.librarian') }}" class="w-full h-12 bg-green-800 rounded-xl shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)] flex items-center gap-3 cursor-pointer  hover:bg-green-700 transition-colors">
          <img src="{{ Vite::asset('resources/images/Dashboard.png') }}" alt="Dashboard" class="w-7 h-7 sidebar-icons"/>
          <span class="label kulim-park-regular text-lg">Dashboard</span>
        </a>
        <a href="{{ route('resource.management') }}" class="w-full h-12 bg-green-500 rounded-xl flex items-center gap-3 cursor-pointer">
    
          <img src="{{ Vite::asset('resources/images/resmgmttoggle.png') }}" alt="Dashboard" class="w-7 h-7 sidebar-icons"/>
          <span class="label kulim-park-regular text-lg">Resource Management</span>
        </a>
        <a href="{{ route('featured') }}" class="w-full h-12 bg-green-800 rounded-xl shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)] flex items-center gap-3 cursor-pointer  hover:bg-green-700 transition-colors">
          <img 
            src="{{ Vite::asset('resources/images/Featured.png') }}" 
            alt="Featured" 
            class="w-7 h-7 translate-y-[-1px] translate-x-[1px] sidebar-icons"
          />
          <span class="label kulim-park-regular text-lg">Featured</span>
        </a>
        <a href="{{ route('community.uploads') }}" class="w-full h-12 bg-green-800 rounded-xl shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)] flex items-center gap-3 cursor-pointer  hover:bg-green-700 transition-colors">
          <img 
            src="{{ Vite::asset('resources/images/Member.png') }}" 
            alt="Community Uploads" 
            class="w-7 h-7 sidebar-icons"
          />
          <span class="label kulim-park-regular text-lg">Community Uploads</span>
        </a>
  
        <form method="POST" action="{{ route('logout') }}" class="mt-auto w-full h-12 bg-green-800 rounded-xl shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)] flex items-center gap-3">
          @csrf
          <button type="submit" class="flex items-center gap-3 w-full h-full bg-transparent border-none text-white cursor-pointer">
            <i class="fa-solid fa-sign-out-alt text-2xl sidebar-icons"></i>
            <span class="label text-lg">Logout</span>
          </button>
        </form>
      </div>
    </div>

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
          <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold kulim-park-bold tracking-tight mb-4">Resource Management</h2>
        </div>
        <div class="homediv lg:mx-[10%] rounded-2xl bg-white shadow-lg p-6">
          <!-- Add Resource Button -->
          <button onclick="document.getElementById('addResourceModal').classList.remove('hidden')" class="bg-green-500 text-white px-4 py-2 rounded mb-4">Add Resource</button>

          <!-- Resources Table -->
          <table class="min-w-full bg-white">
            <thead>
              <tr>
                <th class="py-2 px-4 border-b">Name</th>
                <th class="py-2 px-4 border-b">Author</th>
                <th class="py-2 px-4 border-b">Description</th>
                <th class="py-2 px-4 border-b">Type</th>
                <th class="py-2 px-4 border-b">Upload Date</th>
                <th class="py-2 px-4 border-b">Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($resources as $resource)
                <tr>
                  <td class="py-2 px-4 border-b">{{ $resource->Resource_Name }}</td>
                  <td class="py-2 px-4 border-b">{{ $resource->author }}</td>
                  <td class="py-2 px-4 border-b">{{ $resource->Description }}</td>
                  <td class="py-2 px-4 border-b">{{ $resource->Type }}</td>
                  <td class="py-2 px-4 border-b">{{ $resource->Upload_Date }}</td>
                  <td class="py-2 px-4 border-b">{{ $resource->status }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>

          <!-- Add Resource Modal -->
          <div id="addResourceModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg w-96">
              <h3 class="text-xl font-bold mb-4">Add New Resource</h3>
              <form action="{{ route('resources.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                  <label for="name" class="block text-sm font-medium">Name</label>
                  <input type="text" name="Resource_Name" id="name" class="w-full border px-2 py-1 rounded" required>
                </div>
                <div class="mb-4">
                  <label for="author" class="block text-sm font-medium">Author</label>
                  <input type="text" name="author" id="author" class="w-full border px-2 py-1 rounded" required>
                </div>
                <div class="mb-4">
                  <label for="description" class="block text-sm font-medium">Description</label>
                  <textarea name="Description" id="description" class="w-full border px-2 py-1 rounded" required></textarea>
                </div>
                <div class="mb-4">
                  <label for="file" class="block text-sm font-medium">File</label>
                  <input type="file" name="file" id="file" class="w-full" required>
                </div>
                <input type="hidden" name="Type" value="Featured">
                <div class="flex justify-end">
                  <button type="button" onclick="document.getElementById('addResourceModal').classList.add('hidden')" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                  <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Upload</button>
                </div>
              </form>
            </div>
          </div>
        </div>
       
      </div>
    </div>
  </div>

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

  <!-- Scroll Effect for Navbar -->
  <script>
    window.addEventListener('scroll', () => {
      const nav = document.querySelector('.glass-nav');
      const libraryImg = document.querySelector('.main-content img');
      const libraryHeight = libraryImg ? libraryImg.offsetHeight : 0;
      const scrollPosition = window.scrollY;

      if (scrollPosition > libraryHeight) {
        nav.classList.add('scrolled');
      } else {
        nav.classList.remove('scrolled');
      }
    });
  </script>

  <!-- Sidebar Toggle & Click Outside to Collapse -->
  <script>
    const sidebar = document.querySelector('.sidebar');
    const sidebarItems = document.querySelectorAll('.sidebar .cursor-pointer, .sidebar button, .sidebar form');

    // Toggle sidebar on click
    sidebar.addEventListener('click', (e) => {
      // Only toggle if clicking on the sidebar background (not items)
      if (e.target === sidebar || e.target.closest('.sidebar-content') === sidebar.querySelector('.sidebar-content')) {
        sidebar.classList.toggle('expanded');
      }
    });

    // Prevent collapse when clicking inside sidebar items
    sidebarItems.forEach(item => {
      item.addEventListener('click', (e) => {
        e.stopPropagation();
      });
    });

    // Collapse when clicking outside
    document.addEventListener('click', (e) => {
      if (sidebar.classList.contains('expanded') && !sidebar.contains(e.target)) {
        sidebar.classList.remove('expanded');
      }
    });

    // Optional: Allow clicking the logo area to toggle
    const logoArea = sidebar.querySelector('.sidebar-content');
    logoArea.style.pointerEvents = 'auto';
  </script>
</body>
</html>