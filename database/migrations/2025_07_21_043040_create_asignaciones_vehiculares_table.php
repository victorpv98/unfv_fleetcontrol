<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asignaciones_vehiculares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->onDelete('restrict');
            $table->foreignId('conductor_id')->constrained('conductores')->onDelete('restrict');
            $table->date('fecha_asignacion');
            $table->date('fecha_desasignacion')->nullable();
            $table->enum('estado', ['activa', 'finalizada'])->default('activa');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index(['vehiculo_id', 'estado']);
            $table->index(['conductor_id', 'estado']);
            $table->index(['fecha_asignacion', 'fecha_desasignacion']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('asignaciones_vehiculares');
    }
};
