<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ordenes_mantenimiento', function (Blueprint $table) {
            $table->id();
            $table->string('numero_orden', 20)->unique();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->onDelete('restrict');
            $table->foreignId('taller_id')->constrained('talleres')->onDelete('restrict');
            $table->enum('tipo_mantenimiento', ['preventivo', 'correctivo', 'emergencia']);
            $table->enum('tipo_servicio', ['propio', 'externo']);
            $table->text('descripcion_trabajo');
            $table->date('fecha_solicitud');
            $table->date('fecha_programada')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_finalizacion')->nullable();
            $table->integer('kilometraje_servicio')->nullable();
            $table->enum('estado', ['solicitada', 'cotizando', 'aprobada', 'en_proceso', 'finalizada', 'cancelada'])->default('solicitada');
            $table->enum('prioridad', ['baja', 'media', 'alta', 'critica'])->default('media');
            $table->decimal('costo_estimado', 10, 2)->nullable();
            $table->decimal('costo_real', 10, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('solicitado_por')->nullable()->constrained('users');
            $table->foreignId('aprobado_por')->nullable()->constrained('users');
            $table->timestamps();
            
            // Índices
            $table->index(['vehiculo_id', 'fecha_solicitud']);
            $table->index(['estado', 'prioridad']);
            $table->index(['taller_id', 'estado']);
            $table->index(['fecha_solicitud']);
            $table->index(['fecha_programada', 'estado']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ordenes_mantenimiento');
    }
};
