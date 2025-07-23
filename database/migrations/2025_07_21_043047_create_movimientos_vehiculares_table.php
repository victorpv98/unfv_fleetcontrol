<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('movimientos_vehiculares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->onDelete('restrict');
            $table->foreignId('conductor_id')->constrained('conductores')->onDelete('restrict');
            $table->foreignId('destino_id')->constrained('destinos')->onDelete('restrict');
            $table->string('formulario_ma122', 20)->unique()->nullable();
            $table->timestamp('fecha_hora_salida')->nullable();
            $table->timestamp('fecha_hora_entrada')->nullable();
            $table->integer('kilometraje_salida')->nullable();
            $table->integer('kilometraje_entrada')->nullable();
            $table->integer('kilometros_recorridos')->nullable();
            $table->decimal('combustible_inicial', 8, 2)->nullable();
            $table->decimal('combustible_abastecido', 8, 2)->nullable();
            $table->decimal('combustible_final', 8, 2)->nullable();
            $table->text('proposito_viaje')->nullable();
            $table->text('observaciones_salida')->nullable();
            $table->text('observaciones_entrada')->nullable();
            $table->enum('estado', ['en_curso', 'finalizado', 'cancelado'])->default('en_curso');
            $table->foreignId('autorizado_por')->nullable()->constrained('users');
            $table->timestamps();
            
            // Índices
            $table->index(['vehiculo_id', 'fecha_hora_salida']);
            $table->index(['conductor_id']);
            $table->index(['estado']);
            $table->index(['fecha_hora_salida']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('movimientos_vehiculares');
    }
};
