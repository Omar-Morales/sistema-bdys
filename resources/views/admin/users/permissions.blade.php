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
                        @foreach ($modules as $moduleKey => $moduleLabel)
                            @php
                                $permissionName = 'view ' . $moduleKey;
                            @endphp
                            <label class="flex items-start space-x-2">
                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permissionName }}"
                                    class="mt-1"
                                    {{ in_array($permissionName, $userPermissions, true) ? 'checked' : '' }}
                                >
                                <span>{{ $moduleLabel }}</span>
                            </label>
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
