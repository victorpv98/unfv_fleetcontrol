<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('facturas_mantenimiento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_mantenimiento_id')->constrained('ordenes_mantenimiento')->onDelete('restrict');
            $table->foreignId('taller_id')->constrained('talleres')->onDelete('restrict');
            $table->string('numero_factura', 20);
            $table->string('ruc_emisor', 11);
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('igv', 10, 2);
            $table->decimal('total', 10, 2);
            $table->enum('estado', ['pendiente', 'pagada', 'anulada'])->default('pendiente');
            $table->string('archivo_url', 255)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index(['orden_mantenimiento_id', 'estado']);
            $table->index(['taller_id', 'fecha_emision']);
            $table->index(['numero_factura']);
            $table->index(['fecha_vencimiento', 'estado']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('facturas_mantenimiento');
    }
};
