<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    public const ESTADO_PAGO_PENDIENTE = 'pendiente';
    public const ESTADO_PAGO_POR_COBRAR = 'por_cobrar';
    public const ESTADO_PAGO_VUELTO = 'vuelto';
    public const ESTADO_PAGO_PAGADO = 'pagado';

    public const ESTADOS_PAGO = [
        self::ESTADO_PAGO_PENDIENTE,
        self::ESTADO_PAGO_POR_COBRAR,
        self::ESTADO_PAGO_VUELTO,
        self::ESTADO_PAGO_PAGADO,
    ];

    public const ESTADO_PEDIDO_PENDIENTE = 'pendiente';
    public const ESTADO_PEDIDO_EN_CURSO = 'en_curso';
    public const ESTADO_PEDIDO_ENTREGADO = 'entregado';
    public const ESTADO_PEDIDO_ANULADO = 'anulado';

    public const ESTADOS_PEDIDO = [
        self::ESTADO_PEDIDO_PENDIENTE,
        self::ESTADO_PEDIDO_EN_CURSO,
        self::ESTADO_PEDIDO_ENTREGADO,
        self::ESTADO_PEDIDO_ANULADO,
    ];

    public const TIPO_ENTREGA_RECOJO = 'recojo_almacen';
    public const TIPO_ENTREGA_ENVIO_TIENDA = 'envio_tienda';
    public const TIPO_ENTREGA_DELIVERY_CLIENTE = 'delivery_cliente';

    public const TIPOS_ENTREGA = [
        self::TIPO_ENTREGA_RECOJO,
        self::TIPO_ENTREGA_ENVIO_TIENDA,
        self::TIPO_ENTREGA_DELIVERY_CLIENTE,
    ];

    public const TIPO_PAGO_EFECTIVO = 'efectivo';
    public const TIPO_PAGO_YAPE = 'yape';
    public const TIPO_PAGO_PLIN = 'plin';
    public const TIPO_PAGO_BCP = 'bcp';
    public const TIPO_PAGO_INTERBANK = 'interbank';
    public const TIPO_PAGO_BBVA = 'bbva';

    public const TIPOS_PAGO = [
        self::TIPO_PAGO_EFECTIVO,
        self::TIPO_PAGO_YAPE,
        self::TIPO_PAGO_PLIN,
        self::TIPO_PAGO_BCP,
        self::TIPO_PAGO_INTERBANK,
        self::TIPO_PAGO_BBVA,
    ];

    protected $table = 'pedidos';

    protected $fillable = [
        'tienda_id',
        'vendedor_id',
        'almacen_id',
        'encargado_id',
        'monto_total',
        'monto_pagado',
        'saldo_pendiente',
        'metraje_total',
        'cantidad_total',
        'unidad_referencia',
        'precio_promedio',
        'tipo_entrega',
        'tipo_pago',
        'estado_pago',
        'estado_pedido',
        'cobra_almacen',
        'notas',
    ];

    protected $casts = [
        'monto_total' => 'decimal:2',
        'monto_pagado' => 'decimal:2',
        'saldo_pendiente' => 'decimal:2',
        'metraje_total' => 'decimal:2',
        'cantidad_total' => 'decimal:2',
        'precio_promedio' => 'decimal:2',
        'cobra_almacen' => 'boolean',
    ];

    public function tienda()
    {
        return $this->belongsTo(Tienda::class);
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class);
    }

    public function encargado()
    {
        return $this->belongsTo(User::class, 'encargado_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function cobros()
    {
        return $this->hasMany(Cobro::class);
    }

    public function scopeConPagosPendientes($query)
    {
        return $query->whereColumn('monto_pagado', '<', 'monto_total');
    }

    public function scopeConPagosCompletos($query)
    {
        return $query->whereColumn('monto_pagado', '>=', 'monto_total');
    }

    public function scopeConEstadoPago($query, string $estado)
    {
        return $query->where('estado_pago', $estado);
    }

    public function getDiferenciaPagoAttribute(): float
    {
        $total = (float) $this->monto_total;
        $pagado = (float) $this->monto_pagado;

        return round($total - $pagado, 2);
    }

    public function getEstaPagadoAttribute(): bool
    {
        return $this->diferencia_pago <= 0 && $this->estado_pago === self::ESTADO_PAGO_PAGADO;
    }
}
