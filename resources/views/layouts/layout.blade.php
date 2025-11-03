<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @yield('title')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="flex flex-col min-h-screen px-8 md:px-14 lg:px-32">
    <main class="">
        @include('components.header')
        <div class="flex-grow">
            <!-- Success Alert -->
            @if (session('success'))
                <x-modal-success />
            @endif

            <!-- Error Alert -->
            @if (session('error'))
                <x-modal-error />
            @endif

            @yield('main')
        </div>
        @include('components.footer')
    </main>

    <!-- Alpine.js for dropdowns -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</body>

</html>