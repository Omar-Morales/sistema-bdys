<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cobros', function (Blueprint $table) {
            $table->decimal('monto_pagado', 10, 2)->default(0)->after('monto');
            $table->enum('tipo_pago', ['efectivo', 'yape', 'plin', 'bcp', 'interbank', 'bbva'])
                ->nullable()
                ->after('monto_pagado');
            $table->enum('estado_pago', ['pendiente', 'por_cobrar', 'vuelto', 'pagado'])
                ->default('pendiente')
                ->after('tipo_pago');
        });
    }

    public function down(): void
    {
        Schema::table('cobros', function (Blueprint $table) {
            $table->dropColumn(['monto_pagado', 'tipo_pago', 'estado_pago']);
        });
    }
};
