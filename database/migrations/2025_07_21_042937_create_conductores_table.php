<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('conductores', function (Blueprint $table) {
            $table->id();
            $table->string('dni', 8)->unique();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('telefono', 15)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('licencia_numero', 20)->unique();
            $table->string('licencia_categoria', 10);
            $table->date('licencia_vencimiento');
            $table->text('certificaciones')->nullable();
            $table->enum('estado', ['activo', 'suspendido', 'inactivo'])->default('activo');
            $table->timestamps();
            
            // Índices
            $table->index(['estado']);
            $table->index(['licencia_vencimiento', 'estado']);
            $table->index(['licencia_categoria']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('conductores');
    }
};
