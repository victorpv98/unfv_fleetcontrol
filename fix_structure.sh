#!/bin/bash

echo "🔧 Corrigiendo errores de estructura..."

# =====================================================
# CREAR DIRECTORIOS FALTANTES CORRECTAMENTE
# =====================================================
echo "📂 Creando directorios faltantes..."

# Crear directorios uno por uno para asegurar que se creen correctamente
mkdir -p resources/views/vehiculos/documentos
mkdir -p resources/views/movimientos/inspecciones
mkdir -p resources/views/mantenimiento/ordenes
mkdir -p resources/views/mantenimiento/cotizaciones
mkdir -p resources/views/mantenimiento/facturas
mkdir -p resources/views/reportes/pdf
mkdir -p resources/views/configuracion/sistema
mkdir -p resources/views/components/alerts
mkdir -p resources/views/components/forms
mkdir -p resources/views/components/tables

echo "✅ Directorios creados correctamente"

# =====================================================
# CREAR ARCHIVOS DE VISTAS FALTANTES
# =====================================================
echo "📄 Creando archivos de vistas faltantes..."

# Vehículos - documentos
touch resources/views/vehiculos/documentos/index.blade.php
touch resources/views/vehiculos/documentos/create.blade.php
touch resources/views/vehiculos/documentos/edit.blade.php

# Verificar y crear otros archivos que puedan haber fallado
touch resources/views/vehiculos/index.blade.php
touch resources/views/vehiculos/create.blade.php
touch resources/views/vehiculos/show.blade.php
touch resources/views/vehiculos/edit.blade.php
touch resources/views/vehiculos/historial.blade.php

# Movimientos - inspecciones
touch resources/views/movimientos/inspecciones/salida.blade.php
touch resources/views/movimientos/inspecciones/entrada.blade.php

# Mantenimiento
touch resources/views/mantenimiento/ordenes/index.blade.php
touch resources/views/mantenimiento/cotizaciones/index.blade.php
touch resources/views/mantenimiento/cotizaciones/create.blade.php
touch resources/views/mantenimiento/facturas/index.blade.php
touch resources/views/mantenimiento/facturas/create.blade.php

# Reportes PDF
touch resources/views/reportes/pdf/formulario-ma122.blade.php
touch resources/views/reportes/pdf/orden-mantenimiento.blade.php
touch resources/views/reportes/pdf/factura.blade.php
touch resources/views/reportes/pdf/reporte-vehiculo.blade.php
touch resources/views/reportes/pdf/reporte-flota.blade.php
touch resources/views/reportes/pdf/reporte-movimientos.blade.php

# Configuración
touch resources/views/configuracion/sistema/index.blade.php

echo "✅ Archivos de vistas faltantes creados"

# =====================================================
# VERIFICAR REQUESTS EXISTENTES
# =====================================================
echo "📋 Verificando requests existentes..."

echo "Requests encontrados:"
find app/Http/Requests -name "*.php" 2>/dev/null | sort

# Crear los que faltan (si no existen)
if [ ! -f "app/Http/Requests/VehiculoRequest.php" ]; then
    php artisan make:request VehiculoRequest
fi

if [ ! -f "app/Http/Requests/ConductorRequest.php" ]; then
    php artisan make:request ConductorRequest
fi

if [ ! -f "app/Http/Requests/MovimientoRequest.php" ]; then
    php artisan make:request MovimientoRequest
fi

echo "✅ Requests verificados"

# =====================================================
# CREAR ARCHIVOS DE IDIOMA CON CONTENIDO BÁSICO
# =====================================================
echo "🌐 Configurando archivos de idioma..."

# Crear contenido básico para auth.php
cat > resources/lang/es/auth.php << 'EOF'
<?php

return [
    'failed' => 'Estas credenciales no coinciden con nuestros registros.',
    'password' => 'La contraseña es incorrecta.',
    'throttle' => 'Demasiados intentos de acceso. Inténtelo de nuevo en :seconds segundos.',
];
EOF

# Crear contenido básico para validation.php
cat > resources/lang/es/validation.php << 'EOF'
<?php

return [
    'required' => 'El campo :attribute es obligatorio.',
    'email' => 'El campo :attribute debe ser una dirección de correo válida.',
    'unique' => 'El campo :attribute ya está en uso.',
    'max' => [
        'string' => 'El campo :attribute no puede tener más de :max caracteres.',
    ],
    'min' => [
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
];
EOF

# Crear contenido básico para vehiculos.php
cat > resources/lang/es/vehiculos.php << 'EOF'
<?php

return [
    'title' => 'Gestión de Vehículos',
    'create' => 'Registrar Vehículo',
    'edit' => 'Editar Vehículo',
    'placa' => 'Placa',
    'marca' => 'Marca',
    'modelo' => 'Modelo',
    'año' => 'Año',
    'combustible' => 'Tipo de Combustible',
    'estado' => 'Estado',
];
EOF

echo "✅ Archivos de idioma configurados"

# =====================================================
# VERIFICACIÓN FINAL DETALLADA
# =====================================================
echo "🔍 Verificación final detallada..."

echo ""
echo "📊 RESUMEN DE ESTRUCTURA CREADA:"
echo "================================"

echo "🎯 Controladores: $(find app/Http/Controllers -name "*.php" | wc -l)"
echo "📋 Requests: $(find app/Http/Requests -name "*.php" 2>/dev/null | wc -l)"
echo "📄 Vistas: $(find resources/views -name "*.blade.php" | wc -l)"
echo "🧩 Componentes: $(find app/View/Components -name "*.php" 2>/dev/null | wc -l)"
echo "🌱 Seeders: $(find database/seeders -name "*Seeder.php" | wc -l)"
echo "🏭 Factories: $(find database/factories -name "*Factory.php" | wc -l)"
echo "🔐 Policies: $(find app/Policies -name "*.php" 2>/dev/null | wc -l)"
echo "⚙️ Jobs: $(find app/Jobs -name "*.php" 2>/dev/null | wc -l)"
echo "📡 Events: $(find app/Events -name "*.php" 2>/dev/null | wc -l)"
echo "👂 Listeners: $(find app/Listeners -name "*.php" 2>/dev/null | wc -l)"
echo "🔧 Commands: $(find app/Console/Commands -name "*.php" | grep -v Kernel | wc -l)"

echo ""
echo "📂 DIRECTORIOS PRINCIPALES:"
echo "=========================="
ls -la resources/views/ | grep "^d"

echo ""
echo "✅ CORRECCIONES COMPLETADAS"
echo "🎉 ¡Tu estructura está lista para desarrollar!"

echo ""
echo "🚀 SIGUIENTES PASOS RECOMENDADOS:"
echo "================================"
echo "1. Configurar rutas: php artisan route:list"
echo "2. Crear migraciones: php artisan make:migration"
echo "3. Configurar base de datos en .env"
echo "4. Ejecutar migraciones: php artisan migrate"
echo "5. Poblar datos de prueba: php artisan db:seed"