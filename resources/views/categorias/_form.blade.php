@csrf
<div class="space-y-6">
    <div>
        <x-input-label for="nombre" value="{{ __('Nombre') }}" />
        <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" :value="old('nombre', $categoria->nombre ?? '')" required />
        <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
    </div>
</div>

<div class="flex justify-end space-x-2">
    <a href="{{ route('categorias.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-400 focus:ring ring-gray-200 transition ease-in-out duration-150">
        {{ __('Cancelar') }}
    </a>
    <x-primary-button>{{ $submitLabel }}</x-primary-button>
</div>
