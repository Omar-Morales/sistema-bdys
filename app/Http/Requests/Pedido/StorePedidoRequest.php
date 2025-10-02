<?php

namespace App\Http\Requests\Pedido;

use App\Models\Pedido;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePedidoRequest extends FormRequest
{
    protected float $cambioCalculado = 0.0;

    public function authorize(): bool
    {
        return $this->user()?->can('manage pedidos') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'monto_pagado' => $this->input('monto_pagado', 0),
            'cobra_almacen' => $this->boolean('cobra_almacen'),
        ]);
    }

    public function rules(): array
    {
        return [
            'tienda_id' => ['required', 'exists:tiendas,id'],
            'vendedor_id' => ['required', 'exists:vendedores,id'],
            'almacen_id' => ['required', 'exists:almacenes,id'],
            'encargado_id' => ['required', 'exists:users,id'],
            'monto_total' => ['required', 'numeric', 'min:0'],
            'monto_pagado' => ['nullable', 'numeric', 'min:0'],
            'metraje_total' => ['nullable', 'numeric', 'min:0'],
            'cantidad_total' => ['nullable', 'numeric', 'min:0'],
            'unidad_referencia' => ['nullable', 'string', 'max:25'],
            'precio_promedio' => ['nullable', 'numeric', 'min:0'],
            'tipo_entrega' => ['required', 'string', Rule::in(Pedido::TIPOS_ENTREGA)],
            'tipo_pago' => ['nullable', 'string', Rule::in(Pedido::TIPOS_PAGO)],
            'estado_pedido' => ['required', 'string', Rule::in(Pedido::ESTADOS_PEDIDO)],
            'notas' => ['nullable', 'string'],
            'cobra_almacen' => ['required', 'boolean'],
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);

        $montoTotal = (float) $data['monto_total'];
        $montoPagado = (float) ($data['monto_pagado'] ?? 0);
        $saldo = max($montoTotal - $montoPagado, 0);
        $vuelto = max($montoPagado - $montoTotal, 0);

        $data['monto_pagado'] = $montoPagado;
        $data['saldo_pendiente'] = $saldo;
        $data['estado_pago'] = $this->resolverEstadoPago($saldo, $montoPagado, $vuelto);

        $this->cambioCalculado = $vuelto;

        $data['cobra_almacen'] = (bool) ($data['cobra_almacen'] ?? false);

        return $data;
    }

    public function cambioCalculado(): float
    {
        return $this->cambioCalculado;
    }

    protected function resolverEstadoPago(float $saldo, float $montoPagado, float $vuelto): string
    {
        if ($vuelto > 0) {
            return Pedido::ESTADO_PAGO_VUELTO;
        }

        if ($saldo <= 0) {
            return Pedido::ESTADO_PAGO_PAGADO;
        }

        return $montoPagado > 0 ? Pedido::ESTADO_PAGO_POR_COBRAR : Pedido::ESTADO_PAGO_PENDIENTE;
    }
}
