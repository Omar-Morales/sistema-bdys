<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;

    public const UNIDAD_PIEZA = 'pieza';
    public const UNIDAD_CAJA = 'caja';
    public const UNIDAD_KG = 'kg';
    public const UNIDAD_METRO = 'mtr';
    public const UNIDAD_JUEGO = 'juego';
    public const UNIDAD_KIT = 'kit';

    public const UNIDADES = [
        self::UNIDAD_PIEZA,
        self::UNIDAD_CAJA,
        self::UNIDAD_KG,
        self::UNIDAD_METRO,
        self::UNIDAD_JUEGO,
        self::UNIDAD_KIT,
    ];

    protected $table = 'detalle_pedido';

    protected $fillable = [
        'pedido_id',
        'producto_id',
        'cantidad',
        'unidad',
        'metraje',
        'precio_unitario',
        'precio_final',
        'subtotal',
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'metraje' => 'decimal:2',
        'precio_unitario' => 'decimal:2',
        'precio_final' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function scopeConUnidad($query, string $unidad)
    {
        return $query->where('unidad', $unidad);
    }

    public function scopeConMetraje($query)
    {
        return $query->whereNotNull('metraje');
    }

    public function getImporteTotalAttribute(): float
    {
        $cantidad = (float) $this->cantidad;
        $precio = (float) ($this->precio_unitario ?? $this->precio_final);

        return round($cantidad * $precio, 2);
    }
}
