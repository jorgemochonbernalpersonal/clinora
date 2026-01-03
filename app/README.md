# Clinora - Arquitectura y Estructura del Proyecto

Esta carpeta contiene la documentación técnica completa de la arquitectura de Clinora.

## 📁 Estructura Principal

```
app/
├── Core/                    # Módulo Core (funcionalidades comunes)
├── Modules/                 # Módulos específicos por profesión
│   └── Psychology/         # Módulo de Psicología
├── PatientPortal/          # Portal del paciente
├── Shared/                 # Componentes compartidos
│   ├── Interfaces/         # Interfaces base
│   ├── Traits/            # Traits reutilizables
│   ├── Enums/             # Enumeraciones
│   ├── ValueObjects/      # Value Objects (Address, Money)
│   ├── Helpers/           # Clases Helper
│   └── Exceptions/        # Excepciones personalizadas
├── Http/
│   └── Middleware/        # Middlewares personalizados
└── Providers/             # Service Providers
```

## 🎯 Componentes Implementados

### ✅ Shared Components

**Interfaces:**
- `RepositoryInterface` - Contrato base para repositorios
- `ServiceInterface` - Marker interface para servicios

**Traits:**
- `HasProfessional` - Para modelos que pertenecen a un profesional
- `HasContact` - Para modelos que pertenecen a un contacto/paciente
- `HasAuditLog` - Tracking automático de cambios
- `SoftDeletesWithArchive` - Soft deletes + funcionalidad de archivo

**Enums:**
- `UserRole` - Roles del sistema (admin, professional, patient, support)
- `AppointmentStatus` - Estados de citas
- `AppointmentType` - Tipos de consulta (presencial, online, domicilio, teléfono)
- `InvoiceStatus` - Estados de factura
- `AssessmentType` - Tipos de evaluación psicológica (BDI-II, PHQ-9, GAD-7)

**Value Objects:**
- `Address` - Representación inmutable de direcciones
- `Money` - Manejo seguro de valores monetarios

**Helpers:**
- `DateHelper` - Utilidades para manejo de fechas
- `FileHelper` - Utilidades para archivos

**Exceptions:**
- `AppointmentConflictException` - Conflictos de horario
- `PaymentFailedException` - Fallos en pagos

### ✅ Middleware

- `CheckModuleEnabled` - Verifica si un módulo está habilitado
- `CheckProfessionalSubscription` - Valida suscripción activa
- `EnsureTeleconsultationAccess` - Verifica acceso a teleconsulta

### ✅ Configuraciones

- `config/modules.php` - Configuración de módulos
- `config/teleconsultation.php` - Configuración de teleconsulta
- `config/billing.php` - Configuración de facturación
- `config/notifications.php` - Configuración de notificaciones

### ✅ Service Providers

- `CoreServiceProvider` - Proveedor del módulo Core
- `PsychologyModuleServiceProvider` - Proveedor del módulo Psychology
- `PatientPortalServiceProvider` - Proveedor del Patient Portal

### ✅ Rutas Modulares

- `routes/api/core.php` - Rutas API del Core
- `routes/api/psychology.php` - Rutas API de Psychology
- `routes/api/patient-portal.php` - Rutas API del Patient Portal

## 📋 Próximos Pasos

1. **Implementar Modelos Core:**
   - User (actualizar modelo existente)
   - Professional
   - Contact
   - Appointment
   - Invoice/Payment
   - Notification

2. **Crear Migraciones:**
   - Tablas Core
   - Tablas Psychology
   - Tablas Patient Portal

3. **Implementar Repositorios y Servicios:**
   - Repositories siguiendo RepositoryInterface
   - Services con lógica de negocio

4. **Desarrollar Controladores:**
   - API Controllers para cada módulo
   - Request validation classes
   - API Resources para formateo de respuestas

## 🔧 Uso de la Arquitectura

### Ejemplo: Crear un Repositorio

```php
use App\Shared\Interfaces\RepositoryInterface;
use App\Core\Contacts\Models\Contact;

class ContactRepository implements RepositoryInterface
{
    public function __construct(private Contact $model) {}
    
    // Implementar métodos de la interface...
}
```

### Ejemplo: Usar Traits

```php
use App\Shared\Traits\HasProfessional;
use App\Shared\Traits\HasAuditLog;

class Appointment extends Model
{
    use HasProfessional, HasAuditLog;
    
    // El modelo ahora tiene automatic audit logging
    // y relación con Professional
}
```

### Ejemplo: Value Objects

```php
$address = Address::fromArray([
    'street' => 'Calle Mayor 123',
    'city' => 'Madrid',
    'state' => 'Madrid',
    'postal_code' => '28013',
    'country' => 'España',
]);

echo $address->formatted();
```

---

**Versión:** 1.0  
**Actualizado:** 2026-01-02
