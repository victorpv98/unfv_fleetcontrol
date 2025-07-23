<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('configuraciones_sistema', function (Blueprint $table) {
            $table->id();
            $table->string('clave', 100)->unique();
            $table->text('valor');
            $table->string('descripcion', 200)->nullable();
            $table->enum('tipo_dato', ['string', 'integer', 'decimal', 'boolean', 'date'])->default('string');
            $table->string('categoria', 50)->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            
            // Índices
            $table->index(['clave']);
            $table->index(['categoria']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('configuraciones_sistema');
    }
};
