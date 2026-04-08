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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movimiento_id')->constrained('inventarios');
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->string('numero_factura')->unique();
            $table->dateTime('fecha_emicion');
            $table->enum('metodo_pago', ['Efectivo', 'Tarjeta', 'Transferencia']);
            $table->enum('estado', ['Pagada', 'Pendiente', 'Anulada']);
            $table->float('subtotal');
            $table->float('impuestos');
            $table->float('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
