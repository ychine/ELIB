<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}">
   @vite(['resources/css/app.css'])
  @vite('resources/css/app.js')
    @vite(['resources/css/output.css'])

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Tiny5&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Jersey+20&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

  <title>Home | ISU StudyGo</title>

  <style>
    .tiny5-regular {
      font-family: "Tiny5", sans-serif;
      font-weight: 400;
      font-style: normal;
    }
    .jersey-20-regular {
      font-family: "Jersey 20", sans-serif;
      font-weight: 400;
      font-style: normal;
    }
    .plus-jakarta-sans-extralight {
      font-family: "Plus Jakarta Sans", sans-serif;
      font-optical-sizing: auto;
      font-weight: 200;
      font-style: normal;
    }

    .plus-jakarta-sans-light {
      font-family: "Plus Jakarta Sans", sans-serif;
      font-optical-sizing: auto;
      font-weight: 300;
      font-style: normal;
    }

    .plus-jakarta-sans-regular {
      font-family: "Plus Jakarta Sans", sans-serif;
      font-optical-sizing: auto;
      font-weight: 400;
      font-style: normal;
    }

    .plus-jakarta-sans-medium {
      font-family: "Plus Jakarta Sans", sans-serif;
      font-optical-sizing: auto;
      font-weight: 500;
      font-style: normal;
    }

    .plus-jakarta-sans-semibold {
      font-family: "Plus Jakarta Sans", sans-serif;
      font-optical-sizing: auto;
      font-weight: 600;
      font-style: normal;
    }

    .plus-jakarta-sans-bold {
      font-family: "Plus Jakarta Sans", sans-serif;
      font-optical-sizing: auto;
      font-weight: 700;
      font-style: normal;
    }

    .plus-jakarta-sans-extrabold {
      font-family: "Plus Jakarta Sans", sans-serif;
      font-optical-sizing: auto;
      font-weight: 800;
      font-style: normal;
    }




    .sidebar:hover {
      width: calc(1/6 * 100%) /* 20% */;
      transition: all 0.3s cubic-bezier(0.215, 0.610, 0.355, 1);
    }
  </style>
</head>
<body>
  <div class="bg-yellow-50 w-full h-[100vh] flex">
    

    <!-- Sidebar -->
    <div class="sidebar transition-all duration-[0.3s] ease-out bg-amber-500 px-3 border-2 w-1/25 flex justify-start overflow-x-hidden z-20">
        Menu
    </div>
    
    <!--DIV PARA SA NAV-->
    
        <div class="flex flex-col flex-grow">
            <div class="w-[1440px] h-16 bg-gradient-to-r from-green-950 to-green-700 shadow-[0px_4px_6.800000190734863px_2px_rgba(0,0,0,0.40)]">
            
                <span class="text-5xl jersey-20-regular pl-3">Yeeterians</span>
                <div class="text-md flex space-x-4 gap-5 pr-6 plus-jakarta-sans-semibold">
                    <span>Home</span>
                    <span >About</span>
                    <span>Yeeterians</span>
                </div>
            </div>

          <!--PROFILE CONTAINERzzz--> 
          <div class=" block flex-grow m-2 border-2 h-60 rounded-md z-1">

          </div>
       
        </div>

        <img src="../res/svbg.jpg" class="absolute inset-0 w-screen h-screen blur-lg object-cover" />

  </div>
  
  
</body>
</html>
  