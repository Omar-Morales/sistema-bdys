<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
            {{ __('Cierre diario de almacenes') }}
        </h1>
    </x-slot>

    <section class="space-y-6">
        <x-tailadmin.section-card
            :title="__('Filtros de búsqueda')"
            :description="__('Define la fecha y el almacén para consultar los cierres generados.')"
        >
            <form method="GET" class="grid grid-cols-1 items-end gap-4 md:grid-cols-4">
                <div>
                    <x-input-label for="fecha" value="{{ __('Fecha') }}" />
                    <x-text-input id="fecha" type="date" name="fecha" class="mt-1 block w-full" :value="$fecha" />
                </div>
                <div>
                    <x-input-label for="almacen_id" value="{{ __('Almacén') }}" />
                    <select
                        id="almacen_id"
                        name="almacen_id"
                        class="mt-1 block w-full rounded-xl border border-slate-300 bg-white/95 px-3 py-2 text-sm text-slate-700 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/60"
                    >
                        <option value="">{{ __('Todos') }}</option>
                        @foreach ($almacenes as $id => $nombre)
                            <option value="{{ $id }}" @selected($almacenId == $id)>{{ $nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2 flex flex-wrap items-center gap-3">
                    <x-primary-button>{{ __('Filtrar') }}</x-primary-button>
                    <a href="{{ route('supervisor.cierres.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2">
                        {{ __('Limpiar') }}
                    </a>
                </div>
            </form>
        </x-tailadmin.section-card>

        <x-tailadmin.table-card
            :title="__('Resumen de cierres')"
            :description="__('Detalle consolidado de ventas y cobranzas por almacén y fecha.')"
            :headers="[
                ['label' => __('Fecha')],
                ['label' => __('Almacén')],
                ['label' => __('Total ventas'), 'class' => 'text-right'],
                ['label' => __('Total pagado'), 'class' => 'text-right'],
                ['label' => __('Saldo pendiente'), 'class' => 'text-right'],
                ['label' => __('Vuelto'), 'class' => 'text-right'],
            ]"
        >
            @forelse ($cierres as $cierre)
                <tr class="odd:bg-white even:bg-slate-50">
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900">{{ \Carbon\Carbon::parse($cierre->fecha)->format('d/m/Y') }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ $cierre->almacen_nombre ?? __('Sin almacén') }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-slate-700">S/ {{ number_format($cierre->total_monto, 2) }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-slate-700">S/ {{ number_format($cierre->total_pagado, 2) }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-slate-700">S/ {{ number_format($cierre->total_pendiente, 2) }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-slate-700">S/ {{ number_format($cierre->total_vuelto, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-slate-500">
                        {{ __('No hay información disponible para los filtros seleccionados.') }}
                    </td>
                </tr>
            @endforelse

            @if ($cierres->hasPages())
                <x-slot:footer>
                    {{ $cierres->appends(request()->query())->links() }}
                </x-slot:footer>
            @endif
        </x-tailadmin.table-card>
    </section>
</x-app-layout>
