<?php

namespace App\Http\Requests\Vendedor;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendedorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage vendedores') ?? false;
    }

    public function rules(): array
    {
        return [
            'tienda_id' => ['required', 'exists:tiendas,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:50'],
        ];
    }
}
