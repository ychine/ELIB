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
  <title>Your Shelf | ISU StudyGo</title>

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

    .glass-nav {
      background: transparent;
      transition: all 0.3s ease;
    }
    .glass-nav.scrolled {
      background: linear-gradient(rgba(4, 30, 10, 0.9), rgba(4, 30, 10, 0.7), rgba(4, 30, 10, 0.5), rgba(255, 255, 255, 0.0));
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
    }
    .glass-nav .nav-item { visibility: hidden; transition: visibility 0.3s ease; }
    .glass-nav.scrolled .nav-item { visibility: visible; }

    .searchbar {
      background: rgba(217, 217, 217, 1); 
      color: #000; 
      transition: all 0.3s ease;
    }

    .bottom-nav { display: none; }
    @media (max-width: 768px) {
      .sidebar { display: none; }
      .bottom-nav { display: flex; }
      .main-content { padding-bottom: 4.5rem; }
    }
    .bottom-nav .nav-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: white;
      text-decoration: none;
      font-size: 0.75rem;
      gap: 0.25rem;
    }
    .bottom-nav .nav-item img {
      width: 1.5rem;
      height: 1.5rem;
    }

    .sidebar.expanded + .main-content { margin-left: 15rem; margin-top: 0; }

    /* Book Grid Styles */
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

    /* Pending Table Styles */
    .pending-table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 12px;
      overflow: hidden;
    }
    .pending-table thead {
      background: #f3f4f6;
    }
    .pending-table th {
      padding: 0.5rem 1rem;
      text-align: left;
      font-weight: 600;
      color: #374151;
      border-bottom: 2px solid #e5e7eb;
      font-size: 0.875rem;
    }
    .pending-table td {
      padding: 0.5rem 1rem;
      border-bottom: 1px solid #e5e7eb;
      font-size: 0.875rem;
    }
    .pending-table tr:last-child td {
      border-bottom: none;
    }
    .pending-table tr:hover {
      background: #f9fafb;
    }
  </style>
