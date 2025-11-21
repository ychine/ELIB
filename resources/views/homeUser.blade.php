<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Kulim+Park:ital,wght@0,200;0,300;0,400;0,600;0,700;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @vite(['resources/css/app.css', 'resources/css/output.css', 'resources/css/Inter.css', 'resources/css/kulimpark.css'])

  <title>Home | ISU StudyGo</title>

  <style>
    :root {
      --featured-ease: cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* === GLOBAL FIXES === */
    html, body, .main-content { overflow-x: hidden; }

    .main-content {
      opacity: 0;
      animation: pageFadeIn 0.65s var(--featured-ease) 80ms forwards;
    }

    @keyframes pageFadeIn {
      from { opacity: 0; transform: translateY(24px); }
      to { opacity: 1; transform: translateY(0); }
    }

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
      gap: 1rem;
      width: 100%;
      grid-template-columns: repeat(auto-fit, minmax(clamp(160px, 18vw, 220px), 1fr));
    }

    .book-card {
      background: white;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      transition:
        opacity 0.35s var(--featured-ease),
        transform 0.35s var(--featured-ease),
        box-shadow 0.3s ease;
      cursor: pointer;
      display: flex;
      flex-direction: column;
      height: 100%;
    }
    .book-card[data-visible="false"] {
      opacity: 0;
      transform: translateY(18px);
      pointer-events: none;
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

    .featured-pagination {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 1rem;
      margin-top: 1.5rem;
    }
    .featured-pagination button {
      width: 42px;
      height: 42px;
      border-radius: 999px;
      border: 1px solid rgba(34, 197, 94, 0.3);
      background: white;
      color: #047857;
      font-size: 1.2rem;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s ease;
      box-shadow: 0 8px 18px rgba(15, 118, 110, 0.12);
    }
    .featured-pagination button i {
      pointer-events: none;
      color: #047857;
      font-size: 1rem;
    }
    .featured-pagination button:disabled {
      opacity: 0.4;
      cursor: not-allowed;
      box-shadow: none;
    }
    .featured-pagination span {
      font-weight: 600;
      color: #065f46;
      letter-spacing: 0.05em;
    }

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

    <!-- Universal Sidebar -->
    @include('partials.universalSidebar')

    <!-- Bottom Nav (Mobile) -->
    <div class="bottom-nav fixed bottom-0 left-0 w-full h-16 bg-[#149637] z-20 flex justify-around items-center shadow-lg">
      <a href="{{ route('home.user') }}" class="nav-item"><img src="{{ Vite::asset('resources/images/HomeToggle.png') }}" alt="Home"/><span>Home</span></a>
      <a href="{{ route('featured') }}" class="nav-item"><img src="{{ Vite::asset('resources/images/Featured.png') }}" alt="Featured"/><span>Featured</span></a>
      <a href="{{ route('community.uploads') }}" class="nav-item"><img src="{{ Vite::asset('resources/images/Member.png') }}" alt="Community"/><span>Community</span></a>
      <a href="{{ route('yourshelf') }}" class="nav-item"><img src="{{ Vite::asset('resources/images/Book Shelf.png') }}" alt="Shelf"/><span>Shelf</span></a>
      <form method="POST" action="{{ route('logout') }}" class="nav-item">@csrf
        <button type="submit" class="flex flex-col items-center justify-center w-full h-full"><i class="fa-solid fa-sign-out-alt text-xl text-white"></i><span>Logout</span></button>
      </form>
    </div>

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
        <!-- Empty space to match admin/librarian layout -->
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col flex-1 transition-all duration-300 main-content bg-gray-50 ">
      <div class="hero-container relative w-full greenhue z-1" style="min-height: 200px;">
        <img 
          src="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}" 
          alt="ISU Logo" 
          class="absolute right-0 w-15 h-15 m-7"
        />
        <h5 class="absolute text-white right-0 m-7 mr-10 translate-y-30 kulim-park-semibold">One ISU</h5>
        <img src="{{ Vite::asset('resources/images/libgreenptr.jpg') }}" alt="Library" class="hero-image w-full h-full z-[-1] object-cover absolute" style="object-position: 70% middle; height: 200px;"/>
        <div class="herotext ml-30 flex relative z-2 py-8">
          <div class="column">
            <h1 style="line-height: 86.402%; font-family: 'Kulim Park', sans-serif; font-weight: 600; letter-spacing: -1.3px; font-size: 45px; text-shadow: 0 4px 4px #000; color: #FFF;">
              Bridging knowledge <br> from one campus <br> to another
            </h1>
          </div>
        </div>

        <div class="px-4 lg:px-[5%] mt-4">
          <div class="flex flex-col lg:pl-28 lg:pr-20 lg:flex-row gap-10 pt-10 content-wrapper">
            <!-- Featured Section -->
            <div class="featured-section" style="flex: 0 0 70%;">
              <h2 class="text-2xl sm:text-3xl font-extrabold kulim-park-bold tracking-tight mb-4">ISU Featured Resources</h2>
              
              <div class="filter-tabs">
                <a href="{{ route('home.user', ['filter' => 'latest']) }}" class="filter-tab {{ $filter === 'latest' ? 'active' : '' }}">Latest Uploads</a>
                <a href="{{ route('home.user', ['filter' => 'popular_month']) }}" class="filter-tab {{ $filter === 'popular_month' ? 'active' : '' }}">Popular This Month</a>
                <a href="{{ route('home.user', ['filter' => 'popular_year']) }}" class="filter-tab {{ $filter === 'popular_year' ? 'active' : '' }}">Popular This Year</a>
              </div>

              <div class="books-grid">
                @forelse($featuredResources as $resource)
                  @php
                    $resource->append(['average_rating', 'formatted_publish_date', 'authors', 'tags']);
                    $resourceData = $resource->toArray();
                    $resourceData['is_borrowed'] = $resource->is_borrowed ?? false;
                  @endphp
                  <div class="book-card cursor-pointer transition-opacity duration-200" 
                        data-index="{{ $loop->index }}"
                        data-resource="{{ json_encode($resourceData) }}">
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
                        @if(collect($resource->tags)->count() > 0)
                          @foreach(collect($resource->tags)->take(2) as $tag)
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full">{{ $tag->name ?? $tag['name'] ?? $tag }}</span>
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
                
                <!-- View More Button -->
                @if($featuredResources->count() >= 7)
                  <a href="{{ route('featured') }}" class="book-card flex items-center justify-center bg-green-100 hover:bg-green-200 transition-colors border-2 border-dashed border-green-400 rounded-lg">
                    <div class="text-center">
                      <div class="text-4xl font-bold text-green-700 mb-2">+</div>
                      <div class="text-sm font-semibold text-green-700">View More</div>
              </div>
                  </a>
                @endif
              </div>
            </div>

            <!-- Right Sidebar: Popular Tags and Community Uploads -->
            <div class="flex flex-col gap-6" style="flex: 0 0 30%;">
              <!-- Popular Tags Section -->
              @if($popularTags && $popularTags->count() > 0)
                <div>
                  <h2 class="text-xl font-extrabold kulim-park-bold tracking-tight mb-4">Popular Tags</h2>
                  <div class="bg-white rounded-lg p-4 shadow-lg">
                    <div class="flex flex-wrap gap-2">
                      @foreach($popularTags as $tag)
                        <span class="text-xs bg-green-100 text-green-800 px-3 py-1.5 rounded-full font-medium hover:bg-green-200 transition-colors cursor-pointer">
                          {{ $tag->name }}
                        </span>
                      @endforeach
                    </div>
                  </div>
                </div>
              @endif

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
  </div>

  @include('partials.borrowModal')

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

      // Handle borrow button state
      const borrowButton = document.getElementById('borrowButton');
      const borrowForm = document.getElementById('borrowForm');
      const alreadyBorrowedMessage = document.getElementById('alreadyBorrowedMessage');
      
      if (resource.is_borrowed) {
        borrowForm.classList.add('hidden');
        alreadyBorrowedMessage.classList.remove('hidden');
        borrowButton.disabled = true;
      } else {
        borrowForm.classList.remove('hidden');
        alreadyBorrowedMessage.classList.add('hidden');
        borrowButton.disabled = false;
      }

      // Increment views via AJAX
      const incrementUrl = '{{ route("resources.increment.view", "placeholder") }}'.replace('placeholder', resource.Resource_ID);
      fetch(incrementUrl, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({})
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          let viewsElem = document.getElementById('modalViews');
          viewsElem.textContent = data.views + ' views';
        }
      })
      .catch(error => console.error('Error incrementing views:', error));

      document.getElementById('borrowModal').classList.remove('hidden');
    }

    function closeBorrowModal() {
      document.getElementById('borrowModal').classList.add('hidden');
    }

    // Prevent tab clicks from bubbling up to card listeners
    const filterTabsWrapper = document.querySelector('.filter-tabs');
    if (filterTabsWrapper) {
      filterTabsWrapper.addEventListener('click', (event) => {
        event.stopPropagation();
      });
    }

    document.getElementById('borrowModal').addEventListener('click', function (e) {
      if (e.target === this) closeBorrowModal();
    });

    function initFeaturedPagination() {
      const paginationWrapper = document.getElementById('featuredPagination');
      const prevButton = document.getElementById('featuredPrev');
      const nextButton = document.getElementById('featuredNext');
      const indicator = document.getElementById('featuredPageIndicator');
      const booksGrid = document.querySelector('.books-grid');
      const bookCards = Array.from(document.querySelectorAll('.books-grid .book-card'));

      if (!bookCards.length || !paginationWrapper) return;

      bookCards.forEach(card => {
        card.dataset.visible = 'false';
        card.style.display = 'none';
        card.addEventListener('click', function () {
          const resource = JSON.parse(this.dataset.resource);
          openBorrowModal(resource);
        });
      });

      const rowsPerPage = 2;
      const columnsForPage = () => {
        if (window.innerWidth >= 1280) return 4;
        if (window.innerWidth >= 1024) return 3;
        if (window.innerWidth >= 768) return 2;
        return 1;
      };
      const computeCardsPerPage = () => columnsForPage() * rowsPerPage;

      let cardsPerPage = computeCardsPerPage();
      let currentFeaturedPage = 0;
      let featuredTotalPages = Math.max(1, Math.ceil(bookCards.length / cardsPerPage));

      const setCardVisibility = (card, shouldShow) => {
        if (shouldShow) {
          if (card.dataset.visible === 'true') return;
          card.style.display = '';
          requestAnimationFrame(() => {
            card.dataset.visible = 'true';
          });
        } else {
          if (card.dataset.visible === 'false') return;
          const handle = () => {
            card.style.display = 'none';
            card.removeEventListener('transitionend', handle);
          };
          card.addEventListener('transitionend', handle);
          card.dataset.visible = 'false';
          setTimeout(handle, 450);
        }
      };

      const renderPage = (pageIndex = currentFeaturedPage) => {
        cardsPerPage = computeCardsPerPage();
        featuredTotalPages = Math.max(1, Math.ceil(bookCards.length / cardsPerPage));

        currentFeaturedPage = Math.min(pageIndex, featuredTotalPages - 1);
        currentFeaturedPage = Math.max(currentFeaturedPage, 0);

        const start = currentFeaturedPage * cardsPerPage;
        const end = start + cardsPerPage;

        bookCards.forEach((card, index) => {
          setCardVisibility(card, index >= start && index < end);
        });

        const hidePagination = featuredTotalPages <= 1;
        paginationWrapper.classList.toggle('hidden', hidePagination);

        if (!hidePagination && indicator) {
          indicator.textContent = `${currentFeaturedPage + 1} / ${featuredTotalPages}`;
          if (prevButton) prevButton.disabled = currentFeaturedPage === 0;
          if (nextButton) nextButton.disabled = currentFeaturedPage === featuredTotalPages - 1;
        }
      };

      prevButton?.addEventListener('click', () => {
        if (currentFeaturedPage > 0) {
          renderPage(currentFeaturedPage - 1);
        }
      });

      nextButton?.addEventListener('click', () => {
        if (currentFeaturedPage < featuredTotalPages - 1) {
          renderPage(currentFeaturedPage + 1);
        }
      });

      let resizeTimeout;
      window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => renderPage(currentFeaturedPage), 150);
      });

      renderPage(0);
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initFeaturedPagination);
    } else {
      initFeaturedPagination();
    }

    // Sidebar Toggle
    const sidebar = document.querySelector('.sidebar');
    sidebar.addEventListener('click', (event) => {
      if (event.target.closest('.sidebar a, .sidebar button, .sidebar form')) return;
      sidebar.classList.toggle('expanded');
    });
    document.addEventListener('click', (e) => {
      if (sidebar.classList.contains('expanded') && !sidebar.contains(e.target)) {
        if (e.target.closest('.filter-tabs')) return;
        sidebar.classList.remove('expanded');
      }
    });
  </script>
  @include('partials.globalLoader')
</body>
</html>