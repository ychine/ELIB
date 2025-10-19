<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}">
  @vite(['resources/css/app.css', 'resources/css/output.css', 'resources/css/Inter.css', 'resources/css/kulimpark.css'])

  <title>Admin Home | ISU StudyGo</title>

  <style>
    /* Sidebar transition and width */
    .sidebar {
      width: 4rem; /* collapsed width */
      transition: all 0.3s cubic-bezier(0.215, 0.610, 0.355, 1);
      overflow: hidden;
      padding-left: 0.5rem;
      padding-right: 0.5rem;
    }

    .sidebar:hover {
      width: 18rem; /* expanded width */
    }

    /* Smoothly show/hide text labels */
    .sidebar .label {
      opacity: 0;
      transform: translateX(-10px);
      transition: all 0.3s ease;
      white-space: nowrap;
    }

    .sidebar:hover .label {
      opacity: 1;
      transform: translateX(0);
    }

    /* Adjust inner content spacing */
    .sidebar-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: all 0.3s ease;
    }

    .sidebar:hover .sidebar-content {
      align-items: flex-start;
      padding-left: 1rem;
      padding-right: 1rem;
    
    }
  </style>
</head>
<body class="bg-yellow-50">
  <div class="w-full h-[100vh] relative">

    <!-- Navigation -->
    <div class="absolute w-full top-0 left-0 flex justify-between items-center px-4 py-2 z-10 bg-gradient-to-r from-green-950 to-green-700 shadow-[0px_4px_6.8px_2px_rgba(0,0,0,0.40)]">
      <span class="text-5xl jersey-20-regular pl-3"></span>
      <input class="pl-7 w-[545px] h-11 bg-zinc-300 rounded-[34px] shadow-[inset_0px_4px_4px_rgba(0,0,0,0.25)]" type="text" placeholder="Search for books, papers..">
      <div class="text-md flex space-x-4 gap-5 pr-6 plus-jakarta-sans-semibold">
        <span>Profile</span>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="fixed top-0 left-0 h-full bg-[#149637] shadow-[5px_-10px_22.5px_2px_rgba(0,0,0,0.59)] rounded-tr-[50px] sidebar z-20 pt-20">
      <div class="sidebar-content space-y-2 text-white">
        <div class="w-full h-12 bg-green-500 rounded-xl flex items-center gap-3 cursor-pointer">
          <i class="fa-solid fa-house text-2xl"></i>
          <span class="label text-lg">Home</span>
        </div>
        <div class="w-full h-12 bg-green-800 rounded-xl shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)] flex items-center gap-3">
          <i class="fa-solid fa-book text-2xl"></i>
          <span class="label text-lg">Library</span>
        </div>
        <div class="flex items-center gap-3">
          <i class="fa-solid fa-user text-2xl"></i>
          <span class="label text-lg">Profile</span>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col flex-grow pt-20 pl-20 transition-all duration-300">
      <div class="m-5 border-2 h-60 rounded-md bg-white shadow-sm"></div>
    </div>
  </div>

  <!-- Optional: Font Awesome icons -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
