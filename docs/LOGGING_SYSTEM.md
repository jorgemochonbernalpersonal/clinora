# Sistema de Logging y Debugging

Este documento describe el sistema completo de logging y debugging implementado en Clinora.

## Componentes del Sistema

### 1. Trait Loggable

El trait `App\Shared\Traits\Loggable` proporciona métodos consistentes para logging en controladores y servicios.

**Uso:**
```php
use App\Shared\Traits\Loggable;

class MyController extends Controller
{
    use Loggable; // Ya está incluido en Controller base
    
    public function myMethod()
    {
        // Log de error
        $this->logError('Mensaje de error', $exception, ['context' => 'data']);
        
        // Log de warning
        $this->logWarning('Advertencia', ['data' => 'value']);
        
        // Log de info
        $this->logInfo('Información', ['data' => 'value']);
        
        // Log de debug (solo en modo debug)
        $this->logDebug('Debug info', ['data' => 'value']);
        
        // Log crítico
        $this->logCritical('Error crítico', $exception);
        
        // Log de acción de usuario
        $this->logUserAction('Acción realizada', ['item_id' => 123]);
    }
}
```

### 2. Helpers Globales

Funciones helper disponibles globalmente (sin necesidad de usar el trait):

```php
// En cualquier parte del código
logError('Mensaje', $exception, ['context' => 'data']);
logWarning('Advertencia', ['data' => 'value']);
logInfo('Información', ['data' => 'value']);
logDebug('Debug', ['data' => 'value']);
logCritical('Crítico', $exception);
logUserAction('Acción', ['data' => 'value']);
logSlowQuery($query, $time, $bindings);
```

### 3. Canales de Logging

El sistema está configurado con múltiples canales de logging:

- **daily**: Log principal (por defecto) - `storage/logs/laravel-YYYY-MM-DD.log`
- **errors**: Solo errores - `storage/logs/errors-YYYY-MM-DD.log`
- **api**: Logs de API - `storage/logs/api-YYYY-MM-DD.log`
- **user_actions**: Acciones de usuarios - `storage/logs/user-actions-YYYY-MM-DD.log`
- **performance**: Logs de rendimiento - `storage/logs/performance-YYYY-MM-DD.log`

**Uso de canales específicos:**
```php
Log::channel('errors')->error('Error crítico');
Log::channel('api')->info('Request API');
Log::channel('user_actions')->info('Usuario creó contacto');
```

### 4. Handler de Excepciones Global

Todas las excepciones no manejadas se registran automáticamente con:
- Mensaje de error
- Archivo y línea
- Stack trace completo
- ID de usuario (si está autenticado)
- URL y método de la request

### 5. Middleware de Logging de Requests

El middleware `App\Http\Middleware\LogRequests` puede registrar todas las requests HTTP con:
- Método y URL
- IP y User Agent
- Datos de entrada (sanitizados)
- Tiempo de respuesta
- Código de estado

**Para habilitarlo**, descomenta en `bootstrap/app.php`:
```php
$middleware->web(append: [
    \App\Http\Middleware\LogRequests::class,
]);
```

## Herramientas de Debugging

### Laravel Telescope

Telescope está configurado y disponible en `/telescope` (solo en desarrollo o con permisos).

Monitorea:
- Requests HTTP
- Queries de base de datos
- Jobs en cola
- Excepciones
- Logs
- Cache
- Events
- Mail
- Notifications

### Laravel Pail

Herramienta de logs en tiempo real. Se ejecuta automáticamente con:
```bash
composer dev
```

O manualmente:
```bash
php artisan pail
```

### LogViewer

Interfaz web para ver logs en `/dashboard/logs`:
- Filtrado por fecha
- Filtrado por nivel (error, warning, info, etc.)
- Búsqueda de texto
- Estadísticas de logs
- Descarga de archivos de log

### Sentry

Configurado para monitoreo de errores en producción. Requiere configuración de `SENTRY_DSN` en `.env`.

## Mejores Prácticas

