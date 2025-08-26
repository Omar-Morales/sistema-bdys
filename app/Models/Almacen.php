<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    protected $table = 'almacenes';
    protected $fillable = ['nombre', 'direccion'];

    public function usuarios()
    {
        return $this->hasMany(User::class, 'almacen_id');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}
