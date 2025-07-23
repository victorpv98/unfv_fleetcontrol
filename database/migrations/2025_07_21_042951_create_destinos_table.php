<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('destinos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('direccion', 200);
            $table->string('distrito', 50)->nullable();
            $table->string('provincia', 50)->nullable();
            $table->string('departamento', 50)->nullable();
            $table->string('coordenadas', 50)->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            // Índices
            $table->index(['nombre']);
            $table->index(['departamento', 'provincia', 'distrito']);
            $table->index(['activo']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('destinos');
    }
};
