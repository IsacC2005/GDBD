<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('pregunta1')->nullable();
            $table->string('respuesta1')->nullable();
            $table->string('pregunta2')->nullable();
            $table->string('respuesta2')->nullable();
            $table->string('pregunta3')->nullable();
            $table->string('respuesta3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'pregunta1',
                'respuesta1',
                'pregunta2',
                'respuesta2',
                'pregunta3',
                'respuesta3',
            ]);
        });
    }
};
