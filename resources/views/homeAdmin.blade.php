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

  <title>Admin Home | ISU StudyGo</title>

  <style>
    /* Sidebar transition and width */
    .sidebar {
      width: 4rem;
      transition: all 0.3s cubic-bezier(0.215, 0.610, 0.355, 1);
      overflow: hidden;
      padding-left: 0.5rem;
      padding-right: 0.5rem;
    }

    .sidebar:hover {
      width: 18rem;
    }

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

    .isu-studygo-border-logo {
      opacity: 0;
    }

    .sidebar:hover .isu-studygo-border-logo {
      opacity: 1;
    }

    .sidebar-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: all 0.3s ease;
      min-height: 100%; /* Ensure the container takes full height */
    }

    .sidebar-icons {
      transform: translate(10%, 5%);
    }

    .sidebar:hover .sidebar-content {
      align-items: flex-start;
      padding-left: 0.5rem;
      padding-right: 0.5rem;
    }

    .sidebar:hover + .main-content {
      margin-left: 15rem;
      margin-top: 0;
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
        <div class="mt-5 w-full h-12 bg-green-500 rounded-xl flex items-center gap-3 cursor-pointer">
          <img 
            src="{{ Vite::asset('resources/images/Home.png') }}" 
            alt="Library" 
            class="w-10 h-10 sidebar-icons"
          />
          <span class="label text-lg">Home</span>
        </div>
        <div class="w-full h-12 bg-green-800 rounded-xl shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)] flex items-center gap-3 cursor-pointer">
          <i class="fa-solid fa-book text-2xl"></i>
          <span class="label text-lg">Featured</span>
        </div>
        <div class="w-full h-12 bg-green-800 rounded-xl shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)] flex items-center gap-3 cursor-pointer">
          <i class="fa-solid fa-user text-2xl"></i>
          <span class="label text-lg">Community Uploads</span>
        </div>
        <div class="w-full h-12 bg-green-800 rounded-xl shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)] flex items-center gap-3 cursor-pointer">
          <i class="fa-solid fa-user text-2xl"></i>
          <span class="label text-lg">Your Shelf</span>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-auto w-full h-12 bg-green-800 rounded-xl shadow-[inset_0px_4px_4px_0px_rgba(0,0,0,0.25)] flex items-center gap-3">
          @csrf
          <button type="submit" class="flex items-center gap-3 w-full h-full bg-transparent border-none text-white cursor-pointer">
            <i class="fa-solid fa-sign-out-alt text-2xl sidebar-icons"></i>
            <span class="label text-lg">Logout</span>
          </button>
        </form>
      </div>
    </div>

    <div class="flex flex-col flex-1 transition-all duration-300 main-content">
      <!-- Container for the hero section -->
      <div class="hero-container relative w-full greenhue z-1">
        <!-- Hero text -->
        <img 
          src="{{ Vite::asset('resources/images/libgreenptr.jpg') }}" 
          alt="Library" 
          class="w-full h-70 z-[-1] object-cover absolute"
          style="object-position: 70% middle;"
        />
        <div class="herotext h-70 ml-30 flex relative z-2">
          <div class="column">
            <h1 style="transform: translateY(90%); line-height: 86.402%; font-family: 'Kulim Park', sans-serif; font-weight: 600; letter-spacing: -1.3px; font-size: 45px; text-shadow: 0 4px 4px #000; color: #FFF;">
              Bridging knowledge <br>
              from one campus <br>
              to another
            </h1>
          </div>
        </div>
        <div class="homediv m-5 border-2 h-50000 ml-21 rounded-md bg-white shadow-sm">
          <p class="mx-50 leading-[100px]">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed fermentum orci nunc, ut facilisis purus finibus vitae. Etiam et neque erat. Nulla facilisis diam finibus purus aliquam tempor. Vivamus ac nulla a turpis vehicula ultrices quis at odio. Pellentesque orci ante, pharetra at nulla at, bibendum cursus eros. Etiam malesuada risus id laoreet varius. Curabitur id vehicula sem. In neque ipsum, sagittis vel varius nec, maximus a ipsum. Maecenas vel molestie nunc, nec dapibus diam. Pellentesque scelerisque lacus eu mattis semper.
            Curabitur massa arcu, tempor eu nulla ut, ullamcorper ultrices nibh. Sed placerat, odio non lacinia luctus, justo eros lacinia magna, eget accumsan arcu ligula ac elit. Aliquam erat volutpat. Morbi consectetur, sem a aliquet rhoncus, tortor lectus egestas metus, a blandit lectus diam in lorem. Etiam placerat ex mauris, non elementum massa fringilla ac. Phasellus vitae nunc a ipsum porttitor gravida. Sed molestie, eros id pellentesque pharetra, ligula urna varius lacus, id posuere arcu urna ac mauris. Sed quam nibh, ullamcorper et felis quis, accumsan blandit dolor. Morbi volutpat sapien ac commodo laoreet. Nulla cursus ex a odio pellentesque, in tincidunt justo ullamcorper. Etiam aliquet finibus velit ut viverra. Nulla facilisi. Nulla urna quam, tempor in odio non, venenatis rhoncus nibh. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Donec dignissim volutpat dolor, id lobortis enim accumsan vel.
            Praesent interdum dui et risus rhoncus maximus. Pellentesque nec lorem nisl. In hac habitasse platea dictumst. Nam vehicula ornare massa non blandit. Duis eget massa semper, viverra arcu sit amet, tincidunt justo. Donec at mi rhoncus, maximus lectus ac, rutrum diam. Suspendisse aliquet libero velit, nec aliquam metus luctus quis. Phasellus vitae justo dignissim, pellentesque diam ac, eleifend erat. Ut ac mi id sapien tincidunt sollicitudin euismod et est. Mauris accumsan eleifend lobortis. Quisque et lacus eu lorem porta ornare ut sit amet risus. Nam nulla elit, porttitor id orci a, tempus mattis urna.
            Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Proin sed eleifend massa, in vestibulum augue. Quisque elementum vitae sem vehicula bibendum. Quisque viverra enim vitae nisi aliquam, nec imperdiet augue convallis. Aenean at lorem vitae diam consectetur convallis ac eu ligula. Proin aliquet sodales nisi non fermentum. Donec urna tortor, ultricies efficitur mollis vitae, tincidunt ut nulla. Nam vel nunc eros. Etiam a ante libero. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
            Cras quis nisl sit amet enim viverra pellentesque. In accumsan blandit tellus, quis finibus nisi feugiat eu. Phasellus ornare sem sapien, et laoreet lacus sagittis ut. Nunc lacinia nunc sit amet maximus laoreet. Maecenas rutrum, enim sed ullamcorper sagittis, ipsum justo tempor ipsum, sed elementum nunc ante sit amet odio. Nulla suscipit ullamcorper metus ut commodo. Donec convallis diam ut orci commodo, in molestie nunc cursus. In hac habitasse platea dictumst. Mauris accumsan imperdiet commodo. Sed mauris dolor, suscipit ut lectus non, dapibus porttitor metus. Nulla efficitur hendrerit mattis. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Donec malesuada odio non turpis semper, et molestie velit egestas. Quisque faucibus erat eget commodo facilisis. Curabitur facilisis pretium nisi ac ultrices. Suspendisse consectetur et arcu a mattis.
          </p>
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
</body>
</html>