@csrf
<div class="space-y-6">
    <div>
        <x-input-label for="categoria_id" value="{{ __('Categoría') }}" />
        <select id="categoria_id" name="categoria_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">{{ __('Seleccione una categoría') }}</option>
            @foreach ($categorias as $id => $nombre)
                <option value="{{ $id }}" @selected(old('categoria_id', $producto->categoria_id ?? '') == $id)>{{ $nombre }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('categoria_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="nombre" value="{{ __('Nombre') }}" />
        <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" :value="old('nombre', $producto->nombre ?? '')" required />
        <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <x-input-label for="medida" value="{{ __('Medida') }}" />
            <x-text-input id="medida" name="medida" type="text" class="mt-1 block w-full" :value="old('medida', $producto->medida ?? '')" />
            <x-input-error :messages="$errors->get('medida')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="unidad" value="{{ __('Unidad') }}" />
            <select id="unidad" name="unidad" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">{{ __('Seleccione una unidad') }}</option>
                @foreach ($unidades as $unidad)
                    <option value="{{ $unidad }}" @selected(old('unidad', $producto->unidad ?? '') === $unidad)>{{ ucfirst($unidad) }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('unidad')" class="mt-2" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <x-input-label for="piezas_por_caja" value="{{ __('Piezas por caja') }}" />
            <x-text-input id="piezas_por_caja" name="piezas_por_caja" type="number" min="1" class="mt-1 block w-full" :value="old('piezas_por_caja', $producto->piezas_por_caja ?? 1)" required />
            <x-input-error :messages="$errors->get('piezas_por_caja')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="precio_referencial" value="{{ __('Precio referencial') }}" />
            <x-text-input id="precio_referencial" name="precio_referencial" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('precio_referencial', $producto->precio_referencial ?? '')" required />
            <x-input-error :messages="$errors->get('precio_referencial')" class="mt-2" />
        </div>
    </div>
</div>

<div class="flex justify-end space-x-2">
    <a href="{{ route('productos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-400 focus:ring ring-gray-200 transition ease-in-out duration-150">
        {{ __('Cancelar') }}
    </a>
    <x-primary-button>{{ $submitLabel }}</x-primary-button>
</div>
