<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('repuestos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->string('marca', 50)->nullable();
            $table->string('categoria', 50)->nullable();
            $table->enum('unidad_medida', ['unidad', 'litro', 'galon', 'metro', 'kilogramo']);
            $table->integer('stock_minimo')->default(0);
            $table->integer('stock_actual')->default(0);
            $table->decimal('precio_unitario', 10, 2)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            // Índices
            $table->index(['codigo']);
            $table->index(['nombre']);
            $table->index(['stock_actual', 'activo']);
            $table->index(['categoria']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('repuestos');
    }
};

