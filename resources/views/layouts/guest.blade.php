@props(['maxWidth' => 'sm:max-w-md'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'POS Zeti') }} - Sistem Kasir & Manajemen Toko</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-800 bg-slate-100/90 antialiased min-h-screen flex flex-col justify-center items-center p-3 sm:p-6 lg:p-8">
        <div class="w-full {{ $maxWidth }}">
            {{ $slot }}
        </div>
    </body>
</html>
