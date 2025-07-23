<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detalles_mantenimiento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_mantenimiento_id')->constrained('ordenes_mantenimiento')->onDelete('cascade');
            $table->foreignId('repuesto_id')->nullable()->constrained('repuestos')->onDelete('restrict');
            $table->decimal('cantidad_utilizada', 8, 2);
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->enum('tipo_costo', ['repuesto', 'mano_obra', 'servicio_externo']);
            $table->string('descripcion', 200)->nullable();
            $table->timestamps();
            
            // Índices
            $table->index(['orden_mantenimiento_id', 'tipo_costo']);
            $table->index(['repuesto_id', 'created_at']);
            $table->index(['tipo_costo']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('detalles_mantenimiento');
    }
};