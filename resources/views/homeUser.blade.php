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

  <title>Home | ISU StudyGo</title>

  <style>
    /* === GLOBAL FIXES === */
    html, body, .main-content { overflow-x: hidden; }

    /* Sidebar Styles */
    .sidebar {
      width: 4rem;
      transition: all 0.3s cubic-bezier(0.215, 0.610, 0.355, 1);
      overflow: hidden;
      padding-left: 0.5rem;
      padding-right: 0.5rem;
      cursor: pointer;
    }
    .sidebar.expanded { width: 18rem; }
    .sidebar .label { opacity: 0; transform: translateX(-10px); transition: all 0.3s ease; white-space: nowrap; padding-left: 1rem; }
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

    .glass-nav.scrolled .nav-item {
      visibility: visible;
    }

    .searchbar {
      background: rgba(217, 217, 217, 1); 
      color: #000; 
      transition: all 0.3s ease;
    }

    /* === BOOK GRID & CARDS === */
    .books-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
      width: 100%;
    }
    @media (min-width: 640px) { .books-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (min-width: 1024px) { .books-grid { grid-template-columns: repeat(5, 1fr); } }

    .book-card {
      background: white;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      cursor: pointer;
      display: flex;
      flex-direction: column;
      height: 100%;
    }
    .book-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }
    .book-cover {
      width: 100%;
      height: 180px;
      background: linear-gradient(135deg, #22C55E 0%, #16A34A 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 2.5rem;
      font-weight: bold;
    }
    .book-info {
      padding: 1rem;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }
    .book-title {
      font-weight: 600;
      font-size: 0.95rem;
      line-height: 1.3;
      color: #1f2937;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    .book-author { font-size: 0.8rem; color: #6b7280; }

    /* Filter Tabs */
    .filter-tabs {
      display: flex;
      gap: 1rem;
      margin-bottom: 1.5rem;
      border-bottom: 2px solid #e5e7eb;
    }
    .filter-tab {
      padding: 0.75rem 1.5rem;
      cursor: pointer;
      border-bottom: 3px solid transparent;
      transition: all 0.3s ease;
      font-weight: 500;
      color: #6b7280;
    }
    .filter-tab:hover { color: #22C55E; }
    .filter-tab.active { color: #22C55E; border-bottom-color: #22C55E; }

    /* Community Section */
    .community-section { flex: 0 0 30%; max-width: 30%; }
    .community-item {
      display: flex;
      gap: 1rem;
      padding: 1rem;
      background: white;
      border-radius: 8px;
      margin-bottom: 0.75rem;
      transition: background 0.3s ease;
      cursor: pointer;
    }
    .community-item:hover { background: #f9fafb; }
    .community-icon {
      width: 48px; height: 48px;
      background: linear-gradient(135deg, #22C55E 0%, #16A34A 100%);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
      flex-shrink: 0;
    }

    /* Responsive */
    @media (max-width: 1024px) {
      .featured-section, .community-section { flex: 0 0 100% !important; max-width: 100%; }
      .content-wrapper { flex-direction: column; }
    }
    @media (max-width: 768px) {
      .sidebar { display: none; }
      .bottom-nav { display: flex; }
      .main-content { padding-bottom: 4.5rem; }
    }
    @media (min-width: 769px) { .bottom-nav { display: none; } }

    /* Borrow Modal */
    #borrowModal .modal-content {
      max-width: 900px;
      width: 95%;
      max-height: 90vh;
      overflow-y: auto;
      border-radius: 20px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    }
    @media (max-width: 768px) {
      #borrowModal .modal-content { width: 96%; border-radius: 16px; }
    }
    #borrowModal::-webkit-scrollbar { width: 8px; }
    #borrowModal::-webkit-scrollbar-thumb { background: #22c55e; border-radius: 4px; }
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
        
        <span>Profile</span>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="fixed top-0 left-0 h-full bg-[#149637] shadow-[5px_-10px_22.5px_2px_rgba(0,0,0,0.59)] rounded-tr-[50px] sidebar z-20 pt-8">
      <div class="sidebar-content space-y-2 text-white">
        <img src="{{ Vite::asset('resources/images/ISUStudyGoBorder.svg') }}" alt="Logo" class="w-full h-20 isu-studygo-border-logo"/>
        <img src="{{ Vite::asset('resources/images/ISUclpsd.svg') }}" alt="Logo" class="w-full h-10 translate-y-[20px] absolute isu-studygo-logo"/>
        <a href="{{ route('home.user') }}" class="w-full h-12 bg-green-500 rounded-xl flex items-center gap-3 cursor-pointer">
          <img src="{{ Vite::asset('resources/images/HomeToggle.png') }}" alt="Home" class="w-7 h-7 sidebar-icons"/>
          <span class="label kulim-park-regular text-lg">Home</span>
        </a>
        <a href="{{ route('featured') }}" class="w-full h-12 bg-green-800 rounded-xl shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)] flex items-center gap-3 cursor-pointer hover:bg-green-700 transition-colors">
          <img src="{{ Vite::asset('resources/images/Featured.png') }}" alt="Featured" class="w-7 h-7 sidebar-icons"/>
          <span class="label kulim-park-regular text-lg">Featured</span>
        </a>
        <a href="{{ route('community.uploads') }}" class="w-full h-12 bg-green-800 rounded-xl shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)] flex items-center gap-3 cursor-pointer hover:bg-green-700 transition-colors">
          <img src="{{ Vite::asset('resources/images/Member.png') }}" alt="Community" class="w-7 h-7 sidebar-icons"/>
          <span class="label kulim-park-regular text-lg">Community Uploads</span>
        </a>
        <a href="#" class="w-full h-12 bg-green-800 rounded-xl shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)] flex items-center gap-3 cursor-pointer hover:bg-green-700 transition-colors">
          <img src="{{ Vite::asset('resources/images/Book Shelf.png') }}" alt="Shelf" class="w-7 h-7 sidebar-icons"/>
          <span class="label kulim-park-regular text-lg">Your Shelf</span>
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

    <!-- Bottom Nav (Mobile) -->
    <div class="bottom-nav fixed bottom-0 left-0 w-full h-16 bg-[#149637] z-20 flex justify-around items-center shadow-lg">
      <a href="{{ route('home.user') }}" class="nav-item"><img src="{{ Vite::asset('resources/images/HomeToggle.png') }}" alt="Home"/><span>Home</span></a>
      <a href="{{ route('featured') }}" class="nav-item"><img src="{{ Vite::asset('resources/images/Featured.png') }}" alt="Featured"/><span>Featured</span></a>
      <a href="{{ route('community.uploads') }}" class="nav-item"><img src="{{ Vite::asset('resources/images/Member.png') }}" alt="Community"/><span>Community</span></a>
      <a href="#" class="nav-item"><img src="{{ Vite::asset('resources/images/Book Shelf.png') }}" alt="Shelf"/><span>Shelf</span></a>
      <form method="POST" action="{{ route('logout') }}" class="nav-item">@csrf
        <button type="submit" class="flex flex-col items-center justify-center w-full h-full"><i class="fa-solid fa-sign-out-alt text-xl text-white"></i><span>Logout</span></button>
      </form>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col flex-1 transition-all duration-300 main-content bg-gray-50">
      <div class="hero-container relative  w-full greenhue z-1">
        <img src="{{ Vite::asset('resources/images/libgreenptr.jpg') }}" alt="Library" class="w-full h-50 z-[-1] object-cover absolute" style="object-position: 70% middle;"/>
        <div class="herotext h-50 ml-30 flex relative z-2">
          <div class="column">
            <h1 style="transform: translateY(50%); line-height: 86.402%; font-family: 'Kulim Park', sans-serif; font-weight: 600; letter-spacing: -1.3px; font-size: 45px; text-shadow: 0 4px 4px #000; color: #FFF;">
              Bridging knowledge <br> from one campus <br> to another
            </h1>
          </div>
        </div>

        <div class="px-4 lg:px-[5%] mt-5">
          <div class="flex flex-col lg:pl-28 lg:pr-20 lg:flex-row gap-6 content-wrapper">
            <!-- Featured Section -->
            <div class="featured-section" style="flex: 0 0 70%;">
              <h2 class="text-2xl sm:text-3xl font-extrabold kulim-park-bold tracking-tight mb-4">Featured Resources</h2>
              
              <div class="filter-tabs">
                <a href="{{ route('home.user', ['filter' => 'latest']) }}" class="filter-tab {{ $filter === 'latest' ? 'active' : '' }}">Latest Uploads</a>
                <a href="{{ route('home.user', ['filter' => 'popular_month']) }}" class="filter-tab {{ $filter === 'popular_month' ? 'active' : '' }}">Popular This Month</a>
                <a href="{{ route('home.user', ['filter' => 'popular_year']) }}" class="filter-tab {{ $filter === 'popular_year' ? 'active' : '' }}">Popular This Year</a>
              </div>

              <div class="books-grid">
                @forelse($featuredResources as $resource)
                  <div class="book-card cursor-pointer" 
                        data-resource="{{ $resource->append(['average_rating', 'formatted_publish_date', 'authors', 'tags'])->toJson() }}">
                      <div class="book-cover bg-gray-400">
                        @if($resource->thumbnail_path && Storage::disk('public')->exists($resource->thumbnail_path))
                          <img src="{{ asset('storage/' . $resource->thumbnail_path) }}" 
                              alt="{{ $resource->Resource_Name }}" 
                              class="w-full h-full object-cover">
                        @else
                          <span class="text-white text-4xl font-bold">{{ strtoupper(substr($resource->Resource_Name, 0, 2)) }}</span>
                        @endif
                      </div>
                      <div class="book-info">
                      <h3 class="book-title">{{ $resource->Resource_Name }}</h3>
                      <div class="flex items-center gap-1 text-yellow-500 text-sm">
                        <span>★</span>
                        <span class="text-gray-700 font-medium">{{ $resource->average_rating ?? '0.0' }}</span>
                      </div>
                      <p class="book-author text-sm text-gray-600">{{ $resource->authors ?? 'Unknown Author' }}</p>
                      <p class="text-xs text-gray-500">Published: {{ $resource->formatted_publish_date }}</p>
                      <div class="mt-2 flex flex-wrap gap-1">
                        @if($resource->tags && $resource->tags->isNotEmpty())
                          @foreach($resource->tags->take(2) as $tag)
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full">{{ $tag['name'] ?? $tag->name }}</span>
                          @endforeach
                        @else
                          <span class="text-xs text-gray-400">No tags</span>
                        @endif
                      </div>
                    </div>
                  </div>
                @empty
                  <div class="col-span-full text-center py-12 text-gray-500">No featured resources available.</div>
                @endforelse
              </div>
            </div>

            <!-- Community Uploads -->
            <div class="community-section">
              <h2 class="text-xl font-extrabold kulim-park-bold tracking-tight mb-4">Community Uploads</h2>
              <div class="bg-white rounded-lg p-4 shadow-lg">
                @forelse($communityUploads as $resource)
                  <a href="{{ route('resources.view', $resource->Resource_ID) }}" class="community-item">
                    <div class="community-icon">{{ strtoupper(substr($resource->Resource_Name, 0, 2)) }}</div>
                    <div class="community-info">
                      <div class="community-title">{{ $resource->Resource_Name }}</div>
                      <div class="community-author">by {{ $resource->user->full_name ?? 'Unknown' }}</div>
                    </div>
                  </a>
                @empty
                  <div class="text-center py-8 text-gray-500">No community uploads yet.</div>
                @endforelse
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('partials.borrowModal')

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
  <script>
    // Modal Functions
    function openBorrowModal(resource) {
      document.getElementById('modalTitle').textContent = resource.Resource_Name;
      document.getElementById('modalRating').innerHTML = '★ ' + (resource.average_rating || '0.0');
      document.getElementById('modalAuthor').textContent = Array.isArray(resource.authors) ? resource.authors.map(a => a.name).join(', ') : (resource.authors || 'Unknown Author');
      document.getElementById('modalPublished').textContent = resource.formatted_publish_date || 'N/A';
      document.getElementById('modalResourceId').value = resource.Resource_ID;
      document.getElementById('modalDescription').textContent = resource.Description || 'No description available.';
      document.getElementById('modalViews').textContent = (resource.views || 0) + ' views';
      // Handle thumbnail
      const thumbnailImg = document.getElementById('modalThumbnail');
      const thumbnailPlaceholder = document.getElementById('modalThumbnailPlaceholder');
      
      if (resource.thumbnail_path) {
        thumbnailImg.src = '/storage/' + resource.thumbnail_path;
        thumbnailImg.classList.remove('hidden');
        thumbnailPlaceholder.classList.add('hidden');
      } else {
        thumbnailImg.classList.add('hidden');
        thumbnailPlaceholder.classList.remove('hidden');
        thumbnailPlaceholder.textContent = resource.Resource_Name.substring(0, 2).toUpperCase();
      }

      const tagsContainer = document.getElementById('modalTags');
      tagsContainer.innerHTML = '';
      if (resource.tags && resource.tags.length) {
        resource.tags.forEach(tag => {
          const span = document.createElement('span');
          span.className = 'text-xs bg-green-100 text-green-800 px-3 py-1 rounded-full';
          span.textContent = tag.name;
          tagsContainer.appendChild(span);
        });
      } else {
        tagsContainer.innerHTML = '<span class="text-gray-500 text-sm">No tags</span>';
      }

      document.getElementById('borrowModal').classList.remove('hidden');
    }

    function closeBorrowModal() {
      document.getElementById('borrowModal').classList.add('hidden');
    }

    document.querySelectorAll('.book-card').forEach(card => {
      card.addEventListener('click', function () {
        const resource = JSON.parse(this.dataset.resource);
        openBorrowModal(resource);
      });
    });

    document.getElementById('borrowModal').addEventListener('click', function (e) {
      if (e.target === this) closeBorrowModal();
    });

    // Navbar Scroll
    window.addEventListener('scroll', () => {
      const nav = document.querySelector('.glass-nav');
      const libraryImg = document.querySelector('.main-content img');
      const libraryHeight = libraryImg ? libraryImg.offsetHeight : 0;
      if (window.scrollY > libraryHeight) nav.classList.add('scrolled');
      else nav.classList.remove('scrolled');
    });

    // Sidebar Toggle
    const sidebar = document.querySelector('.sidebar');
    sidebar.addEventListener('click', () => sidebar.classList.toggle('expanded'));
    document.addEventListener('click', (e) => {
      if (sidebar.classList.contains('expanded') && !sidebar.contains(e.target)) {
        sidebar.classList.remove('expanded');
      }
    });
  </script>
</body>
</html>