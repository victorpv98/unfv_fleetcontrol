<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alertas', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_alerta', [
                'vencimiento_documento', 
                'mantenimiento_preventivo', 
                'inspeccion_tecnica', 
                'soat', 
                'licencia_conductor', 
                'kilometraje'
            ]);
            $table->unsignedBigInteger('entidad_id');
            $table->enum('entidad_tipo', ['vehiculo', 'conductor', 'documento']);
            $table->string('titulo', 200);
            $table->text('mensaje');
            $table->date('fecha_programada');
            $table->timestamp('fecha_notificacion')->nullable();
            $table->enum('prioridad', ['baja', 'media', 'alta', 'critica'])->default('media');
            $table->enum('estado', ['pendiente', 'notificada', 'leida', 'resuelta'])->default('pendiente');
            $table->foreignId('usuario_asignado')->nullable()->constrained('users');
            $table->timestamps();
            
            // Índices
            $table->index(['fecha_programada', 'estado']);
            $table->index(['entidad_id', 'entidad_tipo']);
            $table->index(['usuario_asignado', 'estado']);
            $table->index(['prioridad', 'fecha_programada']);
            $table->index(['tipo_alerta']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('alertas');
    }
};