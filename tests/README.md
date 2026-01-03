# Tests de Clinora

Este directorio contiene los tests unitarios y de integración para la aplicación Clinora.

## Configuración

### 1. Crear la base de datos de test

Antes de ejecutar los tests, necesitas crear la base de datos `clinora_test`:

```sql
CREATE DATABASE clinora_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

O usando MySQL desde la línea de comandos:

```bash
mysql -u root -e "CREATE DATABASE clinora_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 2. Configurar variables de entorno

El archivo `phpunit.xml` ya está configurado para usar la base de datos `clinora_test` con las siguientes credenciales por defecto:

- **Host:** 127.0.0.1
- **Puerto:** 3306
- **Base de datos:** clinora_test
- **Usuario:** root
- **Contraseña:** (vacía)

Si necesitas cambiar estas credenciales, edita el archivo `phpunit.xml` o crea un archivo `.env.testing` (aunque este archivo está en .gitignore).

### 3. Ejecutar migraciones en la base de datos de test

Los tests ejecutan automáticamente las migraciones antes de cada suite de tests usando `RefreshDatabase`. No necesitas ejecutar migraciones manualmente.

## Estructura de Tests

### Factories

Las factories están en `database/factories/`:

- **UserFactory**: Crea usuarios (profesionales, admins, etc.)
- **ProfessionalFactory**: Crea perfiles profesionales
- **ContactFactory**: Crea pacientes/contactos
- **AppointmentFactory**: Crea citas
- **ClinicalNoteFactory**: Crea notas clínicas

### Seeders

- **TestSeeder**: Crea datos de prueba estándar:
  - 1 Profesional
  - 5 Pacientes (Contacts)
  - 5 Citas (Appointments)
  - 5 Notas Clínicas (ClinicalNotes)

### Tests de Ejemplo

- **ContactsTest**: Tests para el modelo Contact
- **AppointmentsTest**: Tests para el modelo Appointment
- **ClinicalNotesTest**: Tests para el modelo ClinicalNote

## Ejecutar Tests

### Ejecutar todos los tests

```bash
php artisan test
```

O usando PHPUnit directamente:

```bash
./vendor/bin/phpunit
```

### Ejecutar tests específicos

```bash
# Tests de Feature
php artisan test --testsuite=Feature

# Tests de Unit
php artisan test --testsuite=Unit

# Un test específico
php artisan test tests/Feature/ContactsTest.php

# Un método específico
php artisan test --filter test_can_create_contact
```

### Ejecutar con cobertura

```bash
php artisan test --coverage
```

## Datos de Prueba

El `TestSeeder` crea automáticamente:

- **1 Usuario Profesional** con email `test.professional@clinora.test`
- **5 Pacientes** con datos aleatorios pero realistas
- **5 Citas** (3 completadas, 2 programadas)
- **5 Notas Clínicas** asociadas a las citas

Para usar estos datos en tus tests, simplemente llama:

```php
public function test_example(): void
{
    $this->seedTestData(); // Crea todos los datos de prueba
    
    $professional = Professional::first();
    $contacts = Contact::all();
    // ... tus tests
}
```

## Notas Importantes

1. **Base de datos separada**: Los tests usan `clinora_test`, nunca tocan la base de datos principal `clinora`.

2. **RefreshDatabase**: Cada test ejecuta `RefreshDatabase`, lo que significa que:
   - Las migraciones se ejecutan automáticamente
   - La base de datos se limpia después de cada test
   - Los datos de prueba se recrean en cada test si usas `seedTestData()`

3. **Transacciones**: Los tests se ejecutan dentro de transacciones que se revierten al finalizar, por lo que la base de datos de test siempre queda limpia.

4. **No afecta producción**: Los tests están completamente aislados y no afectan tu base de datos de desarrollo o producción.

## Crear Nuevos Tests

Para crear un nuevo test:

```bash
php artisan make:test MiNuevoTest
```

O manualmente, crea un archivo en `tests/Feature/` o `tests/Unit/`:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class MiNuevoTest extends TestCase
{
    public function test_ejemplo(): void
    {
        $this->seedTestData();
        
        // Tu código de test aquí
        $this->assertTrue(true);
    }
}
```

## Troubleshooting

### Error: "Base de datos no existe"

Asegúrate de haber creado la base de datos `clinora_test`:

```bash
mysql -u root -e "CREATE DATABASE clinora_test;"
```

### Error: "Access denied"

Verifica las credenciales en `phpunit.xml` o crea un archivo `.env.testing` con tus credenciales.

### Error: "Table doesn't exist"

Los tests ejecutan automáticamente las migraciones. Si ves este error, verifica que las migraciones estén correctas.

