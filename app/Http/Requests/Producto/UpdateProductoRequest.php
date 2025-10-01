<?php

namespace App\Http\Requests\Producto;

use App\Models\Producto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage productos') ?? false;
    }

    public function rules(): array
    {
        return [
            'categoria_id' => ['required', 'exists:categorias,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'medida' => ['nullable', 'string', 'max:255'],
            'unidad' => ['required', 'string', Rule::in(Producto::UNIDADES)],
            'piezas_por_caja' => ['required', 'integer', 'min:1'],
            'precio_referencial' => ['required', 'numeric', 'min:0'],
        ];
    }
}
