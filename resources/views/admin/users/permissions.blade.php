<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Permisos personalizados de :user', ['user' => $user->name]) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.users.permissions.update', $user) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <p class="text-sm text-gray-600">
                        {{ __('Selecciona los módulos que quieres habilitar específicamente para el usuario. Estos permisos se suman a los del rol asignado.') }}
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($modules as $moduleKey => $module)
                            @php
                                $permissionLabels = [
                                    'view' => __('Ver'),
                                    'manage' => __('Gestionar'),
                                ];
                            @endphp
                            <div class="border border-gray-200 rounded-lg p-4">
                                <span class="block font-medium text-gray-800">{{ $module['label'] }}</span>
                                @isset($module['route'])
                                    <span class="block text-xs text-gray-500">{{ $module['route'] }}</span>
                                @endisset
                                <div class="mt-3 space-y-2">
                                    @foreach (($module['permissions'] ?? []) as $type => $permissionName)
                                        <label class="flex items-center space-x-2 text-sm text-gray-700">
                                            <input
                                                type="checkbox"
                                                name="permissions[]"
                                                value="{{ $permissionName }}"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                {{ in_array($permissionName, $userPermissions, true) ? 'checked' : '' }}
                                            >
                                            <span>{{ $permissionLabels[$type] ?? ucfirst($type) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <x-input-error :messages="$errors->get('permissions')" class="mt-2" />

                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-400 focus:ring ring-gray-200 transition ease-in-out duration-150">
                            {{ __('Cancelar') }}
                        </a>
                        <x-primary-button>{{ __('Guardar cambios') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
