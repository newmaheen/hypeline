<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hypeline') }}</title>

    {{-- HYPELINE Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/hypeline-logo-2.png') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    {{-- Scripts & Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50 flex flex-col min-h-screen">

    {{-- Universal Navigation / Header --}}
    @include('layouts.navigation')

    {{-- Optional Page Heading (if passed via Breeze) --}}
    @isset($header)
        <header class="bg-white shadow-sm border-b border-gray-100">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    {{-- Page Content: Slot (Breeze Component) অথবা Yield (Blade Extends) দুটোই সাপোর্ট করবে --}}
    <main class="flex-grow">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    {{-- Universal Footer --}}
    @include('layouts.footer')

    @stack('scripts')
</body>

</html>