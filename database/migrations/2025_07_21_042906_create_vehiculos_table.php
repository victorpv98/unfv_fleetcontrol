<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('placa', 10)->unique();
            $table->string('marca', 50);
            $table->string('modelo', 50);
            $table->integer('año');
            $table->enum('tipo_combustible', ['gasolina', 'diesel', 'gnv', 'electrico']);
            $table->enum('tipo_vehiculo', ['automovil', 'camioneta', 'camion', 'motocicleta', 'otro']);
            $table->string('color', 30)->nullable();
            $table->string('numero_motor', 50)->nullable();
            $table->string('numero_chasis', 50)->nullable();
            $table->decimal('capacidad_tanque', 8, 2)->nullable();
            $table->integer('kilometraje_actual')->default(0);
            $table->string('codigo_qr')->unique()->nullable();
            $table->enum('estado', ['activo', 'mantenimiento', 'inactivo'])->default('activo');
            $table->date('fecha_adquisicion')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index(['estado']);
            $table->index(['tipo_vehiculo']);
            $table->index(['kilometraje_actual', 'estado']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehiculos');
    }
};
