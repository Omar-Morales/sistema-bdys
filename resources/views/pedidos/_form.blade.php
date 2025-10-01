@csrf
@php
    $oldMontoTotal = old('monto_total', $pedido->monto_total ?? 0);
    $oldMontoPagado = old('monto_pagado', $pedido->monto_pagado ?? 0);
@endphp
<div
    x-data="{
        montoTotal: {{ is_numeric($oldMontoTotal) ? $oldMontoTotal : 0 }},
        montoPagado: {{ is_numeric($oldMontoPagado) ? $oldMontoPagado : 0 }},
        get saldo() {
            const total = parseFloat(this.montoTotal) || 0;
            const pagado = parseFloat(this.montoPagado) || 0;
            const diff = total - pagado;
            return diff > 0 ? diff : 0;
        },
        get vuelto() {
            const total = parseFloat(this.montoTotal) || 0;
            const pagado = parseFloat(this.montoPagado) || 0;
            const diff = pagado - total;
            return diff > 0 ? diff : 0;
        }
    }"
    class="space-y-6"
>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <x-input-label for="tienda_id" value="{{ __('Tienda') }}" />
            <select id="tienda_id" name="tienda_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">{{ __('Seleccione una tienda') }}</option>
                @foreach ($tiendas as $id => $nombre)
                    <option value="{{ $id }}" @selected(old('tienda_id', $pedido->tienda_id ?? '') == $id)>{{ $nombre }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('tienda_id')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="vendedor_id" value="{{ __('Vendedor') }}" />
            <select id="vendedor_id" name="vendedor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">{{ __('Seleccione un vendedor') }}</option>
                @foreach ($vendedores as $id => $nombre)
                    <option value="{{ $id }}" @selected(old('vendedor_id', $pedido->vendedor_id ?? '') == $id)>{{ $nombre }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('vendedor_id')" class="mt-2" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <x-input-label for="almacen_id" value="{{ __('Almacén origen') }}" />
            <select id="almacen_id" name="almacen_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">{{ __('Seleccione un almacén') }}</option>
                @foreach ($almacenes as $id => $nombre)
                    <option value="{{ $id }}" @selected(old('almacen_id', $pedido->almacen_id ?? '') == $id)>{{ $nombre }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('almacen_id')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="almacen_destino_id" value="{{ __('Almacén destino') }}" />
            <select id="almacen_destino_id" name="almacen_destino_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">{{ __('Seleccione un almacén destino') }}</option>
                @foreach ($almacenes as $id => $nombre)
                    <option value="{{ $id }}" @selected(old('almacen_destino_id', $pedido->almacen_destino_id ?? '') == $id)>{{ $nombre }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('almacen_destino_id')" class="mt-2" />
        </div>
    </div>

    <div>
        <x-input-label for="encargado_id" value="{{ __('Encargado') }}" />
        <select id="encargado_id" name="encargado_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">{{ __('Seleccione un encargado') }}</option>
            @foreach ($encargados as $id => $nombre)
                <option value="{{ $id }}" @selected(old('encargado_id', $pedido->encargado_id ?? '') == $id)>{{ $nombre }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('encargado_id')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <x-input-label for="monto_total" value="{{ __('Monto total (S/)') }}" />
            <x-text-input id="monto_total" name="monto_total" type="number" step="0.01" min="0" class="mt-1 block w-full" x-model.number="montoTotal" :value="$oldMontoTotal" required />
            <x-input-error :messages="$errors->get('monto_total')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="monto_pagado" value="{{ __('Monto pagado (S/)') }}" />
            <x-text-input id="monto_pagado" name="monto_pagado" type="number" step="0.01" min="0" class="mt-1 block w-full" x-model.number="montoPagado" :value="$oldMontoPagado" />
            <x-input-error :messages="$errors->get('monto_pagado')" class="mt-2" />
        </div>
        <div class="bg-gray-50 rounded-md border border-dashed border-gray-300 p-4">
            <p class="text-sm text-gray-600">{{ __('Saldo pendiente') }}:</p>
            <p class="text-lg font-semibold text-gray-900" x-text="`S/ ${saldo.toFixed(2)}`"></p>
            <p class="text-sm text-gray-600 mt-2">{{ __('Vuelto estimado') }}:</p>
            <p class="text-lg font-semibold text-gray-900" x-text="`S/ ${vuelto.toFixed(2)}`"></p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <x-input-label for="metraje_total" value="{{ __('Metraje total') }}" />
            <x-text-input id="metraje_total" name="metraje_total" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('metraje_total', $pedido->metraje_total ?? '')" />
            <x-input-error :messages="$errors->get('metraje_total')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="cantidad_total" value="{{ __('Cantidad total') }}" />
            <x-text-input id="cantidad_total" name="cantidad_total" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('cantidad_total', $pedido->cantidad_total ?? '')" />
            <x-input-error :messages="$errors->get('cantidad_total')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="unidad_referencia" value="{{ __('Unidad referencia') }}" />
            <x-text-input id="unidad_referencia" name="unidad_referencia" type="text" class="mt-1 block w-full" :value="old('unidad_referencia', $pedido->unidad_referencia ?? '')" />
            <x-input-error :messages="$errors->get('unidad_referencia')" class="mt-2" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <x-input-label for="precio_promedio" value="{{ __('Precio promedio (S/)') }}" />
            <x-text-input id="precio_promedio" name="precio_promedio" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('precio_promedio', $pedido->precio_promedio ?? '')" />
            <x-input-error :messages="$errors->get('precio_promedio')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="tipo_entrega" value="{{ __('Tipo de entrega') }}" />
            <select id="tipo_entrega" name="tipo_entrega" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @foreach ($tiposEntrega as $tipo)
                    <option value="{{ $tipo }}" @selected(old('tipo_entrega', $pedido->tipo_entrega ?? '') === $tipo)>{{ ucwords(str_replace('_', ' ', $tipo)) }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('tipo_entrega')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="tipo_pago" value="{{ __('Tipo de pago') }}" />
            <select id="tipo_pago" name="tipo_pago" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">{{ __('Seleccione un tipo de pago') }}</option>
                @foreach ($tiposPago as $tipo)
                    <option value="{{ $tipo }}" @selected(old('tipo_pago', $pedido->tipo_pago ?? '') === $tipo)>{{ strtoupper($tipo) }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('tipo_pago')" class="mt-2" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <x-input-label for="estado_pedido" value="{{ __('Estado del pedido') }}" />
            <select id="estado_pedido" name="estado_pedido" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @foreach ($estadosPedido as $estado)
                    <option value="{{ $estado }}" @selected(old('estado_pedido', $pedido->estado_pedido ?? '') === $estado)>{{ ucwords(str_replace('_', ' ', $estado)) }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('estado_pedido')" class="mt-2" />
        </div>
        <div class="flex items-center pt-6">
            <label class="inline-flex items-center">
                <input type="hidden" name="cobra_almacen" value="0">
                <input type="checkbox" name="cobra_almacen" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" @checked(old('cobra_almacen', $pedido->cobra_almacen ?? false))>
                <span class="ms-2 text-sm text-gray-600">{{ __('Cobro en almacén') }}</span>
            </label>
        </div>
    </div>

    <div>
        <x-input-label for="notas" value="{{ __('Notas') }}" />
        <textarea id="notas" name="notas" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notas', $pedido->notas ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('notas')" class="mt-2" />
    </div>
</div>

<div class="flex justify-end space-x-2 mt-8">
    <a href="{{ route('supervisor.pedidos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-400 focus:ring ring-gray-200 transition ease-in-out duration-150">
        {{ __('Cancelar') }}
    </a>
    <x-primary-button>{{ $submitLabel }}</x-primary-button>
</div>
