<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->decimal('metraje', 10, 2)->nullable()->after('cantidad');
            $table->decimal('precio_unitario', 10, 2)->nullable()->after('precio_final');
        });
    }

    public function down(): void
    {
        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->dropColumn(['metraje', 'precio_unitario']);
        });
    }
};
