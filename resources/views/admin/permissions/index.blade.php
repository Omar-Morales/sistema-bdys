<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
            {{ __('Permisos de módulos por rol') }}
        </h1>
    </x-slot>

    <section class="space-y-6">
        @if (session('status'))
            <x-tailadmin.alert type="success">
                {{ session('status') }}
            </x-tailadmin.alert>
        @endif

        <x-tailadmin.section-card
            :title="__('Visibilidad de módulos')"
            :description="__('Define qué módulos pueden visualizar los usuarios según su rol.')"
        >
            <form method="POST" action="{{ route('admin.permissions.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                @foreach ($roles as $role)
                    <div class="rounded-2xl border border-slate-200 bg-white/80 p-4 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-800">{{ $role->name }}</h3>
                        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                            @foreach ($modules as $moduleKey => $module)
                                @php
                                    $viewPermission = $module['permissions']['view'] ?? null;
                                @endphp
                                @continue(!$viewPermission)

                                <label class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white/70 p-3 text-sm text-slate-600 shadow-sm">
                                    <input
                                        type="checkbox"
                                        name="roles[{{ $role->id }}][]"
                                        value="{{ $viewPermission }}"
                                        class="mt-1 rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        {{ $role->permissions->contains('name', $viewPermission) ? 'checked' : '' }}
                                    >
                                    <span>
                                        <span class="block font-medium text-slate-800">{{ $module['label'] }}</span>
                                        <span class="block text-xs text-slate-500">{{ $module['route'] ?? '' }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="flex justify-end">
                    <x-primary-button>{{ __('Guardar cambios') }}</x-primary-button>
                </div>
            </form>
        </x-tailadmin.section-card>
    </section>
</x-app-layout>
