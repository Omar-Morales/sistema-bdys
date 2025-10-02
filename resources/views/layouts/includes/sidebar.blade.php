<aside
    x-cloak
    class="fixed inset-y-0 left-0 z-30 flex w-72 flex-col bg-slate-900 text-slate-100 shadow-xl transition-transform duration-300 ease-in-out lg:static lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
>
    <div class="flex items-center gap-3 px-6 py-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <x-application-logo class="h-9 w-auto fill-current text-slate-100" />
            <span class="text-lg font-semibold tracking-tight">{{ config('app.name', 'Laravel') }}</span>
        </a>
    </div>
    <div class="flex-1 overflow-y-auto px-4 pb-8">
        <nav class="space-y-1">
            @foreach (config('modules.menus') as $module)
                @php
                    $routeName = $module['route'] ?? null;
                    $viewPermission = $module['permissions']['view'] ?? null;
                    $user = auth()->user();
                    $canView = $routeName && $viewPermission ? $user?->can($viewPermission) : false;
                    $isActive = $routeName ? request()->routeIs($routeName) || request()->routeIs($routeName . '.*') : false;
                @endphp
                @continue(!$routeName || !$viewPermission)
                @if ($canView && \Illuminate\Support\Facades\Route::has($routeName))
                    <a
                        href="{{ route($routeName) }}"
                        class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors duration-150 {{
                            $isActive
                                ? 'bg-slate-800 text-white shadow-sm'
                                : 'text-slate-200 hover:bg-slate-800 hover:text-white'
                        }}"
                    >
                        <span class="h-2 w-2 rounded-full border border-white/40 bg-white/40 transition group-hover:bg-white"></span>
                        <span>{{ __($module['label']) }}</span>
                    </a>
                @endif
            @endforeach
        </nav>
    </div>
</aside>
