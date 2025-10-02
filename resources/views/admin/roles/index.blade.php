<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
            {{ __('Asignar permisos a roles') }}
        </h1>
    </x-slot>

    <section class="space-y-6">
        @if (session('status'))
            <x-tailadmin.alert type="success">
                {{ session('status') }}
            </x-tailadmin.alert>
        @endif

        <x-tailadmin.section-card
            :title="__('Configuración de permisos')"
            :description="__('Selecciona los permisos que corresponden a cada rol disponible en la plataforma.')"
        >
            <form method="POST" action="{{ route('admin.roles.permissions.update') }}" class="space-y-8">
                @csrf
                @method('PUT')

                @foreach ($roles as $role)
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-slate-800">{{ $role->name }}</h3>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                            @foreach ($modules as $moduleKey => $module)
                                @php
                                    $permissions = $module['permissions'] ?? [];
                                    $permissionLabels = [
                                        'view' => __('Ver'),
                                        'manage' => __('Gestionar'),
                                    ];
                                @endphp

                                <div class="rounded-2xl border border-slate-200 bg-white/80 p-4 shadow-sm">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-sm font-semibold text-slate-900">{{ $module['label'] }}</span>
                                        @isset($module['route'])
                                            <span class="text-xs text-slate-500">{{ $module['route'] }}</span>
                                        @endisset
                                    </div>

                                    <div class="mt-4 space-y-2">
                                        @foreach ($permissions as $type => $permissionName)
                                            <label class="flex items-center gap-2 text-sm text-slate-600">
                                                <input
                                                    type="checkbox"
                                                    name="roles[{{ $role->id }}][]"
                                                    value="{{ $permissionName }}"
                                                    class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                    {{ $role->permissions->contains('name', $permissionName) ? 'checked' : '' }}
                                                >
                                                <span>{{ $permissionLabels[$type] ?? ucfirst($type) }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div>
                    <x-primary-button>{{ __('Guardar cambios') }}</x-primary-button>
                </div>
            </form>
        </x-tailadmin.section-card>
    </section>
</x-app-layout>
