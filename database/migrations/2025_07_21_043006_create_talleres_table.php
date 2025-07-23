<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('talleres', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->enum('tipo', ['propio', 'externo']);
            $table->string('ruc', 11)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->string('telefono', 15)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('contacto_nombre', 100)->nullable();
            $table->text('especialidades')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            // Índices
            $table->index(['tipo', 'activo']);
            $table->index(['ruc']);
            $table->index(['nombre']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('talleres');
    }
};
