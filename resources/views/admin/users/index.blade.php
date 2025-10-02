<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
                {{ __('Usuarios') }}
            </h1>
            <a href="{{ route('admin.users.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                {{ __('Crear usuario') }}
            </a>
        </div>
    </x-slot>

    <section class="space-y-6">
        @if (session('status'))
            <x-tailadmin.alert type="success">
                {{ session('status') }}
            </x-tailadmin.alert>
        @endif

        <x-tailadmin.table-card
            :title="__('Usuarios registrados')"
            :description="__('Administra las cuentas, roles y almacenes asignados en el sistema.')"
            :headers="[
                ['label' => __('Nombre')],
                ['label' => __('Correo')],
                ['label' => __('Rol')],
                ['label' => __('Almacén')],
                ['label' => ''],
            ]"
        >
            @forelse ($users as $user)
                <tr class="odd:bg-white even:bg-slate-50">
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900">{{ $user->name }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ $user->email }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ $user->roles->pluck('name')->implode(', ') ?: __('Sin rol') }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ optional($user->almacen)->nombre ?: __('N/A') }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}"
                                class="inline-flex items-center gap-1 rounded-lg border border-indigo-200 bg-white px-3 py-1.5 text-sm font-medium text-indigo-600 shadow-sm transition hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                {{ __('Editar') }}
                            </a>
                            <a href="{{ route('admin.users.permissions.edit', $user) }}"
                                class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2">
                                {{ __('Permisos') }}
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-slate-500">
                        {{ __('No hay usuarios registrados.') }}
                    </td>
                </tr>
            @endforelse

            @if ($users->hasPages())
                <x-slot:footer>
                    {{ $users->links() }}
                </x-slot:footer>
            @endif
        </x-tailadmin.table-card>
    </section>
</x-app-layout>