### 1. Contexto Rico
Siempre incluye contexto útil en los logs:
```php
$this->logError('Error al procesar pago', $e, [
    'user_id' => $user->id,
    'amount' => $amount,
    'payment_method' => $method,
]);
```

### 2. Niveles Apropiados
- **error**: Errores que requieren atención pero no detienen la aplicación
- **critical**: Errores críticos que pueden afectar la funcionalidad
- **warning**: Situaciones inusuales que no son errores
- **info**: Información general sobre el funcionamiento
- **debug**: Información detallada solo para desarrollo

### 3. No Loggear Información Sensible
El sistema automáticamente sanitiza campos como:
- `password`
- `password_confirmation`
- `token`
- `api_token`
- `credit_card`
- `cvv`

### 4. Logging de Acciones Importantes
Siempre loguea acciones importantes del usuario:
```php
$this->logUserAction('Paciente creado', [
    'contact_id' => $contact->id,
    'name' => $contact->full_name,
]);
```

### 5. Logging de Errores con Excepciones
Siempre incluye la excepción cuando esté disponible:
```php
try {
    // código
} catch (\Exception $e) {
    $this->logError('Error en operación', $e, ['context' => 'data']);
    // manejo del error
}
```

## Configuración

### Variables de Entorno

En `.env`:
```env
LOG_CHANNEL=daily
LOG_LEVEL=debug
LOG_DAILY_DAYS=30
TELESCOPE_ENABLED=true
SENTRY_DSN=tu_dsn_aqui
```

### Retención de Logs

Los logs diarios se mantienen por 30 días por defecto (configurable con `LOG_DAILY_DAYS`).

## Ejemplos de Uso

### En un Controlador
```php
public function store(Request $request)
{
    try {
        $this->logInfo('Creando nuevo recurso');
        
        $resource = Resource::create($request->validated());
        
        $this->logUserAction('Recurso creado', [
            'resource_id' => $resource->id,
        ]);
        
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        $this->logError('Error al crear recurso', $e, [
            'input' => $request->except(['password']),
        ]);
        
        return response()->json(['success' => false], 500);
    }
}
```

### En un Servicio
```php
class PaymentService
{
    use Loggable;
    
    public function processPayment($amount)
    {
        $this->logInfo('Procesando pago', ['amount' => $amount]);
        
        try {
            // lógica de pago
            $this->logInfo('Pago procesado exitosamente');
        } catch (PaymentException $e) {
            $this->logError('Error en procesamiento de pago', $e, [
                'amount' => $amount,
            ]);
            throw $e;
        }
    }
}
```

### Usando Helpers Globales
```php
// En cualquier parte del código
if ($queryTime > 1.0) {
    logSlowQuery($query, $queryTime, $bindings);
}

logUserAction('Exportación de datos', [
    'format' => 'csv',
    'records' => $count,
]);
```

## Monitoreo

### Ver Logs en Tiempo Real
```bash
php artisan pail
```

### Ver Logs en la Web
Navega a `/dashboard/logs` en tu aplicación.

### Ver Logs con Telescope
Navega a `/telescope` y selecciona la pestaña "Logs".

### Ver Logs en Producción
Los logs están en `storage/logs/`. Puedes acceder vía SSH o usar herramientas como:
- Papertrail
- Loggly
- Datadog
- Sentry (ya configurado)

## Troubleshooting

### Los logs no aparecen
1. Verifica permisos en `storage/logs/`
2. Verifica `LOG_CHANNEL` en `.env`
3. Verifica que `LOG_LEVEL` no esté muy restrictivo

### Logs muy grandes
1. Reduce `LOG_DAILY_DAYS` en `.env`
2. Considera usar el canal `errors` solo para errores
3. Implementa rotación de logs más agresiva

### Performance
El logging puede afectar el rendimiento. Para producción:
- Usa `LOG_LEVEL=error` o `warning`
- Desactiva logging de requests en producción
- Considera usar un servicio externo como Sentry

