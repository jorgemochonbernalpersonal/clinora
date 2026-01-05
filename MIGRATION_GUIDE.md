# Guía de Migración a Arquitectura por Profesión

## Resumen

Hemos migrado la arquitectura de Clinora a un sistema modular basado en profesiones. Cada profesión ahora tiene su propio módulo independiente con sus funcionalidades específicas.

## Cambios Principales

### 1. Sistema de Módulos

Se ha creado un sistema de módulos que permite:
- Registrar módulos por profesión
- Cargar rutas, vistas y migraciones dinámicamente
- Aislar funcionalidades específicas de cada profesión

**Archivos nuevos:**
- `app/Shared/Interfaces/ModuleInterface.php` - Interface base para módulos
- `app/Shared/Services/ModuleRegistry.php` - Registro central de módulos
- `app/Shared/Services/ModuleServiceProvider.php` - Service provider para módulos
- `app/Shared/Helpers/ModuleHelper.php` - Helpers para trabajar con módulos

### 2. Módulo Psychology

El módulo de psicología ha sido completamente reorganizado:

**Estructura:**
```
app/Modules/Psychology/
├── ClinicalNotes/
│   ├── Controllers/
│   ├── Models/
│   ├── Repositories/
│   ├── Services/
│   └── Requests/
├── ConsentForms/
│   └── Templates/
├── PsychologyModule.php
└── PsychologyModuleServiceProvider.php
```

**Cambios:**
- `ClinicalNote` movido de `Core` a `Modules/Psychology`
- Servicios y repositorios creados siguiendo el patrón Repository
- Controladores actualizados para usar servicios

### 3. Service Providers

**Cambios en `bootstrap/providers.php`:**
- `PsychologyModuleServiceProvider` movido a `App\Modules\Psychology\`
- Agregado `ModuleServiceProvider` para registrar módulos

### 4. Helpers Actualizados

**`app/Helpers/RouteHelper.php`:**
- Actualizado para usar `ModuleHelper` cuando sea posible
- Mantiene compatibilidad hacia atrás

## Cómo Agregar una Nueva Profesión

### Paso 1: Crear la estructura del módulo

```bash
app/Modules/Nutrition/
├── NutritionModule.php
├── NutritionModuleServiceProvider.php
└── [Funcionalidades específicas]/
```

### Paso 2: Implementar ModuleInterface

```php
// app/Modules/Nutrition/NutritionModule.php
class NutritionModule implements ModuleInterface
{
    public function getProfessionType(): string
    {
        return 'nutritionist';
    }
    
    // ... implementar otros métodos
}
```

### Paso 3: Registrar el módulo

En `app/Shared/Services/ModuleServiceProvider.php`:

```php
$registry->register(new NutritionModule());
```

### Paso 4: Crear Service Provider

```php
// app/Modules/Nutrition/NutritionModuleServiceProvider.php
class NutritionModuleServiceProvider extends ServiceProvider
{
    // Registrar servicios, rutas, migraciones, etc.
}
```

### Paso 5: Agregar a providers

En `bootstrap/providers.php`:

```php
App\Modules\Nutrition\NutritionModuleServiceProvider::class,
```

## Beneficios

1. **Escalabilidad**: Agregar nuevas profesiones es más simple
2. **Mantenibilidad**: Código organizado por profesión
3. **Testabilidad**: Módulos independientes son más fáciles de testear
4. **Performance**: Solo se cargan los módulos necesarios
5. **Colaboración**: Equipos pueden trabajar en módulos diferentes sin conflictos

## Próximos Pasos

- [ ] Migrar ConsentForms completamente a módulos
- [ ] Crear módulo de Nutrition
- [ ] Crear módulo de Physiotherapy
- [ ] Reorganizar componentes Livewire por profesión
- [ ] Actualizar documentación completa

## Compatibilidad

El código existente sigue funcionando. Los cambios son principalmente organizacionales y no rompen la funcionalidad actual.

