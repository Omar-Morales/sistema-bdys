<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('pedidos', 'almacen_destino_id')) {
            Schema::table('pedidos', function (Blueprint $table) {
                $table->dropForeign(['almacen_destino_id']);
                $table->dropColumn('almacen_destino_id');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('pedidos', 'almacen_destino_id')) {
            Schema::table('pedidos', function (Blueprint $table) {
                $table->foreignId('almacen_destino_id')
                    ->nullable()
                    ->after('almacen_id')
                    ->constrained('almacenes')
                    ->nullOnDelete();
            });
        }
    }
};
