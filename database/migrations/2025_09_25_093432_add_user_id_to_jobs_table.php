<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['unidades', 'colaboradores', 'bandeiras', 'grupos_economicos'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->unsignedBigInteger('usuario_cadastrante_id')->nullable()->after('id');
                $table->unsignedBigInteger('usuario_alterante_id')->nullable()->after('id');

            });
        }

        $defaultUserId = DB::table('users')->first()->id ?? null;

        if ($defaultUserId) {
            foreach ($tables as $table) {
            DB::table($table)->update([
                'usuario_cadastrante_id' => $defaultUserId,
                'usuario_alterante_id' => $defaultUserId
            ]);
            }
        }

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
            $table->foreign('usuario_cadastrante_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('usuario_alterante_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['unidades', 'colaboradores', 'bandeiras', 'grupos_economicos'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
            $table->dropForeign([$table . '_usuario_cadastrante_id_foreign']);
            $table->dropForeign([$table . '_usuario_alterante_id_foreign']);
            $table->dropColumn('usuario_cadastrante_id');
            $table->dropColumn('usuario_alterante_id');
            });
        }
    }
};
