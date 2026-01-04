<?php

/**
 * Script para verificar y corregir permisos de storage
 * Ejecutar: php fix-storage-permissions.php
 */

echo "🔧 Verificando permisos de storage...\n\n";

$directories = [
    'storage/app',
    'storage/app/public',
    'storage/app/public/patients-photos',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache',
];

$permissions = 0755;

foreach ($directories as $dir) {
    $path = __DIR__ . '/' . $dir;
    
    if (!is_dir($path)) {
        if (mkdir($path, $permissions, true)) {
            echo "✓ Directorio creado: $dir\n";
        } else {
            echo "❌ Error al crear: $dir\n";
            continue;
        }
    }
    
    // Establecer permisos
    if (chmod($path, $permissions)) {
        echo "✓ Permisos establecidos (755) en: $dir\n";
    } else {
        echo "⚠️  No se pudieron establecer permisos en: $dir\n";
    }
}

// Verificar enlace simbólico
$link = __DIR__ . '/public/storage';
$target = __DIR__ . '/storage/app/public';

echo "\n🔗 Verificando enlace simbólico...\n";

if (is_link($link)) {
    $actualTarget = readlink($link);
    if ($actualTarget === $target || realpath($link) === realpath($target)) {
        echo "✓ El enlace simbólico está correcto.\n";
    } else {
        echo "⚠️  El enlace apunta a: $actualTarget\n";
        echo "   Debería apuntar a: $target\n";
    }
} else {
    echo "❌ No existe el enlace simbólico en public/storage\n";
    echo "   Ejecuta: php create-storage-link.php\n";
}

// Verificar que el directorio target existe
if (is_dir($target)) {
    echo "✓ El directorio target existe: storage/app/public\n";
} else {
    echo "❌ El directorio target NO existe: storage/app/public\n";
    echo "   Créalo manualmente.\n";
}

echo "\n✅ Verificación completada.\n";

