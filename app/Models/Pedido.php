<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';

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
}
