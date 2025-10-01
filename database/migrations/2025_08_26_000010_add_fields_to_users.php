<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'almacen_id')) {
                $table->unsignedBigInteger('almacen_id')->nullable();
                $table->foreign('almacen_id')->references('id')->on('almacenes')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'almacen_id')) {
                $table->dropForeign(['almacen_id']);
                $table->dropColumn('almacen_id');
            }
        });
    }
};
