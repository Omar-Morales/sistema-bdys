<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="relative flex min-h-screen flex-col bg-white dark:bg-gray-900 lg:flex-row">
            <div class="flex w-full flex-1 flex-col">
                <div class="w-full max-w-md px-6 pt-10 sm:px-10">
                    <a href="/" class="inline-flex items-center text-sm font-medium text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none">
                            <path d="M12.708 5 7.5 10.208 12.708 15.417" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="ml-3">Volver al inicio</span>
                    </a>
                </div>
                <div class="flex flex-1 flex-col justify-center px-6 py-12 sm:px-10">
                    <div class="mb-10 text-center sm:mb-12 sm:text-left">
                        <a href="/" class="inline-flex items-center justify-center">
                            <img src="{{ asset('images/logo/auth-logo.svg') }}" alt="TailAdmin" class="h-10 sm:h-12" />
                        </a>
                    </div>
                    <div class="relative rounded-2xl bg-white/90 p-8 shadow-xl shadow-gray-200/60 ring-1 ring-gray-100 backdrop-blur dark:bg-gray-900/80 dark:shadow-gray-900/40 dark:ring-gray-800">
                        {{ $slot }}
                    </div>
                </div>
            </div>
            <div class="relative hidden w-full flex-1 items-center justify-center overflow-hidden bg-gradient-to-br from-indigo-700 via-blue-600 to-sky-500 lg:flex">
                <div class="pointer-events-none absolute inset-0">
                    <img src="{{ asset('images/shape/grid-01.svg') }}" alt="Decorative grid" class="absolute right-0 top-0 w-60 max-w-xs opacity-70 xl:w-[450px]" />
                    <img src="{{ asset('images/shape/grid-01.svg') }}" alt="Decorative grid" class="absolute bottom-0 left-0 w-60 max-w-xs rotate-180 opacity-70 xl:w-[450px]" />
                </div>
                <div class="relative z-10 flex flex-col items-center px-6 text-center text-white sm:px-10">
                    <img src="{{ asset('images/logo/auth-logo.svg') }}" alt="TailAdmin" class="mb-6 h-12" />
                    <p class="max-w-sm text-sm font-medium text-white/90">
                        Free and Open-Source Tailwind CSS Admin Dashboard Template
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
