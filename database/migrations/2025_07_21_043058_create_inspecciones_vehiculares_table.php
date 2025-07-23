<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inspecciones_vehiculares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movimiento_id')->constrained('movimientos_vehiculares')->onDelete('cascade');
            $table->enum('tipo_inspeccion', ['salida', 'entrada']);
            $table->enum('nivel_combustible', ['lleno', 'tres_cuartos', 'medio', 'un_cuarto', 'vacio'])->nullable();
            $table->enum('estado_neumaticos', ['bueno', 'regular', 'malo'])->nullable();
            $table->boolean('luces_funcionando')->nullable();
            $table->boolean('frenos_funcionando')->nullable();
            $table->enum('nivel_aceite', ['bueno', 'regular', 'bajo'])->nullable();
            $table->enum('limpieza_vehiculo', ['excelente', 'bueno', 'regular', 'malo'])->nullable();
            $table->boolean('documentos_vehiculo')->nullable();
            $table->boolean('kit_emergencia')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('inspector_id')->nullable()->constrained('users');
            $table->timestamps();
            
            // Índices
            $table->index(['movimiento_id', 'tipo_inspeccion']);
            $table->index(['inspector_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inspecciones_vehiculares');
    }
};
