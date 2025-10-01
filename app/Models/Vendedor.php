<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    use HasFactory;

    protected $table = 'vendedores';

    public function tienda()
    {
        return $this->belongsTo(Tienda::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}
