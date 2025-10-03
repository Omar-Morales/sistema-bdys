<header class="sticky top-0 z-20 flex w-full items-center justify-between border-b border-slate-200 bg-white px-4 py-4 shadow-sm lg:px-6">
    <div class="flex items-center gap-3">
        <button
            type="button"
            class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-1 lg:hidden"
            @click.stop="sidebarOpen = !sidebarOpen"
        >
            <span class="sr-only">{{ __('Toggle sidebar') }}</span>
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 6h16"></path>
                <path d="M4 12h16"></path>
                <path d="M4 18h16"></path>
            </svg>
        </button>
        <div class="hidden text-sm font-medium text-slate-500 lg:block">
            {{ now()->translatedFormat('l, d \d\e F') }}
        </div>
    </div>
    <div class="flex items-center gap-4">
        <div class="hidden items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm text-slate-500 shadow-sm sm:flex">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="7"></circle>
                <path d="m20 20-3.5-3.5"></path>
            </svg>
            <input
                id="search-input"
                type="search"
                class="w-32 bg-transparent text-sm text-slate-600 placeholder:text-slate-400 focus:outline-none focus:ring-0 sm:w-48"
                placeholder="{{ __('Buscar...') }}"
                aria-label="{{ __('Buscar') }}"
            >
            <button
                id="search-button"
                type="button"
                class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-1"
            >
                <span class="sr-only">{{ __('Buscar') }}</span>
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="7"></circle>
                    <path d="m20 20-3.5-3.5"></path>
                </svg>
            </button>
        </div>
        <x-dropdown align="right" width="56" content-classes="bg-white/95 p-2 backdrop-blur">
            <x-slot name="trigger">
                <button class="flex items-center gap-3 rounded-full border border-slate-200 bg-white px-3 py-2 text-left text-sm font-medium text-slate-600 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-1">
                    <div class="flex flex-col leading-tight">
                        <span class="font-semibold text-slate-700">{{ Auth::user()->name }}</span>
                        <span class="text-xs text-slate-400">{{ Auth::user()->email }}</span>
                    </div>
                    <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.939l3.71-3.71a.75.75 0 111.06 1.061l-4.24 4.24a.75.75 0 01-1.06 0l-4.25-4.25a.75.75 0 01.01-1.06z" clip-rule="evenodd" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</header>
