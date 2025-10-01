<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'rol_id')) {
            return;
        }

        DB::transaction(function (): void {
            $users = DB::table('users')
                ->select('id', 'rol_id')
                ->whereNotNull('rol_id')
                ->get();

            foreach ($users as $user) {
                DB::table(config('permission.table_names.model_has_roles'))
                    ->updateOrInsert([
                        'role_id' => $user->rol_id,
                        'model_type' => User::class,
                        'model_id' => $user->id,
                    ], []);
            }
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropForeign(['rol_id']);
            $table->dropColumn('rol_id');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'rol_id')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->unsignedBigInteger('rol_id')->nullable();
            $table->foreign('rol_id')->references('id')->on('roles')->onDelete('cascade');
        });

        $records = DB::table(config('permission.table_names.model_has_roles'))
            ->where('model_type', User::class)
            ->get(['model_id', 'role_id']);

        foreach ($records as $record) {
            DB::table('users')
                ->where('id', $record->model_id)
                ->update(['rol_id' => $record->role_id]);
        }
    }
};
