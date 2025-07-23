<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('auditoria', function (Blueprint $table) {
            $table->id();
            $table->string('tabla', 50);
            $table->unsignedBigInteger('registro_id');
            $table->enum('accion', ['INSERT', 'UPDATE', 'DELETE']);
            $table->json('datos_anteriores')->nullable();
            $table->json('datos_nuevos')->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('users');
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamps();
            
            // Índices
            $table->index(['tabla', 'registro_id']);
            $table->index(['usuario_id']);
            $table->index(['created_at']);
            $table->index(['accion', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('auditoria');
    }
};
