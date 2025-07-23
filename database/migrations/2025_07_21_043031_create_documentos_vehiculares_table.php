<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documentos_vehiculares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->onDelete('restrict');
            $table->enum('tipo_documento', ['soat', 'revision_tecnica', 'tarjeta_propiedad', 'permiso_circulacion']);
            $table->string('numero_documento', 50);
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento');
            $table->string('entidad_emisora', 100)->nullable();
            $table->string('archivo_url', 255)->nullable();
            $table->enum('estado', ['vigente', 'por_vencer', 'vencido'])->default('vigente');
            $table->timestamps();
            
            // Índices
            $table->index(['vehiculo_id', 'fecha_vencimiento']);
            $table->index(['tipo_documento']);
            $table->index(['estado', 'fecha_vencimiento']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('documentos_vehiculares');
    }
};
