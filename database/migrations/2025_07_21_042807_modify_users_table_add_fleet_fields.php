<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('apellidos', 100)->after('name');
            $table->enum('rol', ['administrador', 'operador', 'encargado_garaje', 'jefe_mantenimiento'])->after('email');
            $table->boolean('activo')->default(true)->after('rol');
            
            // Eliminar campos no necesarios
            if (Schema::hasColumn('users', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['apellidos', 'rol', 'activo']);
            $table->timestamp('email_verified_at')->nullable();
        });
    }
};
