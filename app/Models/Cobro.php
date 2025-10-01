<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cobro extends Model
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

    protected $table = 'cobros';

    protected $fillable = [
        'pedido_id',
        'monto',
        'monto_pagado',
        'tipo_pago',
        'estado_pago',
        'metodo',
        'registrado_por',
        'observaciones',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'monto_pagado' => 'decimal:2',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado_pago', self::ESTADO_PAGO_PENDIENTE);
    }

    public function scopePagados($query)
    {
        return $query->where('estado_pago', self::ESTADO_PAGO_PAGADO);
    }

    public function scopeConTipoPago($query, string $tipo)
    {
        return $query->where('tipo_pago', $tipo);
    }

    public function getEsParcialAttribute(): bool
    {
        $monto = (float) $this->monto;
        $pagado = (float) $this->monto_pagado;

        return $pagado > 0 && $pagado < $monto;
    }

    public function getSaldoPendienteAttribute(): float
    {
        $monto = (float) $this->monto;
        $pagado = (float) $this->monto_pagado;

        return round($monto - $pagado, 2);
    }
}
