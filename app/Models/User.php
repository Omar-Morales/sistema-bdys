<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'almacen_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relación con Almacen (solo si es almacenista)
    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'almacen_id');
    }

    // Pedidos registrados por este encargado
    public function pedidosRegistrados()
    {
        return $this->hasMany(Pedido::class, 'encargado_id');
    }

    // Cobros registrados por este almacenista
    public function cobrosRegistrados()
    {
        return $this->hasMany(Cobro::class, 'registrado_por');
    }
}
