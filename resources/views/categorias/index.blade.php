<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
                {{ __('Categorías') }}
            </h1>
            @can('manage categorias')
                <a href="{{ route('categorias.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('Agregar categoría') }}
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
            :title="__('Categorías registradas')"
            :description="__('Organiza el catálogo de productos por categorías.')"
            :headers="[
                ['label' => __('Nombre')],
                ['label' => __('Fecha de creación')],
                ['label' => __('Acciones'), 'class' => 'text-right'],
            ]"
        >
            @forelse ($categorias as $categoria)
                <tr class="odd:bg-white even:bg-slate-50">
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900">
                        <a href="{{ route('categorias.show', $categoria) }}" class="text-indigo-600 transition hover:text-indigo-500">
                            {{ $categoria->nombre }}
                        </a>
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                        {{ $categoria->created_at?->format('d/m/Y H:i') }}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                        @can('manage categorias')
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('categorias.edit', $categoria) }}"
                                    class="inline-flex items-center gap-2 rounded-lg border border-indigo-200 bg-white px-3 py-1.5 text-sm font-medium text-indigo-600 shadow-sm transition hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    {{ __('Actualizar') }}
                                </a>
                                <form action="{{ route('categorias.destroy', $categoria) }}" method="POST"
                                    onsubmit="return confirm('{{ __('¿Eliminar esta categoría?') }}');">
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
                    <td colspan="3" class="px-6 py-4 text-center text-sm text-slate-500">
                        {{ __('No se encontraron categorías.') }}
                    </td>
                </tr>
            @endforelse

            @if ($categorias->hasPages())
                <x-slot:footer>
                    {{ $categorias->links() }}
                </x-slot:footer>
            @endif
        </x-tailadmin.table-card>
    </section>
</x-app-layout>
