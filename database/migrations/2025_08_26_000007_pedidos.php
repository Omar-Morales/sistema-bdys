<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tienda_id')->constrained('tiendas')->cascadeOnDelete();
            $table->foreignId('vendedor_id')->constrained('vendedores')->cascadeOnDelete();
            $table->foreignId('almacen_id')->constrained('almacenes')->cascadeOnDelete();
            $table->foreignId('encargado_id')->constrained('users')->cascadeOnDelete(); // empleado BDYS
            //$table->string('cliente_final')->nullable();
            $table->decimal('monto_total', 10, 2);
            $table->decimal('saldo_pendiente', 10, 2)->default(0);
            $table->enum('estado', ['pendiente','enviado','entregado'])->default('pendiente');
            $table->boolean('cobra_almacen')->default(true);
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
