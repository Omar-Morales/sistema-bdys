<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tienda extends Model
{
    protected $table = 'tiendas';

    public function vendedores()
    {
        return $this->hasMany(Vendedor::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}
