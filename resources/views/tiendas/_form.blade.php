@csrf
<div class="space-y-6">
    <div>
        <x-input-label for="nombre" value="{{ __('Nombre') }}" />
        <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" :value="old('nombre', $tienda->nombre ?? '')" required />
        <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="sector" value="{{ __('Sector') }}" />
        <x-text-input id="sector" name="sector" type="text" class="mt-1 block w-full" :value="old('sector', $tienda->sector ?? '')" required />
        <x-input-error :messages="$errors->get('sector')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="direccion" value="{{ __('Dirección') }}" />
        <x-text-input id="direccion" name="direccion" type="text" class="mt-1 block w-full" :value="old('direccion', $tienda->direccion ?? '')" required />
        <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="telefono" value="{{ __('Teléfono') }}" />
        <x-text-input id="telefono" name="telefono" type="text" class="mt-1 block w-full" :value="old('telefono', $tienda->telefono ?? '')" required />
        <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
    </div>
</div>

<div class="flex justify-end space-x-2">
    <a href="{{ route('tiendas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-400 focus:ring ring-gray-200 transition ease-in-out duration-150">
        {{ __('Cancelar') }}
    </a>
    <x-primary-button>{{ $submitLabel }}</x-primary-button>
</div>
