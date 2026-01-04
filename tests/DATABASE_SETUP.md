# Configuración de Base de Datos para Tests

## ⚠️ IMPORTANTE: Base de Datos de Tests

**NUNCA** uses la base de datos de desarrollo/producción para los tests. Los tests deben usar una base de datos separada.

## Configuración

### 1. Base de Datos de Tests

La base de datos de tests está configurada en `phpunit.xml`:

```xml
<env name="DB_CONNECTION" value="mysql"/>
<env name="DB_DATABASE" value="clinora_test"/>
<env name="DB_HOST" value="127.0.0.1"/>
<env name="DB_PORT" value="3306"/>
<env name="DB_USERNAME" value="root"/>
<env name="DB_PASSWORD" value=""/>
```

### 2. Crear la Base de Datos de Tests

Antes de ejecutar los tests, asegúrate de crear la base de datos:

```sql
CREATE DATABASE IF NOT EXISTS clinora_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

O usando MySQL CLI:

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS clinora_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 3. Ejecutar Tests

Los tests automáticamente:
- ✅ Usan la base de datos `clinora_test` (configurada en `phpunit.xml`)
- ✅ Ejecutan las migraciones antes de cada test (gracias a `RefreshDatabase`)
- ✅ Limpian la base de datos después de cada test
- ✅ Verifican que estás usando la base de datos correcta

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar solo tests unitarios
php artisan test --testsuite=Unit

# Ejecutar solo tests de feature
php artisan test --testsuite=Feature
```

## Verificación Automática

El `TestCase` base verifica automáticamente que estés usando la base de datos de tests:

```php
protected function assertDatabaseConnectionIsTest(): void
{
    $database = config('database.connections.mysql.database');
    
    if (app()->environment('testing')) {
        if ($database !== 'clinora_test' && $database !== ':memory:') {
            throw new \RuntimeException(
                "Los tests deben usar la base de datos 'clinora_test' o ':memory:', pero se está usando '{$database}'."
            );
        }
    }
}
```

## Uso de RefreshDatabase

Todos los tests deben usar el trait `RefreshDatabase`:

```php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MiTest extends TestCase
{
    use RefreshDatabase; // ✅ Esto asegura que se use la BD de tests
    
    public function test_algo(): void
    {
        // Tu test aquí
    }
}
```

## ¿Qué hace RefreshDatabase?

1. **Antes de cada test:**
   - Ejecuta todas las migraciones
   - Prepara una base de datos limpia

2. **Después de cada test:**
   - Limpia todas las tablas (rollback de transacciones)
   - Mantiene la base de datos lista para el siguiente test

## Troubleshooting

### Error: "No se puede establecer una conexión"

**Problema:** MySQL no está corriendo o la base de datos no existe.

**Solución:**
1. Asegúrate de que MySQL esté corriendo
2. Crea la base de datos: `CREATE DATABASE clinora_test;`
3. Verifica las credenciales en `phpunit.xml`

### Error: "Los tests deben usar la base de datos 'clinora_test'"

**Problema:** Estás usando la base de datos de desarrollo.

**Solución:**
1. Verifica que `phpunit.xml` tenga `DB_DATABASE=clinora_test`
2. No uses variables de entorno del `.env` que sobrescriban la configuración
3. Asegúrate de que `APP_ENV=testing` esté configurado

### Tests lentos

**Problema:** Los tests pueden ser lentos si la base de datos es grande.

**Solución:**
- `RefreshDatabase` ya optimiza esto usando transacciones
- Considera usar SQLite en memoria para tests más rápidos (opcional)

## Alternativa: SQLite en Memoria (Opcional)

Para tests más rápidos, puedes usar SQLite en memoria:

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

**Nota:** Esto puede tener limitaciones con algunas características específicas de MySQL.

## Resumen

✅ **SIEMPRE** usa `RefreshDatabase` en tus tests  
✅ **NUNCA** uses la base de datos de desarrollo  
✅ **VERIFICA** que `phpunit.xml` tenga `DB_DATABASE=clinora_test`  
✅ **CREA** la base de datos `clinora_test` antes de ejecutar tests

