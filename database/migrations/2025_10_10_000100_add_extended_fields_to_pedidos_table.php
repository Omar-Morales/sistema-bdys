<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreignId('almacen_destino_id')
                ->nullable()
                ->after('almacen_id')
                ->constrained('almacenes')
                ->nullOnDelete();
            $table->enum('tipo_entrega', ['recojo_almacen', 'envio_tienda', 'delivery_cliente'])
                ->default('recojo_almacen')
                ->after('almacen_destino_id');
            $table->decimal('metraje_total', 10, 2)
                ->nullable()
                ->after('saldo_pendiente');
            $table->decimal('cantidad_total', 10, 2)
                ->nullable()
                ->after('metraje_total');
            $table->string('unidad_referencia', 25)
                ->nullable()
                ->after('cantidad_total');
            $table->decimal('precio_promedio', 10, 2)
                ->nullable()
                ->after('unidad_referencia');
            $table->decimal('monto_pagado', 10, 2)
                ->default(0)
                ->after('monto_total');
            $table->enum('tipo_pago', ['efectivo', 'yape', 'plin', 'bcp', 'interbank', 'bbva'])
                ->nullable()
                ->after('monto_pagado');
            $table->enum('estado_pago', ['pendiente', 'por_cobrar', 'vuelto', 'pagado'])
                ->default('pendiente')
                ->after('tipo_pago');
            $table->enum('estado_pedido', ['pendiente', 'en_curso', 'entregado', 'anulado'])
                ->default('pendiente')
                ->after('estado_pago');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_entrega',
                'metraje_total',
                'cantidad_total',
                'unidad_referencia',
                'precio_promedio',
                'monto_pagado',
                'tipo_pago',
                'estado_pago',
                'estado_pedido',
            ]);

            $table->dropForeign(['almacen_destino_id']);
            $table->dropColumn('almacen_destino_id');
        });
    }
};
