<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap&family=Kulim+Park:ital,wght@0,200;0,300;0,400;0,600;0,700;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
  <noscript><link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap&family=Kulim+Park:ital,wght@0,200;0,300;0,400;0,600;0,700;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet"></noscript>
  @vite(['resources/css/app.css', 'resources/css/output.css', 'resources/css/Inter.css', 'resources/css/kulimpark.css', 'resources/css/kantumruypro.css'])
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
    /* Modal Styles */
    .modal { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:9999; justify-content:center; align-items:center; opacity: 0; transition: opacity 0.3s ease; }
    .modal.active { display:flex; opacity: 1; }
    .modal-content { 
      background:white; 
      border-radius:12px; 
      width:90%; 
      max-width:600px; 
      height:85vh; 
      max-height:700px;
      min-height:500px;
      box-shadow:0 10px 30px rgba(0,0,0,0.3); 
      transform: scale(0.95); 
      opacity: 0; 
      transition: transform 0.3s ease, opacity 0.3s ease; 
      display: flex; 
      flex-direction: column; 
      position: relative;
      overflow: hidden;
    }
    @media (max-width: 768px) {
      .modal-content {
        height:90vh;
        max-height:90vh;
        min-height:400px;
        width:95%;
      }
    }
    @media (max-height: 600px) {
      .modal-content {
        height:95vh;
        max-height:95vh;
        min-height:300px;
      }
    }
    .modal.active .modal-content { transform: scale(1); opacity: 1; }
    .modal-header { 
      padding:1.5rem; 
      border-bottom:1px solid #e5e7eb; 
      flex-shrink: 0; 
      background: white;
    }
    .modal-body { 
      padding:1.5rem; 
      padding-bottom: 90px;
      flex: 1;
      overflow-y: auto; 
      overflow-x: hidden;
      min-height: 0;
      -webkit-overflow-scrolling: touch;
      position: relative;
      margin-bottom: 0;
      
      /* Scroll shadows using background gradients */
      background:
        /* Shadow Cover TOP */
        linear-gradient(
          white 30%,
          rgba(255, 255, 255, 0)
        ) center top,
        
        /* Shadow Cover BOTTOM */
        linear-gradient(
          rgba(255, 255, 255, 0), 
          white 70%
        ) center bottom,
        
        /* Shadow TOP */
        radial-gradient(
          farthest-side at 50% 0,
          rgba(0, 0, 0, 0.2),
          rgba(0, 0, 0, 0)
        ) center top,
        
        /* Shadow BOTTOM - positioned above footer */
        radial-gradient(
          farthest-side at 50% 100%,
          rgba(0, 0, 0, 0.25),
          rgba(0, 0, 0, 0)
        ) center bottom;
      
      background-repeat: no-repeat;
      background-size: 100% 40px, 100% 40px, 100% 14px, 100% 20px;
      background-attachment: local, local, scroll, scroll;
      background-position: center top, center bottom, center top, center bottom;
    }
    .modal-body.scrolled-top {
      background:
        /* Shadow Cover BOTTOM */
        linear-gradient(
          rgba(255, 255, 255, 0), 
          white 70%
        ) center bottom,
        
        /* Shadow BOTTOM */
        radial-gradient(
          farthest-side at 50% 100%,
          rgba(0, 0, 0, 0.25),
          rgba(0, 0, 0, 0)
        ) center bottom;
      background-repeat: no-repeat;
      background-size: 100% 40px, 100% 20px;
      background-attachment: local, scroll;
      background-position: center bottom, center bottom;
    }
    .modal-body.scrolled-bottom {
      background:
        /* Shadow Cover TOP */
        linear-gradient(
          white 30%,
          rgba(255, 255, 255, 0)
        ) center top,
        
        /* Shadow TOP */
        radial-gradient(
          farthest-side at 50% 0,
          rgba(0, 0, 0, 0.2),
          rgba(0, 0, 0, 0)
        ) center top;
      background-repeat: no-repeat;
      background-size: 100% 40px, 100% 14px;
      background-attachment: local, scroll;
      background-position: center top, center top;
    }
    .modal-body.scrolled-top.scrolled-bottom {
      background: white;
    }
    #editResourceModal .modal-body {
      max-height: calc(85vh - 180px);
    }
    @media (max-width: 768px) {
      #editResourceModal .modal-body {
        max-height: calc(90vh - 180px);
      }
    }
    @media (max-height: 600px) {
      #editResourceModal .modal-body {
        max-height: calc(95vh - 180px);
      }
    }
    .modal-footer { 
      padding:1rem 1.5rem; 
      border-top:1px solid #e5e7eb; 
      display:flex; 
      justify-content:space-between; 
      gap:0.5rem; 
      flex-shrink: 0; 
      background: white; 
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      z-index: 10;
      box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
      min-height: 70px;
    }
    /* Theme Fonts */
    .kantumruy-pro-regular { font-family: 'Kantumruy Pro', sans-serif; }
    .kulim-park-bold { font-family: 'Kulim Park', sans-serif; font-weight: 700; }
    /* Table Styles */
    table.min-w-full { width: 100%; border-collapse: separate; border-spacing: 0; }
    table.min-w-full thead tr th { background: #f3f4f6; text-align: left; font-weight: 600; font-family: 'Kantumruy Pro', sans-serif; }
    table.min-w-full tbody tr:hover { background: #f9fafb; cursor: pointer; }
    table.min-w-full td, table.min-w-full th { padding: 1rem; border-bottom: 1px solid #e5e7eb; }
    /* Drop Zone Styles */
    .drop-zone {
      border: 2px dashed #bbb;
      border-radius: 5px;
      padding: 25px;
      text-align: center;
      cursor: pointer;
      transition: background 0.3s;
    }
    .drop-zone.dragover {
      background: #eee;
      border-color: #000;
    }
    .drop-zone p {
      margin: 0;
      color: #666;
    }
    /* Alert Styles for Flash Messages */
    .alert { padding: 1rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: 0.375rem; }
    .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
    .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
    .error-message { color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem; }
    /* Tag Styles */
    .tags-container { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.5rem; }
    .tag { display: flex; align-items: center; background: #d1fae5; color: #065f46; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.875rem; }
    .tag-remove { margin-left: 0.5rem; cursor: pointer; font-weight: bold; }
  </style>
</head>
<body class="bg-yellow-50">
  <div class="w-full min-h-screen flex">
    <!-- Navigation -->
    <div class="fixed w-full top-0 left-0 flex justify-between items-center px-4 py-2 z-10 glass-nav">
      <span class="text-5xl jersey-20-regular pl-3 text-white"></span>
      <div class="relative flex items-center">
        <input class="searchbar z-0 pl-7 pr-10 sm:w-[545px] h-11 rounded-[34px]  shadow-[inset_0px_4px_4px_rgba(0,0,0,0.25)]" type="text" placeholder="Search for books, papers..">
        <img
          src="{{ Vite::asset('resources/images/Search.png') }}"
          alt="Search icon"
          class="absolute right-5 w-6 h-6"
        />
      </div>
      <div class="text-md flex space-x-4 gap-5 pr-6 plus-jakarta-sans-semibold text-white">
        <span class="bg-green-800 rounded-3xl px-3 py-1 border-2 border-amber-400 text-[13px] kantumruy-pro-regular">LIBRARIAN</span>
      </div>
    </div>
    <!-- Universal Sidebar -->
    <!-- Universal Sidebar - Load First -->
    @include('partials.universalSidebar')
    @vite(['resources/js/app.js'])
    
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
      <a href="{{ route('yourshelf') }}" class="nav-item">
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
    <div class="flex flex-col flex-1 min-h-screen transition-all duration-300 main-content bg-gray-200">
      <div class="hero-container relative w-full  greenhue z-1">
        <img
          src="{{ Vite::asset('resources/images/libgreenptr.jpg') }}"
          alt="Library"
          class="hero-image w-full h-50 z-[-1] object-cover absolute"
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
          <!-- Flash Messages -->
          @if (session('success'))
            <div class="alert alert-success mb-4">
              {{ session('success') }}
            </div>
          @endif
          @if (session('error'))
            <div class="alert alert-danger mb-4">
              {{ session('error') }}
            </div>
          @endif
          @if ($errors->any())
            <div class="alert alert-danger mb-4">
              <ul class="list-unstyled mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          
          <!-- Search Bar and Add Resource Button -->
          <div class="flex gap-4 mb-4 items-center">
            <input type="text" 
                   id="resourceSearchInput" 
                   placeholder="Search by title, author, or status..." 
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 searchbar">
            <button onclick="openAddModal()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 kantumruy-pro-regular">Add Resource</button>
          </div>
          
          <!-- Resources Table -->
          <table class="min-w-full bg-white rounded border border-gray-200 shadow-sm overflow-hidden" id="resourceTable">
            <thead>
              <tr>
                <th class="py-3 px-6 border-b border-gray-500 text-left kantumruy-pro-regular">Title</th>
                <th class="py-3 px-6 border-b border-gray-500 text-left kantumruy-pro-regular">Author(s)</th>
                <th class="py-3 px-6 border-b border-gray-500 text-left kantumruy-pro-regular">Published Date</th>
                <th class="py-3 px-6 border-b border-gray-500 text-left kantumruy-pro-regular">Upload Date</th>
                <th class="py-3 px-6 border-b border-gray-500 text-left kantumruy-pro-regular">Uploaded By</th>
                <th class="py-3 px-6 border-b border-gray-500 text-left kantumruy-pro-regular">Status</th>
              </tr>
            </thead>
            <tbody>
             @foreach ($resources as $resource)
                @php
                  $authorsCollection = $resource->getRelationValue('authors');
                  $authorsString = $authorsCollection && $authorsCollection->isNotEmpty()
                      ? $authorsCollection->pluck('name')->implode(', ')
                      : 'Unknown Author';
                @endphp

                <tr 
                  class="hover:bg-gray-50 transition-colors cursor-pointer resource-row" 
                  data-resource="{{ json_encode($resource->only(['Resource_ID', 'Resource_Name', 'Description', 'publish_year', 'publish_month', 'publish_day', 'File_Path', 'status']) + ['tags' => collect($resource->tags)->pluck('name')]) }}"
                  data-authors="{{ $authorsString }}"
                  onclick="openEditModalFromRow(this)"
                >
                  <td class="py-2 px-6 border-b border-gray-400 kantumruy-pro-regular">
                    {{ $resource->Resource_Name }}
                    <br>
                    <button class="text-green-700 hover:text-green-800 hover:no-underline text-sm font-medium" onclick="event.stopPropagation(); openPreviewModal('/storage/{{ $resource->File_Path }}')">
                      View File
                    </button>
                  </td>
                  <td class="py-2 px-6 border-b border-gray-400 kantumruy-pro-regular tracking-tight">
                    {{ $authorsString }}
                  </td>
                  <td class="py-2 px-6 border-b border-gray-400 kantumruy-pro-regular tracking-tight">
                    {{ $resource->formatted_publish_date ?? 'N/A' }}
                  </td>
                  <td class="py-2 px-6 border-b border-gray-400 kantumruy-pro-regular tracking-tight">
                    {{ $resource->created_at->format('Y-m-d') }}
                  </td>
                  <td class="py-2 px-6 border-b border-gray-400 kantumruy-pro-regular tracking-tight">
                    {{ $resource->user->full_name ?? 'Unknown' }}
                  </td>
                  <td class="py-2 px-6 border-b border-gray-400 kantumruy-pro-regular tracking-tight">
                    {{ $resource->status }}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
         
        </div>
      
      </div>
    </div>
  </div>
  <!-- Add Resource Modal (moved outside main flex for full overlay) -->
  <div id="addResourceModal" class="modal">
    <div class="modal-content kantumruy-pro-regular tracking-tight">
      <div class="modal-header">
        <h3 class="text-xl font-bold">Add New Resource</h3>
      </div>
      <form action="{{ route('resources.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateAddForm()">
        @csrf
        <input type="hidden" name="MAX_FILE_SIZE" value="104857600">
        <div class="modal-body space-y-4">
          <div>
            <label for="name" class="block font-medium">Title</label>
            <input type="text" name="Resource_Name" id="name" value="{{ old('Resource_Name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('Resource_Name') border-red-500 @enderror" required autocomplete="off">
            @error('Resource_Name')
              <div class="error-message">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label for="author" class="block font-medium">Author(s) (comma-separated)</label>
            <input type="text" name="authors" id="author" value="{{ old('authors') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('authors') border-red-500 @enderror" required autocomplete="off">
            @error('authors')
              <div class="error-message">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label for="description" class="block font-medium">Description</label>
            <textarea name="Description" id="description" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('Description') border-red-500 @enderror" required autocomplete="off">{{ old('Description') }}</textarea>
            @error('Description')
              <div class="error-message">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label for="tags" class="block font-medium">Tags</label>
            <div id="addTagsContainer" class="tags-container"></div>
            <input type="text" id="addTagsInput" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('tags') border-red-500 @enderror" placeholder="Type tag and press space/enter" autocomplete="off">
            <input type="hidden" name="tags" id="addTagsHidden" value="{{ old('tags') }}">
            <p class="text-xs text-gray-500 mt-1">Press space or enter to add a tag</p>
            @error('tags')
              <div class="error-message">{{ $message }}</div>
            @enderror
          </div>
          <script>
            // Populate tags from old input if validation failed
            @if(old('tags'))
              document.addEventListener('DOMContentLoaded', () => {
                const oldTags = '{{ old('tags') }}'.split(',').map(t => t.trim()).filter(t => t);
                oldTags.forEach(tag => {
                  if (tag) addTag('add', tag);
                });
              });
            @endif
          </script>
          <div>
            <label class="block font-medium">Publish Date</label>
            <div id="addDatePicker" class="flex space-x-2">
              <select id="add_year" name="publish_year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('publish_year') border-red-500 @enderror" onchange="showAddMonth();">
                <option value="">Select Year (optional)</option>
                @php
                  $currentYear = date('Y');
                  $oldYear = old('publish_year');
                  for ($year = $currentYear + 5; $year >= 1900; $year--) {
                    $selected = ($oldYear == $year) ? 'selected' : '';
                    echo "<option value=\"$year\" $selected>$year</option>";
                  }
                @endphp
              </select>
              @error('publish_year')
                <div class="error-message">{{ $message }}</div>
              @enderror
              <select id="add_month" name="publish_month" style="display: none;" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('publish_month') border-red-500 @enderror" onchange="showAddDay();">
                <option value="">Select Month</option>
                @php $oldMonth = old('publish_month'); @endphp
                <option value="1" {{ $oldMonth == 1 ? 'selected' : '' }}>January</option>
                <option value="2" {{ $oldMonth == 2 ? 'selected' : '' }}>February</option>
                <option value="3" {{ $oldMonth == 3 ? 'selected' : '' }}>March</option>
                <option value="4" {{ $oldMonth == 4 ? 'selected' : '' }}>April</option>
                <option value="5" {{ $oldMonth == 5 ? 'selected' : '' }}>May</option>
                <option value="6" {{ $oldMonth == 6 ? 'selected' : '' }}>June</option>
                <option value="7" {{ $oldMonth == 7 ? 'selected' : '' }}>July</option>
                <option value="8" {{ $oldMonth == 8 ? 'selected' : '' }}>August</option>
                <option value="9" {{ $oldMonth == 9 ? 'selected' : '' }}>September</option>
                <option value="10" {{ $oldMonth == 10 ? 'selected' : '' }}>October</option>
                <option value="11" {{ $oldMonth == 11 ? 'selected' : '' }}>November</option>
                <option value="12" {{ $oldMonth == 12 ? 'selected' : '' }}>December</option>
              </select>
              @error('publish_month')
                <div class="error-message">{{ $message }}</div>
              @enderror
              <select id="add_day" name="publish_day" style="display: none;" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('publish_day') border-red-500 @enderror">
                <option value="">Select Day</option>
                @php $oldDay = old('publish_day'); @endphp
                @for ($d = 1; $d <= 31; $d++)
                  <option value="{{ $d }}" {{ $oldDay == $d ? 'selected' : '' }}>{{ $d }}</option>
                @endfor
              </select>
              @error('publish_day')
                <div class="error-message">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div>
            <label for="file" class="block font-medium">File</label>
            <div id="dropZone" class="drop-zone">
              <p>Drag & drop file here or click to upload</p>
              <input type="file" name="file" id="file" class="hidden" required>
            </div>
            @error('file')
              <div class="error-message">{{ $message }}</div>
            @enderror
          </div>
          <input type="hidden" name="Type" value="Featured">
        </div>
        <div class="modal-footer">
          <button type="button" onclick="document.getElementById('addResourceModal').classList.remove('active')" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Upload</button>
        </div>
      </form>
    </div>
  </div>
  <!-- Edit Resource Modal -->
  <div id="editResourceModal" class="modal">
    <div class="modal-content kantumruy-pro-regular tracking-tight">
      <div class="modal-header">
        <h3 class="text-xl font-bold">Edit Resource</h3>
      </div>
      <form id="editForm" action="" method="POST" enctype="multipart/form-data" onsubmit="return validateEditForm()">
        @csrf
        @method('PATCH')
        <div class="modal-body space-y-4">
          <div>
            <label for="edit_name" class="block font-medium">Title</label>
            <input type="text" name="Resource_Name" id="edit_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('Resource_Name') border-red-500 @enderror" required>
            @error('Resource_Name')
              <div class="error-message">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label for="edit_author" class="block font-medium">Author(s) (comma-separated)</label>
            <input type="text" name="authors" id="edit_author" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('authors') border-red-500 @enderror" required>
            @error('authors')
              <div class="error-message">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label for="edit_description" class="block font-medium">Description</label>
            <textarea name="Description" id="edit_description" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('Description') border-red-500 @enderror" required></textarea>
            @error('Description')
              <div class="error-message">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label class="block font-medium">Publish Date</label>
            <div id="editDatePicker" class="flex space-x-2">
              <select id="edit_year" name="publish_year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('publish_year') border-red-500 @enderror" onchange="showEditMonth();">
                <option value="">Select Year (optional)</option>
                @php
                  $currentYear = date('Y');
                  for ($year = $currentYear + 5; $year >= 1900; $year--) {
                    echo "<option value=\"$year\">$year</option>";
                  }
                @endphp
              </select>
              @error('publish_year')
                <div class="error-message">{{ $message }}</div>
              @enderror
              <select id="edit_month" name="publish_month" style="display: none;" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('publish_month') border-red-500 @enderror" onchange="showEditDay();">
                <option value="">Select Month</option>
                <option value="1">January</option>
                <option value="2">February</option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">June</option>
                <option value="7">July</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
              </select>
              @error('publish_month')
                <div class="error-message">{{ $message }}</div>
              @enderror
              <select id="edit_day" name="publish_day" style="display: none;" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('publish_day') border-red-500 @enderror">
                <option value="">Select Day</option>
              </select>
              @error('publish_day')
                <div class="error-message">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div>
            <label for="edit_file" class="block font-medium">File (optional update)</label>
            <div id="editDropZone" class="drop-zone">
              <p id="editFileText">Drag & drop new file here or click to upload</p>
              <input type="file" name="file" id="edit_file" class="hidden">
            </div>
            @error('file')
              <div class="error-message">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label class="block font-medium">Tags (press space or enter to add)</label>
            <div id="editTagsContainer" class="tags-container"></div>
            <input type="text" id="editTagsInput" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Type tag and press space/enter">
            <input type="hidden" name="tags" id="editTagsHidden">
          </div>
          <div>
            <label for="edit_status" class="block font-medium">Status</label>
            <select name="status" id="edit_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('status') border-red-500 @enderror" required>
              <option value="Available">Available</option>
              <option value="Unavailable">Archived</option>
            </select>
            @error('status')
              <div class="error-message">{{ $message }}</div>
            @enderror
          </div>
          <input type="hidden" name="Type" value="Featured">
        </div>
        <div class="modal-footer" style="display: flex; justify-content: space-between; align-items: center;">
          <button type="button" onclick="confirmDeleteResource()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center gap-2">
            <i class="fa-solid fa-trash" style="color: white; font-size: 16px;"></i>
            <span>Delete</span>
          </button>
          <div style="display: flex; gap: 0.5rem;">
          <button type="button" onclick="document.getElementById('editResourceModal').classList.remove('active')" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Update</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <form id="deleteForm" action="" method="POST" class="hidden">
    @csrf
    @method('DELETE')
  </form>
  <!-- Preview Modal -->
  <div id="previewModal" class="modal">
    <div class="modal-content" style="width:90%; max-width:800px; height:90vh;">
      <div class="modal-header">
        <h3 class="text-xl font-bold">File Preview</h3>
      </div>
      <div class="modal-body" style="height: calc(100% - 100px); padding:0;">
        <iframe id="previewIframe" src="" style="width:100%; height:100%; border:none;"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="closePreviewModal()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Close</button>
      </div>
    </div>
  </div>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <!-- Scroll Effect for Navbar -->
  <script>
    // Improved scroll blur effect - waits for image to load and uses multiple selectors
    function initScrollBlur() {
      const nav = document.querySelector('.glass-nav');
      if (!nav) return;

      // Try multiple selectors for the hero image/container
      const heroImage = document.querySelector('.hero-image') || 
                       document.querySelector('.hero-container img') ||
                       document.querySelector('.main-content .hero-container img');
      
      const heroContainer = document.querySelector('.hero-container');

      function updateNavBlur() {
        let libraryHeight = 0;
        
        if (heroImage && heroImage.offsetHeight > 0) {
          libraryHeight = heroImage.offsetHeight;
        } else if (heroContainer && heroContainer.offsetHeight > 0) {
          libraryHeight = heroContainer.offsetHeight;
        } else {
          // Fallback: use a reasonable default height
          libraryHeight = 400;
        }

        const scrollPosition = window.scrollY || window.pageYOffset;

        if (scrollPosition > libraryHeight) {
          nav.classList.add('scrolled');
        } else {
          nav.classList.remove('scrolled');
        }
      }

      // Wait for image to load before calculating height
      if (heroImage) {
        if (heroImage.complete) {
          updateNavBlur();
        } else {
          heroImage.addEventListener('load', updateNavBlur);
        }
      }

      // Also check on load event for the whole page
      window.addEventListener('load', updateNavBlur);
      
      // Update on scroll
      window.addEventListener('scroll', updateNavBlur);
      
      // Update on resize (in case image size changes)
      window.addEventListener('resize', updateNavBlur);

      // Initial check
      updateNavBlur();
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initScrollBlur);
    } else {
      initScrollBlur();
    }
  </script>
  <!-- Sidebar Toggle & Click Outside to Collapse -->
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
  <!-- Modal Close on Outside Click -->
  <script>
    window.addEventListener('click', e => {
      const addModal = document.getElementById('addResourceModal');
      const editModal = document.getElementById('editResourceModal');
      const previewModal = document.getElementById('previewModal');
      if (e.target === addModal) {
        addModal.classList.remove('active');
      }
      if (e.target === editModal) {
        editModal.classList.remove('active');
      }
      if (e.target === previewModal) {
        previewModal.classList.remove('active');
      }
    });
  </script>
  <!-- Auto-open Add Modal if Validation Errors -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      @if ($errors->any())
        document.getElementById('addResourceModal').classList.add('active');
      @endif
    });
  </script>
  <!-- Drag-and-Drop File Upload for Add Modal -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const dropZone = document.getElementById('dropZone');
      const fileInput = document.getElementById('file');
      // Click to upload
      dropZone.addEventListener('click', () => {
        fileInput.click();
      });
      // Drag events
      dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
      });
      dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
      });
      dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        if (e.dataTransfer.files.length) {
          fileInput.files = e.dataTransfer.files;
          const filename = e.dataTransfer.files[0].name;
          const title = filename.replace(/\.[^/.]+$/, "");
          document.getElementById('name').value = title;
          dropZone.querySelector('p').textContent = `File selected: ${filename}`;
        }
      });
      // Update text on file select via click
      fileInput.addEventListener('change', () => {
        if (fileInput.files.length) {
          const filename = fileInput.files[0].name;
          const title = filename.replace(/\.[^/.]+$/, "");
          document.getElementById('name').value = title;
          dropZone.querySelector('p').textContent = `File selected: ${filename}`;
        }
      });
    });
  </script>
  <!-- Drag-and-Drop for Edit Modal -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const editDropZone = document.getElementById('editDropZone');
      const editFileInput = document.getElementById('edit_file');
      // Click to upload
      editDropZone.addEventListener('click', () => {
        editFileInput.click();
      });
      // Drag events
      editDropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        editDropZone.classList.add('dragover');
      });
      editDropZone.addEventListener('dragleave', () => {
        editDropZone.classList.remove('dragover');
      });
      editDropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        editDropZone.classList.remove('dragover');
        if (e.dataTransfer.files.length) {
          editFileInput.files = e.dataTransfer.files;
          editDropZone.querySelector('#editFileText').textContent = `New file selected: ${e.dataTransfer.files[0].name}`;
        }
      });
      // Update text on file select via click
      editFileInput.addEventListener('change', () => {
        if (editFileInput.files.length) {
          editDropZone.querySelector('#editFileText').textContent = `New file selected: ${editFileInput.files[0].name}`;
        }
      });
    });
  </script>
  <!-- Date Picker for Add Modal -->
  <script>
    function showAddMonth() {
      const year = document.getElementById('add_year').value;
      if (year) {
        document.getElementById('add_month').style.display = 'block';
        document.getElementById('add_day').style.display = 'none';
        document.getElementById('add_day').innerHTML = '<option value="">Select Day</option>';
      }
    }

    function showAddDay() {
      const month = document.getElementById('add_month').value;
      const year = document.getElementById('add_year').value;
      if (month && year) {
        const daySelect = document.getElementById('add_day');
        daySelect.style.display = 'block';
        daySelect.innerHTML = '<option value="">Select Day</option>';
        const daysInMonth = new Date(year, month, 0).getDate();
        for (let d = 1; d <= daysInMonth; d++) {
          const option = document.createElement('option');
          option.value = d;
          option.text = d;
          daySelect.appendChild(option);
        }
      }
    }
  </script>
  <!-- Add Form Validation -->
  <script>
    function validateAddForm() {
      // REMOVED: Year is now optional, so no validation alert for it
      return true;
    }
  </script>
  <!-- Open Add Modal Function (with old values if errors) -->
  <script>
    function openAddModal() {
      // Clear any previous errors visually if needed
      document.querySelectorAll('.error-message').forEach(el => el.style.display = 'none');
      document.getElementById('addResourceModal').classList.add('active');
    }
  </script>
  <!-- Edit Modal Population - Updated to use Resource_ID -->
  <script>
    function openEditModalFromRow(row) {
      try {
        const data = JSON.parse(row.dataset.resource);
        const authors = row.dataset.authors;
        const resourceId = data.Resource_ID;
        if (!resourceId || resourceId === null || resourceId === undefined || resourceId === 'null') {
          console.error('Invalid resource ID:', resourceId);
          alert('Error: Invalid resource ID. Please refresh the page and try again.');
          return;
        }
        const modal = document.getElementById('editResourceModal');
        const form = document.getElementById('editForm');
        form.action = '{{ url("/resources") }}/' + resourceId;
        document.getElementById('edit_name').value = data.Resource_Name || '';
        document.getElementById('edit_author').value = authors || '';
        document.getElementById('edit_description').value = data.Description || '';
        document.getElementById('edit_status').value = data.status || 'Available';
        document.getElementById('editFileText').textContent = 'Current file: ' + (data.File_Path ? data.File_Path.split('/').pop() : 'Unknown') + ' (drag & drop to replace)';
        window.currentResourceId = resourceId;

        // Set date selects
        const year = data.publish_year || null;
        const month = data.publish_month || null;
        const day = data.publish_day || null;
        document.getElementById('edit_year').value = year || '';
        document.getElementById('edit_month').style.display = year ? 'block' : 'none';
        document.getElementById('edit_month').value = month || '';
        document.getElementById('edit_day').style.display = (year && month) ? 'block' : 'none';
        document.getElementById('edit_day').value = day || '';

        if (year && month) {
          showEditDay();
        }

        // Populate tags
        const tagsContainer = document.getElementById('editTagsContainer');
        tagsContainer.innerHTML = '';
        const tags = data.tags || [];
        tags.forEach(tag => addTag('edit', tag));

        updateHiddenTags('edit');

        modal.classList.add('active');
      } catch (error) {
        console.error('Error opening edit modal:', error);
        alert('Error loading resource data. Please try again.');
      }
    }
  </script>
  <!-- Date Picker for Edit Modal -->
  <script>
    function showEditMonth() {
      const year = document.getElementById('edit_year').value;
      if (year) {
        document.getElementById('edit_month').style.display = 'block';
        document.getElementById('edit_day').style.display = 'none';
        document.getElementById('edit_day').innerHTML = '<option value="">Select Day</option>';
      } else {
        document.getElementById('edit_month').style.display = 'none';
        document.getElementById('edit_day').style.display = 'none';
      }
    }

    function showEditDay() {
      const month = document.getElementById('edit_month').value;
      const year = document.getElementById('edit_year').value;
      if (month && year) {
        const daySelect = document.getElementById('edit_day');
        daySelect.style.display = 'block';
        daySelect.innerHTML = '<option value="">Select Day</option>';
        const daysInMonth = new Date(year, month, 0).getDate();
        for (let d = 1; d <= daysInMonth; d++) {
          const option = document.createElement('option');
          option.value = d;
          option.text = d;
          daySelect.appendChild(option);
        }
      } else {
        document.getElementById('edit_day').style.display = 'none';
      }
    }
  </script>
  <!-- Edit Form Validation -->
  <script>
    function validateEditForm() {
      // REMOVED: Year is now optional, so no validation alert for it
      updateHiddenTags('edit');
      return true;
    }
  </script>
  <!-- Preview Modal Functions -->
  <script>
    function openPreviewModal(fileUrl) {
      document.getElementById('previewIframe').src = fileUrl + '#toolbar=0';
      document.getElementById('previewModal').classList.add('active');
    }
    function closePreviewModal() {
      document.getElementById('previewModal').classList.remove('active');
      document.getElementById('previewIframe').src = '';
    }
  </script>
  <!-- Tag Management Script -->
  <script>
    // Generic function to add tag
    function addTag(prefix, tagText) {
      tagText = tagText.trim().toLowerCase();
      if (!tagText) return;

      const container = document.getElementById(`${prefix}TagsContainer`);
      const existingTags = Array.from(container.querySelectorAll('.tag')).map(tag => tag.textContent.slice(0, -1).trim().toLowerCase());

      if (existingTags.includes(tagText)) return; // Prevent duplicates

      const tag = document.createElement('span');
      tag.className = 'tag';
      tag.innerHTML = `${tagText} <span class="tag-remove">×</span>`;
      tag.querySelector('.tag-remove').addEventListener('click', () => {
        tag.remove();
        updateHiddenTags(prefix);
      });
      container.appendChild(tag);
      updateHiddenTags(prefix);
    }

    // Update hidden input with comma-separated tags
    function updateHiddenTags(prefix) {
      const container = document.getElementById(`${prefix}TagsContainer`);
      const tags = Array.from(container.querySelectorAll('.tag')).map(tag => tag.textContent.slice(0, -1).trim());
      document.getElementById(`${prefix}TagsHidden`).value = tags.join(',');
    }

    // Handle keypress for tags input (add form)
    const addTagsInput = document.getElementById('addTagsInput');
    if (addTagsInput) {
      addTagsInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          const input = e.target;
          addTag('add', input.value);
          input.value = '';
        }
      });
    }

    // Handle keypress for tags input (edit form)
    document.getElementById('editTagsInput').addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        const input = e.target;
        addTag('edit', input.value);
        input.value = '';
      }
    });
    function confirmDeleteResource() {
      if (!window.currentResourceId) {
        alert('Please select a resource to delete.');
        return;
      }

      if (!confirm('Are you sure you want to delete this resource? This action cannot be undone.')) {
        return;
      }

      const deleteForm = document.getElementById('deleteForm');
      deleteForm.action = '{{ url("/resources") }}/' + window.currentResourceId;
      deleteForm.submit();
    }

    // Search functionality for resource table
    document.getElementById('resourceSearchInput').addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      const rows = document.querySelectorAll('#resourceTable tbody tr.resource-row');
      
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });

    // Scroll shadows for modal body
    function updateScrollShadows(element) {
      if (!element) return;
      
      const isScrolledToTop = element.scrollTop === 0;
      const isScrolledToBottom = element.scrollHeight - element.scrollTop <= element.clientHeight + 1;
      
      if (isScrolledToTop) {
        element.classList.add('scrolled-top');
      } else {
        element.classList.remove('scrolled-top');
      }
      
      if (isScrolledToBottom) {
        element.classList.add('scrolled-bottom');
      } else {
        element.classList.remove('scrolled-bottom');
      }
    }

    // Initialize scroll shadows for edit modal
    const editModalBody = document.querySelector('#editResourceModal .modal-body');
    if (editModalBody) {
      // Initial check
      updateScrollShadows(editModalBody);
      
      // Update on scroll
      editModalBody.addEventListener('scroll', function() {
        updateScrollShadows(this);
      });
      
      // Update when modal opens
      const editModal = document.getElementById('editResourceModal');
      if (editModal) {
        const observer = new MutationObserver(function(mutations) {
          mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
              if (editModal.classList.contains('active')) {
                setTimeout(() => updateScrollShadows(editModalBody), 100);
              }
            }
          });
        });
        observer.observe(editModal, { attributes: true });
      }
    }

    // Also handle add modal if it exists
    const addModalBody = document.querySelector('#addResourceModal .modal-body');
    if (addModalBody) {
      updateScrollShadows(addModalBody);
      addModalBody.addEventListener('scroll', function() {
        updateScrollShadows(this);
      });
    }
  </script>
  @include('partials.globalLoader')
</body>
</html>