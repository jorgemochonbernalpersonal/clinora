<?php

/**
 * Script para crear el enlace simbólico de storage
 * Úsalo cuando exec() esté deshabilitado en el servidor
 * 
 * Ejecutar: php create-storage-link.php
 */

$target = __DIR__ . '/storage/app/public';
$link = __DIR__ . '/public/storage';

echo "🔗 Creando enlace simbólico de storage...\n\n";

// Verificar que el directorio target existe
if (!is_dir($target)) {
    echo "❌ Error: El directorio $target no existe.\n";
    echo "   Asegúrate de que storage/app/public existe.\n";
    exit(1);
}
echo "✓ Directorio target encontrado: $target\n";

// Si el enlace ya existe, eliminarlo primero
if (file_exists($link) || is_link($link)) {
    if (is_link($link)) {
        unlink($link);
        echo "✓ Enlace existente eliminado.\n";
    } else {
        echo "⚠️  Advertencia: $link existe pero no es un enlace simbólico.\n";
        echo "   Elimínalo manualmente antes de continuar.\n";
        exit(1);
    }
}

// Crear el enlace simbólico
if (symlink($target, $link)) {
    echo "✅ ¡Enlace simbólico creado exitosamente!\n\n";
    echo "   Enlace: $link\n";
    echo "   Apunta a: $target\n\n";
    
    // Verificar que funciona
    if (is_link($link) && readlink($link) === $target) {
        echo "✓ Verificación: El enlace funciona correctamente.\n";
        echo "\n🎉 ¡Todo listo! Las fotos de pacientes deberían mostrarse ahora.\n";
    } else {
        echo "⚠️  El enlace se creó pero la verificación falló.\n";
    }
    exit(0);
} else {
    echo "❌ Error: No se pudo crear el enlace simbólico.\n";
    echo "   Verifica los permisos del directorio public/\n";
    echo "   Necesitas permisos de escritura en: " . dirname($link) . "\n";
    exit(1);
}

