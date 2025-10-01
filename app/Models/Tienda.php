<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tienda extends Model
{
    use HasFactory;

    protected $table = 'tiendas';
    protected $fillable = ['nombre', 'sector', 'direccion', 'telefono'];

    public function vendedores()
    {
        return $this->hasMany(Vendedor::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}
