<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
                {{ __('Vendedores') }}
            </h1>
            @can('manage vendedores')
                <a href="{{ route('vendedores.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('Agregar vendedor') }}
                </a>
            @endcan
        </div>
    </x-slot>

    <section class="space-y-6">
        @if (session('status'))
            <x-tailadmin.alert type="success">
                {{ session('status') }}
            </x-tailadmin.alert>
        @endif

        <x-tailadmin.table-card
            :title="__('Vendedores registrados')"
            :description="__('Controla los vendedores y su asignación de tiendas.')"
            :headers="[
                ['label' => __('Nombre')],
                ['label' => __('Tienda')],
                ['label' => __('Teléfono')],
                ['label' => __('Acciones'), 'class' => 'text-right'],
            ]"
        >
            @forelse ($vendedores as $vendedor)
                <tr class="odd:bg-white even:bg-slate-50">
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900">
                        <a href="{{ route('vendedores.show', $vendedor) }}" class="text-indigo-600 transition hover:text-indigo-500">
                            {{ $vendedor->nombre }}
                        </a>
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ $vendedor->tienda?->nombre ?? __('Sin tienda') }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ $vendedor->telefono }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                        @can('manage vendedores')
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('vendedores.edit', $vendedor) }}"
                                    class="inline-flex items-center gap-2 rounded-lg border border-indigo-200 bg-white px-3 py-1.5 text-sm font-medium text-indigo-600 shadow-sm transition hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    {{ __('Actualizar') }}
                                </a>
                                <form action="{{ route('vendedores.destroy', $vendedor) }}" method="POST"
                                    onsubmit="return confirm('{{ __('¿Eliminar este vendedor?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 rounded-lg border border-rose-200 bg-white px-3 py-1.5 text-sm font-medium text-rose-600 shadow-sm transition hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">
                                        {{ __('Eliminar') }}
                                    </button>
                                </form>
                            </div>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-slate-500">
                        {{ __('No se encontraron vendedores.') }}
                    </td>
                </tr>
            @endforelse

            @if ($vendedores->hasPages())
                <x-slot:footer>
                    {{ $vendedores->links() }}
                </x-slot:footer>
            @endif
        </x-tailadmin.table-card>
    </section>
</x-app-layout>
