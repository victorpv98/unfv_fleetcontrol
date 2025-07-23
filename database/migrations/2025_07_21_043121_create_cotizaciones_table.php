<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_mantenimiento_id')->constrained('ordenes_mantenimiento')->onDelete('cascade');
            $table->foreignId('taller_id')->constrained('talleres')->onDelete('restrict');
            $table->string('numero_cotizacion', 20)->nullable();
            $table->text('descripcion_servicios');
            $table->decimal('costo_mano_obra', 10, 2)->nullable();
            $table->decimal('costo_repuestos', 10, 2)->nullable();
            $table->decimal('costo_total', 10, 2);
            $table->integer('tiempo_estimado')->nullable();
            $table->date('fecha_cotizacion');
            $table->date('fecha_validez');
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada', 'vencida'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index(['orden_mantenimiento_id', 'estado']);
            $table->index(['taller_id', 'fecha_cotizacion']);
            $table->index(['fecha_validez']);
            $table->index(['costo_total', 'estado']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cotizaciones');
    }
};
