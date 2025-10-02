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
    <body class="font-sans antialiased bg-slate-100" x-data="{ sidebarOpen: false }">
        <div class="flex min-h-screen overflow-hidden">
            <div
                x-cloak
                class="fixed inset-0 z-20 bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 lg:hidden"
                x-show="sidebarOpen"
                x-transition.opacity
                @click="sidebarOpen = false"
            ></div>

            @include('layouts.includes.sidebar')

            <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
                @include('layouts.includes.topbar')

                <main class="flex-1">
                    <div class="mx-auto w-full max-w-screen-2xl px-4 py-6 sm:px-6 md:px-8 2xl:px-12">
                        @isset($header)
                            <div class="mb-6 flex flex-col gap-4 rounded-xl border border-slate-200 bg-white/80 p-5 shadow-sm backdrop-blur lg:flex-row lg:items-center lg:justify-between">
                                {{ $header }}
                            </div>
                        @endisset

                        <section class="space-y-6">
                            {{ $slot }}
                        </section>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
