<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Permisos de módulos por rol') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('status'))
                    <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.permissions.update') }}" class="space-y-8">
                    @csrf
                    @method('PUT')

                    @foreach ($roles as $role)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $role->name }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($modules as $moduleKey => $module)
                                    @php
                                        $permissionName = $module['permission'];
                                    @endphp
                                    <label class="flex items-start space-x-2">
                                    <input
                                        type="checkbox"
                                        name="roles[{{ $role->id }}][]"
                                        value="{{ $permissionName }}"
                                        class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        {{ $role->permissions->contains('name', $permissionName) ? 'checked' : '' }}
                                    >
                                        <span>
                                            <span class="block font-medium text-gray-700">{{ $module['label'] }}</span>
                                            <span class="block text-xs text-gray-500">{{ $module['route'] }}</span>
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
            </div>
        </div>
    </div>
</x-app-layout>