</head>
<body class="bg-gray-50">
  <div class="w-full h-[100vh] flex">
    <!-- Universal Sidebar -->
    @include('partials.universalSidebar')

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

    <div class="flex flex-col flex-1 transition-all duration-300 main-content bg-gray-50">
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

        <div class="px-4 lg:px-[5%] mt-2 pt-2">
          <div class="flex flex-col lg:pl-28 lg:pr-20 lg:flex-row gap-6 content-wrapper">
            <div class="w-full">
              <h2 class="text-2xl sm:text-3xl font-extrabold kulim-park-bold tracking-tight mb-4">Your Shelf</h2>
              
              @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                  {{ session('success') }}
                </div>
              @endif
              
              @php
                $pendingBorrows = $borrows->filter(function($borrow) {
                  return !$borrow->Approved_Date;
                });
                $activeBorrows = $borrows->filter(function($borrow) {
                  return $borrow->Approved_Date && !$borrow->isReturned;
                });
                $returnedBorrows = $borrows->filter(function($borrow) {
                  return $borrow->isReturned;
                });
              @endphp

              @if($pendingBorrows->count() > 0)
                <div class="mb-8">
                  <h3 class="text-xl font-bold kulim-park-bold mb-4 text-gray-800">Pending Requests</h3>
                  <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <table class="pending-table">
                      <thead>
                        <tr>
                          <th>Resource</th>
                          <th>Requested Date</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($pendingBorrows as $borrow)
                          <tr>
                            <td>
                              <div class="flex items-center gap-3">
                      @if($borrow->resource && $borrow->resource->thumbnail_path && Storage::disk('public')->exists($borrow->resource->thumbnail_path))
                    <img src="{{ asset('storage/' . $borrow->resource->thumbnail_path) }}"
                         alt="{{ $borrow->resource->Resource_Name }}"
                                       class="w-12 h-16 object-cover rounded">
                @else
                                  <div class="w-12 h-16 bg-gray-200 rounded flex items-center justify-center uppercase text-gray-500 text-xs">
                          {{ strtoupper(substr($borrow->resource->Resource_Name ?? 'NA', 0, 2)) }}
                                  </div>
                                @endif
                                <span class="font-semibold text-gray-900">{{ $borrow->resource->Resource_Name ?? 'Unknown Resource' }}</span>
                              </div>
                            </td>
                            <td class="text-gray-600">{{ \Carbon\Carbon::parse($borrow->created_at)->format('M d, Y') }}</td>
                            <td>
                              <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-700">
                                Pending Approval
                              </span>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                    </div>
                @endif

              @if($activeBorrows->count() > 0 || $returnedBorrows->count() > 0)
                <div>
                  @if($activeBorrows->count() > 0)
                    <h3 class="text-xl font-bold kulim-park-bold mb-4 text-gray-800">Currently Borrowed</h3>
                    <div class="books-grid mb-8">
                      @foreach($activeBorrows as $borrow)
                        @php
                          $resource = $borrow->resource;
                          $authorsRelation = $resource && $resource->relationLoaded('authors') ? $resource->getRelation('authors') : null;
                          $tagsRelation = $resource && $resource->relationLoaded('tags') ? $resource->getRelation('tags') : null;
                          $authorsData = $authorsRelation && $authorsRelation->isNotEmpty() 
                            ? $authorsRelation->map(fn($a) => ['name' => $a->name])->toArray() 
                            : [];
                          $tagsData = $tagsRelation && $tagsRelation->isNotEmpty() 
                            ? $tagsRelation->map(fn($t) => ['name' => $t->name])->toArray() 
                            : [];
                        @endphp
                        <div class="book-card cursor-pointer" 
                             data-borrow="{{ json_encode([
                               'Borrower_ID' => $borrow->Borrower_ID,
                               'Resource_ID' => $resource->Resource_ID ?? null,
                               'Resource_Name' => $resource->Resource_Name ?? 'Unknown Resource',
                               'thumbnail_path' => $resource->thumbnail_path ?? null,
                               'Description' => $resource->Description ?? 'No description available.',
                               'Approved_Date' => $borrow->Approved_Date,
                               'authors' => $authorsData,
                               'tags' => $tagsData,
                               'average_rating' => $resource->average_rating ?? '0.0',
                               'formatted_publish_date' => $resource->formatted_publish_date ?? 'N/A',
                               'views' => $resource->views ?? 0
                             ]) }}">
                          <div class="book-cover bg-gray-400">
                            @if($borrow->resource && $borrow->resource->thumbnail_path && Storage::disk('public')->exists($borrow->resource->thumbnail_path))
                              <img src="{{ asset('storage/' . $borrow->resource->thumbnail_path) }}" 
                                  alt="{{ $borrow->resource->Resource_Name }}" 
                                  class="w-full h-full object-cover">
                            @else
                              <span class="text-white text-4xl font-bold">{{ strtoupper(substr($borrow->resource->Resource_Name ?? 'NA', 0, 2)) }}</span>
                            @endif
                        </div>
                          <div class="book-info">
                            <h3 class="book-title">{{ $borrow->resource->Resource_Name ?? 'Unknown Resource' }}</h3>
                            <p class="text-xs text-gray-500">Borrowed: {{ \Carbon\Carbon::parse($borrow->Approved_Date)->format('M d, Y') }}</p>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  @endif

                  @if($returnedBorrows->count() > 0)
                    <h3 class="text-xl font-bold kulim-park-bold mb-4 text-gray-800">Returned Books</h3>
                    <div class="books-grid">
                      @foreach($returnedBorrows as $borrow)
                        <div class="book-card">
                          <div class="book-cover bg-gray-400">
                            @if($borrow->resource && $borrow->resource->thumbnail_path && Storage::disk('public')->exists($borrow->resource->thumbnail_path))
                              <img src="{{ asset('storage/' . $borrow->resource->thumbnail_path) }}" 
                                  alt="{{ $borrow->resource->Resource_Name }}" 
                                  class="w-full h-full object-cover">
                            @else
                              <span class="text-white text-4xl font-bold">{{ strtoupper(substr($borrow->resource->Resource_Name ?? 'NA', 0, 2)) }}</span>
                            @endif
                          </div>
                          <div class="book-info">
                            <h3 class="book-title">{{ $borrow->resource->Resource_Name ?? 'Unknown Resource' }}</h3>
                            <p class="text-xs text-gray-500">Returned: {{ $borrow->returned_at ? \Carbon\Carbon::parse($borrow->returned_at)->format('M d, Y') : 'N/A' }}</p>
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 inline-block mt-1">
                              Returned
                            </span>
                          </div>
                        </div>
                      @endforeach
                        </div>
                    @endif
                </div>
              @endif

              @if($borrows->count() === 0)
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center text-gray-600 text-lg">
                    You don't have any borrows yet. Browse featured resources to start reading!
                  </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

  <!-- Borrow Modal for Your Shelf -->
  <div id="shelfModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50 overflow-y-auto" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] my-4 overflow-y-auto">
      <div class="flex h-full min-h-0">
        <!-- Left: Thumbnail -->
        <div class="w-72 flex-shrink-0 bg-gradient-to-br from-gray-100 to-gray-200 p-6 flex items-center justify-center border-r border-gray-200">
          <div class="w-60 h-96 bg-gray-100 border-8 border-gray-300 rounded-lg shadow-xl flex items-center justify-center overflow-hidden">
            <img id="shelfModalThumbnail" 
                src="" 
                alt="Book Cover" 
                class="w-full h-full object-cover hidden">
            <div id="shelfModalThumbnailPlaceholder" 
                class="w-full h-full bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center text-6xl font-bold text-white">
              BOOK
            </div>
          </div>
        </div>

        <!-- Right: Details -->
        <div class="flex-1 p-8 flex flex-col overflow-y-auto min-h-0">
          <!-- Title -->
          <h2 id="shelfModalTitle" class="text-3xl kulim-park-bold font-bold text-gray-900 mb-6 leading-tight"></h2>

          <!-- Rating with Views -->
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2 text-yellow-500">
              <span id="shelfModalRating" class="text-4xl font-bold">★ 0.0</span>
            </div>
            <div class="text-sm text-gray-500 font-medium">
              <span id="shelfModalViews">0 views</span>
            </div>
          </div>

          <!-- Author & Published -->
          <div class="space-y-3 mb-8 text-gray-800">
            <p class="flex items-center gap-3">
              <span class="font-semibold text-sm text-gray-600 uppercase tracking-wide">Author</span>
              <span id="shelfModalAuthor" class="font-medium text-lg"></span>
            </p>
            <p class="flex items-center gap-3">
              <span class="font-semibold text-sm text-gray-600 uppercase tracking-wide">Borrowed Date</span>
              <span id="shelfModalBorrowedDate" class="font-medium text-lg"></span>
            </p>
            <p class="flex items-center gap-3">
              <span class="font-semibold text-sm text-gray-600 uppercase tracking-wide">Originally published</span>
              <span id="shelfModalPublished" class="font-medium text-lg"></span>
            </p>
          </div>

          <!-- Tags -->
          <div class="mb-8">
            <p class="font-semibold text-gray-800 text-sm uppercase tracking-wide mb-3">Tags</p>
            <div id="shelfModalTags" class="flex flex-wrap gap-2"></div>
          </div>

          <!-- Description -->
          <div class="flex-1 mb-10">
            <p class="font-semibold text-gray-800 text-sm uppercase tracking-wide mb-4">Description</p>
            <p id="shelfModalDescription" class="text-gray-700 text-sm leading-relaxed max-h-48 overflow-y-auto pr-2"></p>
          </div>

          <!-- Buttons -->
          <div class="flex gap-4 justify-end pt-4 border-t border-gray-200">
            <button type="button" onclick="closeShelfModal()"
                    class="px-8 py-3 bg-white text-gray-700 border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all font-medium text-sm shadow-sm">
              Cancel
            </button>
            <a id="shelfModalViewBook" href="#" target="_blank"
               class="shelf-modal-btn-view">
              <i class="fas fa-book-open"></i>
              View Book
            </a>
            <form method="POST" id="shelfModalReturnForm" class="inline">
              @csrf
              <button type="submit"
                      class="shelf-modal-btn-return"
                      onclick="return confirm('Are you sure you want to return this book?')">
                <i class="fas fa-undo"></i>
                Return Book
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    #shelfModalDescription::-webkit-scrollbar { 
      width: 4px; 
    }
    #shelfModalDescription::-webkit-scrollbar-track { 
      background: transparent; 
    }
    #shelfModalDescription::-webkit-scrollbar-thumb { 
      background: #d1d5db; 
      border-radius: 2px; 
    }
    #shelfModalDescription::-webkit-scrollbar-thumb:hover { 
      background: #9ca3af; 
    }
    #shelfModalTags span {
      background: #10B981;
      color: white;
      padding: 0.375rem 0.875rem;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.025em;
    }
    @media (max-width: 768px) {
      #shelfModal .bg-white {
        margin: 1rem;
        border-radius: 1rem;
      }
      #shelfModal .w-72 {
        width: 100%;
        height: 280px;
      }
      #shelfModal .w-60 {
        width: 240px;
        height: 100%;
      }
      #shelfModal h2 {
        font-size: 1.75rem !important;
      }
      #shelfModal .text-4xl {
        font-size: 2.5rem !important;
      }
      #shelfModal .flex.gap-4 {
        flex-direction: column-reverse;
        gap: 1rem;
      }
      #shelfModal button, #shelfModal a {
        flex: 1;
        justify-content: center;
      }
    }
    
    /* Ensure buttons in shelf modal have proper styling - override global styles */
    .shelf-modal-btn-view,
    .shelf-modal-btn-return {
      padding: 0.75rem 2rem !important;
      border-radius: 0.75rem !important;
      font-weight: 500 !important;
      font-size: 0.875rem !important;
      color: white !important;
      border: none !important;
      cursor: pointer !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      min-height: 2.5rem !important;
      text-decoration: none !important;
      gap: 0.5rem !important;
      transition: all 0.2s ease !important;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    }
    
    .shelf-modal-btn-view {
      background: linear-gradient(to right, #22C55E, #16A34A) !important;
    }
    
    .shelf-modal-btn-view:hover {
      background: linear-gradient(to right, #16A34A, #15803D) !important;
      box-shadow: 0 6px 16px rgba(34, 197, 94, 0.3) !important;
      transform: translateY(-1px) !important;
    }
    
    .shelf-modal-btn-return {
      background: linear-gradient(to right, #dc2626, #b91c1c) !important;
    }
    
    .shelf-modal-btn-return:hover {
      background: linear-gradient(to right, #b91c1c, #991b1b) !important;
      box-shadow: 0 6px 16px rgba(220, 38, 38, 0.3) !important;
      transform: translateY(-1px) !important;
    }
  </style>

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

    // Shelf Modal Functions
    function openShelfModal(borrow) {
      document.getElementById('shelfModalTitle').textContent = borrow.Resource_Name;
      document.getElementById('shelfModalRating').innerHTML = '★ ' + (borrow.average_rating || '0.0');
      document.getElementById('shelfModalAuthor').textContent = Array.isArray(borrow.authors) ? borrow.authors.map(a => a.name || a).join(', ') : (borrow.authors || 'Unknown Author');
      document.getElementById('shelfModalBorrowedDate').textContent = borrow.Approved_Date ? new Date(borrow.Approved_Date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A';
      document.getElementById('shelfModalPublished').textContent = borrow.formatted_publish_date || 'N/A';
      document.getElementById('shelfModalDescription').textContent = borrow.Description || 'No description available.';
      document.getElementById('shelfModalViews').textContent = (borrow.views || 0) + ' views';

      // Handle thumbnail
      const thumbnailImg = document.getElementById('shelfModalThumbnail');
      const thumbnailPlaceholder = document.getElementById('shelfModalThumbnailPlaceholder');
      
      if (borrow.thumbnail_path) {
        thumbnailImg.src = '/storage/' + borrow.thumbnail_path;
        thumbnailImg.classList.remove('hidden');
        thumbnailPlaceholder.classList.add('hidden');
      } else {
        thumbnailImg.classList.add('hidden');
        thumbnailPlaceholder.classList.remove('hidden');
        thumbnailPlaceholder.textContent = borrow.Resource_Name.substring(0, 2).toUpperCase();
      }

      // Handle tags
      const tagsContainer = document.getElementById('shelfModalTags');
      tagsContainer.innerHTML = '';
      if (borrow.tags && borrow.tags.length) {
        borrow.tags.forEach(tag => {
          const span = document.createElement('span');
          span.textContent = tag.name || tag;
          tagsContainer.appendChild(span);
        });
      } else {
        tagsContainer.innerHTML = '<span class="text-gray-500 text-sm">No tags</span>';
      }

      // Set up buttons
      const viewBookLink = document.getElementById('shelfModalViewBook');
      const returnForm = document.getElementById('shelfModalReturnForm');
      
      viewBookLink.href = '{{ route("viewer", ":id") }}'.replace(':id', borrow.Resource_ID);
      returnForm.action = '{{ route("return.book", ":id") }}'.replace(':id', borrow.Borrower_ID);

      const modal = document.getElementById('shelfModal');
      modal.style.display = 'flex';
      modal.classList.remove('hidden');
      // Prevent body scroll when modal is open
      document.body.style.overflow = 'hidden';
    }

    function closeShelfModal() {
      const modal = document.getElementById('shelfModal');
      modal.style.display = 'none';
      modal.classList.add('hidden');
      // Restore body scroll when modal is closed
      document.body.style.overflow = '';
    }

    // Make currently borrowed cards clickable
    document.querySelectorAll('.book-card[data-borrow]').forEach(card => {
      card.addEventListener('click', function (e) {
        // Don't open modal if clicking on links/buttons inside
        if (e.target.closest('a, button')) return;
        
        const borrow = JSON.parse(this.dataset.borrow);
        openShelfModal(borrow);
      });
    });

    // Close modal when clicking outside
    document.getElementById('shelfModal').addEventListener('click', function (e) {
      if (e.target === this) closeShelfModal();
    });
  </script>
  @include('partials.globalLoader')
</body>
</html>
