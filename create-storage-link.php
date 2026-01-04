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

// Verificar permisos del directorio public
$publicDir = dirname($link);
if (!is_writable($publicDir)) {
    echo "❌ Error: No tienes permisos de escritura en: $publicDir\n";
    echo "   Permisos actuales: " . substr(sprintf('%o', fileperms($publicDir)), -4) . "\n";
    echo "   Necesitas permisos 755 o 775 en el directorio public/\n";
    exit(1);
}
echo "✓ Permisos de escritura verificados en: $publicDir\n";

// Crear el enlace simbólico
echo "📝 Intentando crear el enlace simbólico...\n";
$result = @symlink($target, $link);

if ($result) {
    echo "✅ ¡Enlace simbólico creado exitosamente!\n\n";
    echo "   Enlace: $link\n";
    echo "   Apunta a: $target\n\n";
    
    // Verificar que funciona
    if (is_link($link)) {
        $actualTarget = readlink($link);
        echo "✓ Verificación: El enlace existe y apunta a: $actualTarget\n";
        
        // Verificar que el target existe
        if (file_exists($link)) {
            echo "✓ Verificación: El enlace funciona correctamente (el target es accesible).\n";
            echo "\n🎉 ¡Todo listo! Las fotos de pacientes deberían mostrarse ahora.\n";
        } else {
            echo "⚠️  El enlace existe pero el target no es accesible desde el enlace.\n";
            echo "   Esto puede ser normal si el servidor resuelve rutas relativas.\n";
        }
    } else {
        echo "⚠️  El enlace se creó pero la verificación falló.\n";
    }
    exit(0);
} else {
    $error = error_get_last();
    echo "❌ Error: No se pudo crear el enlace simbólico.\n";
    echo "   Mensaje de error: " . ($error['message'] ?? 'Desconocido') . "\n";
    echo "   Verifica los permisos del directorio public/\n";
    echo "   Necesitas permisos de escritura en: $publicDir\n";
    echo "\n💡 Alternativa: Crea el enlace manualmente con:\n";
    echo "   ln -s " . realpath($target) . " " . $link . "\n";
    exit(1);
}

