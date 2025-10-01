@csrf
<div class="space-y-6">
    <div>
        <x-input-label for="tienda_id" value="{{ __('Tienda') }}" />
        <select id="tienda_id" name="tienda_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">{{ __('Seleccione una tienda') }}</option>
            @foreach ($tiendas as $id => $nombre)
                <option value="{{ $id }}" @selected(old('tienda_id', $vendedor->tienda_id ?? '') == $id)>{{ $nombre }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('tienda_id')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="nombre" value="{{ __('Nombre') }}" />
        <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" :value="old('nombre', $vendedor->nombre ?? '')" required />
        <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="telefono" value="{{ __('Teléfono') }}" />
        <x-text-input id="telefono" name="telefono" type="text" class="mt-1 block w-full" :value="old('telefono', $vendedor->telefono ?? '')" required />
        <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
    </div>
</div>

<div class="flex justify-end space-x-2">
    <a href="{{ route('vendedores.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-400 focus:ring ring-gray-200 transition ease-in-out duration-150">
        {{ __('Cancelar') }}
    </a>
    <x-primary-button>{{ $submitLabel }}</x-primary-button>
</div>
