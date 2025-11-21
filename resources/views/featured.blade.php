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

  <title>Featured Resources | ISU StudyGo</title>

  <style>
    :root {
      --featured-ease: cubic-bezier(0.16, 1, 0.3, 1);
    }

    html, body, .main-content { overflow-x: hidden; }

    .main-content {
      opacity: 0;
      animation: pageFadeIn 0.65s var(--featured-ease) 80ms forwards;
    }

    @keyframes pageFadeIn {
      from { opacity: 0; transform: translateY(24px); }
      to { opacity: 1; transform: translateY(0); }
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

    .books-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
      width: 100%;
    }
    @media (min-width: 640px) { .books-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (min-width: 1024px) { .books-grid { grid-template-columns: repeat(5, 1fr); } }

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
      text-decoration: none;
    }
    .filter-tab:hover { color: #22C55E; }
    .filter-tab.active { color: #22C55E; border-bottom-color: #22C55E; }

    .book-card {
      background: white;
      border-radius: 0.75rem;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .book-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .book-cover {
      width: 100%;
      height: 200px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }
    .book-info {
      padding: 1rem;
    }
    .book-title {
      font-size: 0.875rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: #1f2937;
      line-height: 1.3;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    .book-author {
      font-size: 0.75rem;
      color: #6b7280;
      margin-top: 0.25rem;
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
          <div class="flex flex-col lg:pl-28 lg:pr-20 pt-10 content-wrapper">
            <!-- Featured Section -->
            <div class="featured-section w-full">
              <h2 class="text-2xl sm:text-3xl font-extrabold kulim-park-bold tracking-tight mb-4">Featured Resources</h2>
              
              <div class="filter-tabs">
                <a href="{{ route('featured', ['filter' => 'latest']) }}" class="filter-tab {{ $filter === 'latest' ? 'active' : '' }}">Latest Uploads</a>
                <a href="{{ route('featured', ['filter' => 'popular_month']) }}" class="filter-tab {{ $filter === 'popular_month' ? 'active' : '' }}">Popular This Month</a>
                <a href="{{ route('featured', ['filter' => 'popular_year']) }}" class="filter-tab {{ $filter === 'popular_year' ? 'active' : '' }}">Popular This Year</a>
              </div>

              <div class="books-grid">
                @forelse($featuredResources as $resource)
                  @php
                    $resource->append(['average_rating', 'formatted_publish_date', 'authors', 'tags']);
                    $resourceData = $resource->toArray();
                    $resourceData['is_borrowed'] = $resource->is_borrowed ?? false;
                  @endphp
                  <a href="{{ route('resources.view', $resource->Resource_ID) }}" class="book-card cursor-pointer transition-opacity duration-200">
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
                  </a>
                @empty
                  <div class="col-span-full text-center py-12 text-gray-500">No featured resources available.</div>
                @endforelse
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function initGlassNavScroll() {
      const nav = document.querySelector('.glass-nav');
      if (!nav) return;

      const heroImage = document.querySelector('.hero-container img[src*="libgreenptr"]');
      const heroContainer = document.querySelector('.hero-container');

      const updateNavBlur = () => {
        if (!heroImage && !heroContainer) {
          nav.classList.add('scrolled');
          return;
        }

        const reference = heroImage || heroContainer;
        const rect = reference.getBoundingClientRect();
        const tolerance = nav.offsetHeight + 16;

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

