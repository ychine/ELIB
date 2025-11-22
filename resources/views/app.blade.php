<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,100..900;1,100..900&family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&family=Kulim+Park:ital,wght@0,200;0,300;0,400;0,600;0,700;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    @vite([
        'resources/css/app.css',
        'resources/css/output.css',
        'resources/css/Inter.css',
        'resources/css/kulimpark.css',
        'resources/css/kantumruypro.css',
        'resources/js/app.js',
    ])
    @inertiaHead
</head>
<body class="bg-gray-50 antialiased">
    @inertia
    <div id="global-loading-root"></div>
</body>
</html>









