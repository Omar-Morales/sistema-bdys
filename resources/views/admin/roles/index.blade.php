<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asignar permisos a roles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('status'))
                    <div class="mb-4 text-green-600">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.roles.permissions.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-8">
                        @foreach ($roles as $role)
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700 mb-4">{{ $role->name }}</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach ($modules as $moduleKey => $module)
                                        @php
                                            $permissions = $module['permissions'] ?? [];
                                            $permissionLabels = [
                                                'view' => __('Ver'),
                                                'manage' => __('Gestionar'),
                                            ];
                                        @endphp
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <span class="block font-medium text-gray-800">{{ $module['label'] }}</span>
                                                    @isset($module['route'])
                                                        <span class="block text-xs text-gray-500">{{ $module['route'] }}</span>
                                                    @endisset
                                                </div>
                                            </div>
                                            <div class="mt-3 space-y-2">
                                                @foreach ($permissions as $type => $permissionName)
                                                    <label class="flex items-center space-x-2 text-sm text-gray-700">
                                                        <input
                                                            type="checkbox"
                                                            name="roles[{{ $role->id }}][]"
                                                            value="{{ $permissionName }}"
                                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
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
                    </div>

                    <div class="mt-6">
                        <x-primary-button>{{ __('Guardar cambios') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
