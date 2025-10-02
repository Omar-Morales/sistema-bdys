<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
                {{ __('Productos') }}
            </h1>
            @can('manage productos')
                <a href="{{ route('productos.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('Agregar producto') }}
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
            :title="__('Productos disponibles')"
            :description="__('Consulta el catálogo de productos y gestiona su información.')"
            :headers="[
                ['label' => __('Nombre')],
                ['label' => __('Categoría')],
                ['label' => __('Unidad')],
                ['label' => __('Precio'), 'class' => 'text-right'],
                ['label' => __('Acciones'), 'class' => 'text-right'],
            ]"
        >
            @forelse ($productos as $producto)
                <tr class="odd:bg-white even:bg-slate-50">
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900">
                        <a href="{{ route('productos.show', $producto) }}" class="text-indigo-600 transition hover:text-indigo-500">
                            {{ $producto->nombre }}
                        </a>
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                        {{ $producto->categoria?->nombre ?? __('Sin categoría') }}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                        {{ ucfirst($producto->unidad) }}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-slate-600">
                        S/ {{ number_format($producto->precio_referencial, 2) }}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                        @can('manage productos')
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('productos.edit', $producto) }}"
                                    class="inline-flex items-center gap-2 rounded-lg border border-indigo-200 bg-white px-3 py-1.5 text-sm font-medium text-indigo-600 shadow-sm transition hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    {{ __('Actualizar') }}
                                </a>
                                <form action="{{ route('productos.destroy', $producto) }}" method="POST"
                                    onsubmit="return confirm('{{ __('¿Eliminar este producto?') }}');">
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
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-slate-500">
                        {{ __('No se encontraron productos.') }}
                    </td>
                </tr>
            @endforelse

            @if ($productos->hasPages())
                <x-slot:footer>
                    {{ $productos->links() }}
                </x-slot:footer>
            @endif
        </x-tailadmin.table-card>
    </section>
</x-app-layout>
