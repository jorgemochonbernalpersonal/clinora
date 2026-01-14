# Clinora - Arquitectura Laravel 12

## 📋 Índice

1. [Visión General](#visión-general)
2. [Stack Tecnológico](#stack-tecnológico)
3. [Arquitectura del Proyecto](#arquitectura-del-proyecto)
4. [Estructura de Directorios](#estructura-de-directorios)
5. [Patrones de Diseño](#patrones-de-diseño)
6. [Base de Datos MySQL](#base-de-datos-mysql)
7. [API REST](#api-rest)
8. [WebSockets y Teleconsulta](#websockets-y-teleconsulta)
9. [Sistema de Módulos](#sistema-de-módulos)
10. [Autenticación y Autorización](#autenticación-y-autorización)
11. [Jobs y Queues](#jobs-y-queues)
12. [Eventos y Listeners](#eventos-y-listeners)
13. [Testing](#testing)
14. [Despliegue](#despliegue)

---

## 🎯 Visión General

### Propósito
Clinora es una plataforma SaaS multi-profesional construida con **Laravel 12** y **MySQL**, diseñada con arquitectura modular para facilitar la expansión a múltiples profesiones de salud y bienestar.

### Principios Arquitectónicos
- **Modularidad**: Separación clara entre Core y Módulos específicos
- **Escalabilidad**: Preparado para crecer horizontalmente
- **Mantenibilidad**: Código limpio y bien organizado
- **Testabilidad**: Arquitectura que facilita testing
- **Performance**: Optimizado para MySQL con índices y queries eficientes

---

## 🛠️ Stack Tecnológico

### Backend
- **Framework**: Laravel 12
- **PHP**: 8.2+
- **Base de Datos**: MySQL 8.0+
- **Cache**: Redis 7.0+
- **Queue**: Redis + Laravel Horizon
- **WebSockets**: Laravel Echo + Soketi/Pusher
- **Autenticación**: Laravel Sanctum
- **Permisos**: Spatie Laravel Permission

### Frontend (Referencia)
- React 18+ con TypeScript
- Laravel Echo para WebSockets
- Axios para peticiones HTTP

### Infraestructura
- **Servidor Web**: Nginx
- **PHP-FPM**: PHP 8.2+
- **Contenedores**: Docker (opcional)
- **CI/CD**: GitHub Actions
- **Monitoring**: Laravel Telescope, Sentry

---

## 🏗️ Arquitectura del Proyecto

### Arquitectura en Capas

```
┌─────────────────────────────────────────┐
│     Presentation Layer                  │
│  Controllers, API Resources, Views     │
└─────────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────────┐
│     Application Layer                   │
│  Services, DTOs, Use Cases, Actions    │
└─────────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────────┐
│     Domain Layer                        │
│  Models, Value Objects, Events, Enums   │
└─────────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────────┐
│     Infrastructure Layer                │
│  Repositories, External Services, DB   │
└─────────────────────────────────────────┘
```

### Separación de Responsabilidades

1. **Core**: Funcionalidades comunes a todas las profesiones
2. **Modules**: Funcionalidades específicas por profesión
3. **PatientPortal**: Portal independiente para pacientes
4. **Shared**: Componentes reutilizables

---

## 📁 Estructura de Directorios

```
clinora/
├── app/
│   ├── Core/                              # Núcleo común
│   │   ├── Authentication/
│   │   │   ├── Controllers/
│   │   │   │   ├── AuthController.php
│   │   │   │   └── PasswordResetController.php
│   │   │   ├── Services/
│   │   │   │   ├── AuthService.php
│   │   │   │   └── TwoFactorService.php
│   │   │   ├── Repositories/
│   │   │   │   └── UserRepository.php
│   │   │   ├── Models/
│   │   │   │   ├── User.php
│   │   │   │   └── Session.php
│   │   │   ├── Requests/
│   │   │   │   ├── LoginRequest.php
│   │   │   │   └── RegisterRequest.php
│   │   │   └── Resources/
│   │   │       └── UserResource.php
│   │   │
│   │   ├── Users/
│   │   │   ├── Controllers/
│   │   │   │   └── ProfessionalController.php
│   │   │   ├── Services/
│   │   │   │   └── ProfessionalService.php
│   │   │   ├── Repositories/
│   │   │   │   └── ProfessionalRepository.php
│   │   │   ├── Models/
│   │   │   │   ├── Professional.php
│   │   │   │   └── ProfessionalSetting.php
│   │   │   └── DTOs/
│   │   │       └── CreateProfessionalDTO.php
│   │   │
│   │   ├── Contacts/
│   │   │   ├── Controllers/
│   │   │   │   └── ContactController.php
│   │   │   ├── Services/
│   │   │   │   └── ContactService.php
│   │   │   ├── Repositories/
│   │   │   │   ├── ContactRepositoryInterface.php
│   │   │   │   └── ContactRepository.php
│   │   │   ├── Models/
│   │   │   │   ├── Contact.php
│   │   │   │   └── EmergencyContact.php
│   │   │   ├── Requests/
│   │   │   │   ├── StoreContactRequest.php
│   │   │   │   └── UpdateContactRequest.php
│   │   │   └── Resources/
│   │   │       └── ContactResource.php
│   │   │
│   │   ├── Appointments/
│   │   │   ├── Controllers/
│   │   │   │   └── AppointmentController.php
│   │   │   ├── Services/
│   │   │   │   ├── AppointmentService.php
│   │   │   │   └── CalendarService.php
│   │   │   ├── Repositories/
│   │   │   │   ├── AppointmentRepositoryInterface.php
│   │   │   │   └── AppointmentRepository.php
│   │   │   ├── Models/
│   │   │   │   └── Appointment.php
│   │   │   ├── Events/
│   │   │   │   ├── AppointmentCreated.php
│   │   │   │   ├── AppointmentCancelled.php
│   │   │   │   └── AppointmentCompleted.php
│   │   │   ├── Listeners/
│   │   │   │   ├── SendAppointmentReminder.php
│   │   │   │   └── SyncGoogleCalendar.php
│   │   │   ├── Jobs/
│   │   │   │   └── SendAppointmentReminderJob.php
│   │   │   └── DTOs/
│   │   │       └── CreateAppointmentDTO.php
│   │   │
│   │   ├── Billing/
│   │   │   ├── Controllers/
│   │   │   │   └── InvoiceController.php
│   │   │   ├── Services/
│   │   │   │   ├── InvoiceService.php
│   │   │   │   └── PaymentService.php
│   │   │   ├── Repositories/
│   │   │   │   └── InvoiceRepository.php
│   │   │   ├── Models/
│   │   │   │   ├── Invoice.php
│   │   │   │   ├── InvoiceItem.php
│   │   │   │   └── Payment.php
│   │   │   ├── Strategies/
│   │   │   │   ├── InvoiceStrategyInterface.php
│   │   │   │   ├── SpainInvoiceStrategy.php
│   │   │   │   └── PDFGeneratorStrategy.php
│   │   │   ├── Factories/
│   │   │   │   └── InvoiceFactory.php
│   │   │   └── Jobs/
│   │   │       └── GenerateInvoicePDFJob.php
│   │   │
│   │   ├── Notifications/
│   │   │   ├── Services/
│   │   │   │   ├── NotificationService.php
│   │   │   │   └── EmailNotificationService.php
│   │   │   ├── Models/
│   │   │   │   └── Notification.php
│   │   │   ├── Channels/
│   │   │   │   ├── EmailChannel.php
│   │   │   │   └── SMSChannel.php
│   │   │   └── Jobs/
│   │   │       ├── SendEmailNotificationJob.php
│   │   │       └── SendSMSNotificationJob.php
│   │   │
│   │   └── Dashboard/
│   │       ├── Services/
│   │       │   └── DashboardService.php
│   │       └── Resources/
│   │           └── DashboardResource.php
│   │
│   ├── Modules/                           # Módulos específicos
│   │   └── Psychology/
│   │       ├── ClinicalNotes/
│   │       │   ├── Controllers/
│   │       │   │   └── ClinicalNoteController.php
│   │       │   ├── Services/
│   │       │   │   └── ClinicalNoteService.php
│   │       │   ├── Repositories/
│   │       │   │   └── ClinicalNoteRepository.php
│   │       │   ├── Models/
│   │       │   │   └── ClinicalNote.php
│   │       │   └── Policies/
│   │       │       └── ClinicalNotePolicy.php
│   │       │
│   │       ├── Assessments/
│   │       │   ├── Controllers/
│   │       │   │   └── AssessmentController.php
│   │       │   ├── Services/
│   │       │   │   └── AssessmentService.php
│   │       │   ├── Repositories/
│   │       │   │   └── AssessmentRepository.php
│   │       │   ├── Models/
│   │       │   │   ├── Assessment.php
│   │       │   │   ├── AssessmentQuestion.php
│   │       │   │   └── AssessmentAnswer.php
│   │       │   ├── Strategies/
│   │       │   │   ├── AssessmentCalculatorInterface.php
│   │       │   │   ├── BDI2Calculator.php
│   │       │   │   ├── PHQ9Calculator.php
│   │       │   │   └── GAD7Calculator.php
│   │       │   └── DTOs/
│   │       │       └── AssessmentResultDTO.php
│   │       │
│   │       ├── ConsentForms/
│   │       │   ├── Controllers/
│   │       │   │   └── ConsentFormController.php
│   │       │   ├── Services/
│   │       │   │   └── ConsentFormService.php
│   │       │   ├── Models/
│   │       │   │   └── ConsentForm.php
│   │       │   └── Events/
│   │       │       └── ConsentFormSigned.php
│   │       │
│   │       └── Teleconsultation/
│   │           ├── Controllers/
│   │           │   └── TeleconsultationController.php
│   │           ├── Services/
│   │           │   ├── TeleconsultationService.php
│   │           │   └── WebRTCService.php
│   │           ├── Models/
│   │           │   ├── TeleconsultationSession.php
│   │           │   └── TeleconsultationChatMessage.php
│   │           ├── WebSocket/
│   │           │   ├── Handlers/
│   │           │   │   ├── JoinRoomHandler.php
│   │           │   │   ├── LeaveRoomHandler.php
│   │           │   │   └── SendMessageHandler.php
│   │           │   └── Events/
│   │           │       ├── UserJoined.php
│   │           │       └── UserLeft.php
│   │           └── Jobs/
│   │               └── EndSessionJob.php
│   │
│   ├── PatientPortal/                    # Portal del paciente
│   │   ├── Booking/
│   │   │   ├── Controllers/
│   │   │   │   └── BookingController.php
│   │   │   ├── Services/
│   │   │   │   └── BookingService.php
│   │   │   └── Models/
│   │   │       └── Booking.php
│   │   │
│   │   ├── Payments/
│   │   │   ├── Controllers/
│   │   │   │   └── PaymentController.php
│   │   │   ├── Services/
│   │   │   │   └── PaymentService.php
│   │   │   └── Integrations/
│   │   │       ├── StripeService.php
│   │   │       └── PayPalService.php
│   │   │
│   │   ├── History/
│   │   │   ├── Controllers/
│   │   │   │   └── HistoryController.php
│   │   │   └── Services/
│   │   │       └── HistoryService.php
│   │   │
│   │   └── Communication/
│   │       ├── Controllers/
│   │       │   └── MessageController.php
│   │       ├── Services/
│   │       │   └── MessageService.php
│   │       └── Models/
│   │           ├── Message.php
│   │           └── Conversation.php
│   │
│   ├── Shared/                           # Componentes compartidos
│   │   ├── Traits/
│   │   │   ├── HasProfessional.php
│   │   │   ├── HasContact.php
│   │   │   ├── HasAuditLog.php
│   │   │   └── SoftDeletesWithArchive.php
│   │   │
│   │   ├── Interfaces/
│   │   │   ├── RepositoryInterface.php
│   │   │   └── ServiceInterface.php
│   │   │
│   │   ├── Enums/
│   │   │   ├── UserRole.php
│   │   │   ├── AppointmentStatus.php
│   │   │   ├── AppointmentType.php
│   │   │   ├── InvoiceStatus.php
│   │   │   └── AssessmentType.php
│   │   │
│   │   ├── ValueObjects/
│   │   │   ├── Address.php
│   │   │   └── Money.php
│   │   │
│   │   ├── Helpers/
│   │   │   ├── DateHelper.php
│   │   │   └── FileHelper.php
│   │   │
│   │   └── Exceptions/
│   │       ├── AppointmentConflictException.php
│   │       └── PaymentFailedException.php
│   │
│   ├── Http/
│   │   ├── Middleware/
│   │   │   ├── CheckModuleEnabled.php
│   │   │   ├── CheckProfessionalSubscription.php
│   │   │   └── EnsureTeleconsultationAccess.php
│   │   │
│   │   └── Kernel.php
│   │
│   └── Providers/
│       ├── CoreServiceProvider.php
│       ├── PsychologyModuleServiceProvider.php
│       └── PatientPortalServiceProvider.php
│
├── config/
│   ├── modules.php                       # Configuración de módulos
│   ├── teleconsultation.php
│   ├── billing.php
│   └── notifications.php
│
├── database/
│   ├── migrations/
│   │   ├── core/
│   │   │   ├── 0001_create_users_table.php
│   │   │   ├── 0002_create_professionals_table.php
│   │   │   ├── 0003_create_contacts_table.php
│   │   │   ├── 0004_create_appointments_table.php
│   │   │   ├── 0005_create_invoices_table.php
│   │   │   └── ...
│   │   │
│   │   ├── modules/
│   │   │   └── psychology/
│   │   │       ├── 0001_create_clinical_notes_table.php
│   │   │       ├── 0002_create_assessments_table.php
│   │   │       └── ...
│   │   │
│   │   └── patient_portal/
│   │       ├── 0001_create_patient_users_table.php
│   │       └── ...
│   │
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   ├── Core/
│   │   │   ├── UserSeeder.php
│   │   │   └── ProfessionalSeeder.php
│   │   └── Modules/
│   │       └── Psychology/
│   │           └── AssessmentTypeSeeder.php
│   │
│   └── factories/
│       ├── Core/
│       │   ├── ProfessionalFactory.php
│       │   └── ContactFactory.php
│       └── Modules/
│           └── Psychology/
│               └── ClinicalNoteFactory.php
│
├── routes/
│   ├── api/
│   │   ├── core.php                      # Rutas del core
│   │   ├── psychology.php                 # Rutas del módulo psicología
│   │   └── patient-portal.php             # Rutas del portal paciente
│   │
│   └── web.php
│
├── resources/
│   ├── views/
│   │   └── emails/
│   │       ├── appointments/
│   │       └── invoices/
│   │
│   └── lang/
│       ├── es/
│       └── en/
│
├── storage/
│   ├── app/
│   │   ├── public/
│   │   │   ├── invoices/                 # Facturas PDF
│   │   │   ├── documents/                # Documentos compartidos
│   │   │   └── consent-forms/            # Consentimientos firmados
│   │   │
│   │   └── private/                      # Archivos privados cifrados
│   │
│   └── logs/
│
├── tests/
│   ├── Unit/
│   │   ├── Core/
│   │   └── Modules/
│   │
│   ├── Feature/
│   │   ├── Core/
│   │   └── Modules/
│   │
│   └── Integration/
│
├── docker/
│   ├── Dockerfile
│   └── docker-compose.yml
│
└── .env.example
```

---

## 🎨 Patrones de Diseño

### 1. Repository Pattern

**Propósito**: Abstraer el acceso a datos y facilitar testing.

```php
// app/Shared/Interfaces/RepositoryInterface.php
interface RepositoryInterface
{
    public function find(string $id);
    public function findAll(array $filters = []);
    public function create(array $data);
    public function update(string $id, array $data);
    public function delete(string $id);
}

// app/Core/Contacts/Repositories/ContactRepositoryInterface.php
interface ContactRepositoryInterface extends RepositoryInterface
{
    public function findByProfessional(string $professionalId, array $filters = []);
    public function search(string $query, string $professionalId): Collection;
    public function archive(string $id): bool;
}

// app/Core/Contacts/Repositories/ContactRepository.php
class ContactRepository implements ContactRepositoryInterface
{
    public function __construct(
        private Contact $model
    ) {}

    public function find(string $id): ?Contact
    {
        return $this->model->findOrFail($id);
    }

    public function findByProfessional(string $professionalId, array $filters = []): Collection
    {
        $query = $this->model->where('professional_id', $professionalId);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('first_name', 'like', "%{$filters['search']}%")
                  ->orWhere('last_name', 'like', "%{$filters['search']}%")
                  ->orWhere('email', 'like', "%{$filters['search']}%");
            });
        }

        return $query->get();
    }

    public function create(array $data): Contact
    {
        return $this->model->create($data);
    }

    // ... otros métodos
}
```

### 2. Service Layer Pattern

**Propósito**: Contener la lógica de negocio.

```php
// app/Core/Appointments/Services/AppointmentService.php
class AppointmentService
{
    public function __construct(
        private AppointmentRepositoryInterface $repository,
        private NotificationService $notificationService,
        private CalendarService $calendarService,
        private EventDispatcher $dispatcher
    ) {}

    public function createAppointment(CreateAppointmentDTO $dto): Appointment
    {
        // Validar disponibilidad
        $this->validateAvailability($dto);

        // Crear cita
        $appointment = $this->repository->create([
            'professional_id' => $dto->professionalId,
            'contact_id' => $dto->contactId,
            'start_time' => $dto->startTime,
            'end_time' => $dto->endTime,
            'type' => $dto->type,
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        // Disparar evento
        $this->dispatcher->dispatch(new AppointmentCreated($appointment));

        // Sincronizar con Google Calendar
        if ($dto->syncCalendar) {
            $this->calendarService->sync($appointment);
        }

        return $appointment;
    }

    private function validateAvailability(CreateAppointmentDTO $dto): void
    {
        $conflicts = $this->repository->findConflicts(
            $dto->professionalId,
            $dto->startTime,
            $dto->endTime
        );

        if ($conflicts->isNotEmpty()) {
            throw new AppointmentConflictException('Horario no disponible');
        }
    }
}
```

### 3. DTO (Data Transfer Object) Pattern

**Propósito**: Transferir datos de forma estructurada y type-safe.

**⚠️ Enfoque Híbrido**: Los DTOs se usan **selectivamente** en módulos que requieren transformaciones complejas o type safety crítico. No todos los módulos los usan.

#### Cuándo Usar DTOs

**✅ Usa DTOs cuando:**
- Transformaciones complejas de datos
- Type safety crítico (seguridad, validaciones)
- Múltiples fuentes de datos (API, Web, Interno)
- Lógica de negocio encapsulada en transformación
- Inmutabilidad es importante

**❌ No uses DTOs cuando:**
- Datos simples sin transformaciones complejas
- Arrays son suficientes para la complejidad
- Simplicidad es prioridad

#### Módulos que Usan DTOs

| Módulo | DTOs | Razón |
|--------|------|-------|
| **Authentication** | ✅ Sí | Datos complejos, múltiples transformaciones, seguridad crítica |
| **Appointments** | ✅ Sí (opcional) | Transformaciones de fechas, validaciones complejas |
| **Assessments** | ✅ Sí | Cálculos complejos, resultados estructurados |
| **Contacts** | ❌ No | Datos simples, arrays suficientes |
| **ConsentForms** | ❌ No | Datos simples, arrays suficientes |

#### Ejemplo: Authentication DTOs

```php
// app/Core/Authentication/DTOs/LoginCredentialsDTO.php
readonly class LoginCredentialsDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember = false,
        public ?string $twoFactorCode = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password'],
            remember: $data['remember'] ?? false,
            twoFactorCode: $data['two_factor_code'] ?? null,
        );
    }
}

// app/Core/Authentication/DTOs/RegisterUserDTO.php
readonly class RegisterUserDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public string $firstName,
        public string $lastName,
        // ... más propiedades
    ) {}

    public function getUserData(): array
    {
        // Encapsula lógica de transformación
        return [
            'email' => $this->email,
            'first_name' => $this->firstName,
            // ...
        ];
    }

    public function getProfessionalData(): array
    {
        // Encapsula lógica de transformación
        return [
            'profession_type' => $this->getProfessionType(),
            // ...
        ];
    }
}
```

#### Ejemplo: Appointments DTO (Opcional)

```php
// app/Core/Appointments/DTOs/CreateAppointmentDTO.php
readonly class CreateAppointmentDTO
{
    public function __construct(
        public readonly string $professionalId,
        public readonly string $contactId,
        public readonly Carbon $startTime,
        public readonly Carbon $endTime,
        public readonly AppointmentType $type,
        public readonly ?string $notes = null,
        public readonly bool $syncCalendar = false,
    ) {}

    public static function fromRequest(StoreAppointmentRequest $request): self
    {
        return new self(
            professionalId: $request->professional_id,
            contactId: $request->contact_id,
            startTime: Carbon::parse($request->start_time),
            endTime: Carbon::parse($request->end_time),
            type: AppointmentType::from($request->type),
            notes: $request->notes,
            syncCalendar: $request->boolean('sync_calendar'),
        );
    }
}
```

#### Ejemplo: Sin DTOs (Arrays Simples)

```php
// app/Core/Contacts/Services/ContactService.php
public function createForProfessional(
    Professional $professional, 
    array $data,  // ← Array simple, suficiente
    int $createdBy
): Contact {
    $data['professional_id'] = $professional->id;
    $data['created_by'] = $createdBy;
    return $this->repository->create($data);
}
```

**Decisión**: Usar arrays cuando los datos son simples y no requieren transformaciones complejas. Usar DTOs cuando la complejidad y type safety lo justifican.

### 4. Strategy Pattern

**Propósito**: Intercambiar algoritmos en tiempo de ejecución.

```php
// app/Modules/Psychology/Assessments/Strategies/AssessmentCalculatorInterface.php
interface AssessmentCalculatorInterface
{
    public function calculate(array $answers): AssessmentResultDTO;
    public function getInterpretation(int $score): string;
}

// app/Modules/Psychology/Assessments/Strategies/BDI2Calculator.php
class BDI2Calculator implements AssessmentCalculatorInterface
{
    private const MAX_SCORE = 63;
    private const MIN_SCORE = 0;

    public function calculate(array $answers): AssessmentResultDTO
    {
        $score = array_sum(array_column($answers, 'value'));

        return new AssessmentResultDTO(
            score: $score,
            maxScore: self::MAX_SCORE,
            minScore: self::MIN_SCORE,
            interpretation: $this->getInterpretation($score),
            severity: $this->getSeverity($score),
        );
    }

    public function getInterpretation(int $score): string
    {
        return match(true) {
            $score <= 13 => 'Depresión mínima',
            $score <= 19 => 'Depresión leve',
            $score <= 28 => 'Depresión moderada',
            default => 'Depresión grave',
        };
    }

    private function getSeverity(int $score): string
    {
        return match(true) {
            $score <= 13 => 'minimal',
            $score <= 19 => 'mild',
            $score <= 28 => 'moderate',
            default => 'severe',
        };
    }
}

// app/Modules/Psychology/Assessments/Services/AssessmentService.php
class AssessmentService
{
    private array $calculators = [];

    public function __construct()
    {
        $this->calculators = [
            AssessmentType::BDI2->value => new BDI2Calculator(),
            AssessmentType::PHQ9->value => new PHQ9Calculator(),
            AssessmentType::GAD7->value => new GAD7Calculator(),
        ];
    }

    public function calculate(Assessment $assessment): AssessmentResultDTO
    {
        $calculator = $this->calculators[$assessment->type->value]
            ?? throw new InvalidArgumentException("Calculator not found for type: {$assessment->type->value}");

        return $calculator->calculate($assessment->answers->toArray());
    }
}
```

### 5. Factory Pattern

**Propósito**: Crear objetos complejos.

```php
// app/Core/Billing/Factories/InvoiceFactory.php
class InvoiceFactory
{
    public function __construct(
        private InvoiceService $invoiceService,
        private InvoiceRepositoryInterface $repository
    ) {}

    public function createForAppointment(Appointment $appointment): Invoice
    {
        $professional = $appointment->professional;
        $contact = $appointment->contact;

        $invoice = $this->repository->create([
            'professional_id' => $professional->id,
            'contact_id' => $contact->id,
            'appointment_id' => $appointment->id,
            'invoice_number' => $this->generateInvoiceNumber($professional),
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'subtotal' => $appointment->price ?? 0,
            'tax' => $this->calculateTax($appointment->price ?? 0),
            'total' => $this->calculateTotal($appointment->price ?? 0),
            'status' => InvoiceStatus::DRAFT,
        ]);

        // Añadir items
        $invoice->items()->create([
            'description' => "Consulta {$appointment->type->label()}",
            'quantity' => 1,
            'unit_price' => $appointment->price ?? 0,
            'tax_rate' => 21, // IVA
            'subtotal' => $appointment->price ?? 0,
            'total' => $this->calculateTotal($appointment->price ?? 0),
        ]);

        return $invoice;
    }

    private function generateInvoiceNumber(Professional $professional): string
    {
        $lastInvoice = $this->repository->getLastInvoiceNumber($professional->id);
        $number = $lastInvoice ? (int) substr($lastInvoice, -6) + 1 : 1;

        return sprintf('INV-%s-%06d', $professional->invoice_series, $number);
    }
}
```

### 6. Observer Pattern (Event-Driven)

**Propósito**: Comunicación desacoplada entre componentes.

```php
// app/Core/Appointments/Events/AppointmentCreated.php
class AppointmentCreated
{
    public function __construct(
        public readonly Appointment $appointment
    ) {}
}

// app/Core/Appointments/Listeners/SendAppointmentReminder.php
class SendAppointmentReminder
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    public function handle(AppointmentCreated $event): void
    {
        // Programar recordatorio 24h antes
        SendAppointmentReminderJob::dispatch($event->appointment)
            ->delay($event->appointment->start_time->subHours(24));
    }
}

// app/Core/Appointments/Listeners/SyncGoogleCalendar.php
class SyncGoogleCalendar
{
    public function handle(AppointmentCreated $event): void
    {
        if ($event->appointment->professional->google_calendar_enabled) {
            SyncGoogleCalendarJob::dispatch($event->appointment);
        }
    }
}
```

### 7. Facade Pattern

**Propósito**: Simplificar APIs complejas.

```php
// app/Shared/Facades/Teleconsultation.php
class Teleconsultation extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'teleconsultation.service';
    }
}

// Uso:
Teleconsultation::createSession($appointment);
Teleconsultation::joinRoom($sessionId, $userId);
```

---

## 🗄️ Base de Datos MySQL

### Esquema Principal

#### Tablas Core

```sql
-- Usuarios y Autenticación
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email_verified_at TIMESTAMP NULL,
    two_factor_enabled BOOLEAN DEFAULT FALSE,
    two_factor_secret TEXT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Profesionales
CREATE TABLE professionals (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    profession ENUM('psychology', 'physiotherapy', 'nutrition') NOT NULL,
    license_number VARCHAR(100) NOT NULL,
    specialties JSON NULL,
    phone VARCHAR(20) NULL,
    address_street VARCHAR(255) NULL,
    address_city VARCHAR(100) NULL,
    address_postal_code VARCHAR(10) NULL,
    address_country VARCHAR(100) DEFAULT 'España',
    timezone VARCHAR(50) DEFAULT 'Europe/Madrid',
    currency VARCHAR(3) DEFAULT 'EUR',
    language VARCHAR(2) DEFAULT 'es',
    invoice_series VARCHAR(10) DEFAULT 'A',
    subscription_plan VARCHAR(50) DEFAULT 'basic',
    subscription_status ENUM('active', 'cancelled', 'expired') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_profession (profession),
    INDEX idx_license_number (license_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Horarios de Trabajo
CREATE TABLE working_hours (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    professional_id BIGINT UNSIGNED NOT NULL,
    day_of_week TINYINT UNSIGNED NOT NULL, -- 0=Lunes, 6=Domingo
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (professional_id) REFERENCES professionals(id) ON DELETE CASCADE,
    UNIQUE KEY unique_professional_day (professional_id, day_of_week),
    INDEX idx_professional_id (professional_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contactos (Pacientes)
CREATE TABLE contacts (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    professional_id BIGINT UNSIGNED NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NULL,
    phone VARCHAR(20) NULL,
    date_of_birth DATE NULL,
    gender ENUM('male', 'female', 'other', 'prefer_not_to_say') NULL,
    dni VARCHAR(20) NULL,
    address_street VARCHAR(255) NULL,
    address_city VARCHAR(100) NULL,
    address_postal_code VARCHAR(10) NULL,
    address_country VARCHAR(100) DEFAULT 'España',
    status ENUM('active', 'inactive', 'archived') DEFAULT 'active',
    tags JSON NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (professional_id) REFERENCES professionals(id) ON DELETE CASCADE,
    INDEX idx_professional_id (professional_id),
    INDEX idx_status (status),
    INDEX idx_email (email),
    INDEX idx_deleted_at (deleted_at),
    FULLTEXT idx_search (first_name, last_name, email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contactos de Emergencia
CREATE TABLE emergency_contacts (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    contact_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    relationship VARCHAR(100) NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE,
    INDEX idx_contact_id (contact_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Citas
CREATE TABLE appointments (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    professional_id BIGINT UNSIGNED NOT NULL,
    contact_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NULL,
    type ENUM('in-person', 'teleconsultation', 'hybrid') NOT NULL,
    status ENUM('scheduled', 'confirmed', 'in-progress', 'completed', 'cancelled', 'no-show') DEFAULT 'scheduled',
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    duration INT UNSIGNED NOT NULL, -- minutos
    timezone VARCHAR(50) DEFAULT 'Europe/Madrid',
    location_type ENUM('office', 'online', 'patient-home') NULL,
    location_address VARCHAR(255) NULL,
    online_link VARCHAR(500) NULL,
    notes TEXT NULL,
    price DECIMAL(10, 2) NULL,
    reminder_sent BOOLEAN DEFAULT FALSE,
    reminder_sent_at TIMESTAMP NULL,
    cancelled_at TIMESTAMP NULL,
    cancellation_reason TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (professional_id) REFERENCES professionals(id) ON DELETE CASCADE,
    FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE,
    INDEX idx_professional_id (professional_id),
    INDEX idx_contact_id (contact_id),
    INDEX idx_start_time (start_time),
    INDEX idx_status (status),
    INDEX idx_type (type),
    INDEX idx_professional_start (professional_id, start_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Facturas
CREATE TABLE invoices (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    professional_id BIGINT UNSIGNED NOT NULL,
    contact_id BIGINT UNSIGNED NOT NULL,
    appointment_id BIGINT UNSIGNED NULL,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    series VARCHAR(10) DEFAULT 'A',
    issue_date DATE NOT NULL,
    due_date DATE NOT NULL,
    status ENUM('draft', 'sent', 'paid', 'overdue', 'cancelled') DEFAULT 'draft',
    subtotal DECIMAL(10, 2) NOT NULL DEFAULT 0,
    tax DECIMAL(10, 2) NOT NULL DEFAULT 0,
    total DECIMAL(10, 2) NOT NULL DEFAULT 0,
    currency VARCHAR(3) DEFAULT 'EUR',
    notes TEXT NULL,
    pdf_url VARCHAR(500) NULL,
    xml_url VARCHAR(500) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (professional_id) REFERENCES professionals(id) ON DELETE CASCADE,
    FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL,
    INDEX idx_professional_id (professional_id),
    INDEX idx_contact_id (contact_id),
    INDEX idx_invoice_number (invoice_number),
    INDEX idx_status (status),
    INDEX idx_due_date (due_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Items de Factura
CREATE TABLE invoice_items (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    invoice_id BIGINT UNSIGNED NOT NULL,
    description VARCHAR(500) NOT NULL,
    quantity DECIMAL(8, 2) NOT NULL DEFAULT 1,
    unit_price DECIMAL(10, 2) NOT NULL,
    tax_rate DECIMAL(5, 2) NOT NULL DEFAULT 21.00,
    subtotal DECIMAL(10, 2) NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE,
    INDEX idx_invoice_id (invoice_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pagos
CREATE TABLE payments (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    invoice_id BIGINT UNSIGNED NOT NULL,
    contact_id BIGINT UNSIGNED NOT NULL,
    professional_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'EUR',
    method ENUM('card', 'paypal', 'bank-transfer', 'cash') NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    transaction_id VARCHAR(255) NULL,
    payment_intent_id VARCHAR(255) NULL, -- Stripe
    paid_at TIMESTAMP NULL,
    refunded_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE,
    FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE,
    FOREIGN KEY (professional_id) REFERENCES professionals(id) ON DELETE CASCADE,
    INDEX idx_invoice_id (invoice_id),
    INDEX idx_status (status),
    INDEX idx_transaction_id (transaction_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tablas Módulo Psicología

```sql
-- Notas Clínicas
CREATE TABLE psychology_clinical_notes (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    contact_id BIGINT UNSIGNED NOT NULL,
    professional_id BIGINT UNSIGNED NOT NULL,
    appointment_id BIGINT UNSIGNED NULL,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    type ENUM('session', 'assessment', 'follow-up', 'general') DEFAULT 'session',
    tags JSON NULL,
    is_confidential BOOLEAN DEFAULT FALSE,
    requires_consent BOOLEAN DEFAULT FALSE,
    created_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE,
    FOREIGN KEY (professional_id) REFERENCES professionals(id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_contact_id (contact_id),
    INDEX idx_professional_id (professional_id),
    INDEX idx_appointment_id (appointment_id),
    FULLTEXT idx_content (title, content)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Evaluaciones
CREATE TABLE psychology_assessments (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    contact_id BIGINT UNSIGNED NOT NULL,
    professional_id BIGINT UNSIGNED NOT NULL,
    type ENUM('beck-depression', 'beck-anxiety', 'phq-9', 'gad-7', 'pcl-5', 'custom') NOT NULL,
    title VARCHAR(255) NOT NULL,
    score INT NULL,
    max_score INT NULL,
    interpretation TEXT NULL,
    severity VARCHAR(50) NULL,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE,
    FOREIGN KEY (professional_id) REFERENCES professionals(id) ON DELETE CASCADE,
    INDEX idx_contact_id (contact_id),
    INDEX idx_professional_id (professional_id),
    INDEX idx_type (type),
    INDEX idx_completed_at (completed_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Preguntas de Evaluación
CREATE TABLE psychology_assessment_questions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    assessment_id BIGINT UNSIGNED NOT NULL,
    question_text TEXT NOT NULL,
    question_type ENUM('multiple-choice', 'scale', 'text') NOT NULL,
    options JSON NULL,
    required BOOLEAN DEFAULT TRUE,
    order_index INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (assessment_id) REFERENCES psychology_assessments(id) ON DELETE CASCADE,
    INDEX idx_assessment_id (assessment_id),
    INDEX idx_order (assessment_id, order_index)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Respuestas de Evaluación
CREATE TABLE psychology_assessment_answers (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    assessment_id BIGINT UNSIGNED NOT NULL,
    question_id BIGINT UNSIGNED NOT NULL,
    value VARCHAR(255) NULL,
    text_value TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (assessment_id) REFERENCES psychology_assessments(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES psychology_assessment_questions(id) ON DELETE CASCADE,
    UNIQUE KEY unique_assessment_question (assessment_id, question_id),
    INDEX idx_assessment_id (assessment_id),
    INDEX idx_question_id (question_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Consentimientos
CREATE TABLE psychology_consent_forms (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    contact_id BIGINT UNSIGNED NOT NULL,
    professional_id BIGINT UNSIGNED NOT NULL,
    type ENUM('treatment', 'teleconsultation', 'data-sharing', 'recording') NOT NULL,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    status ENUM('pending', 'signed', 'revoked') DEFAULT 'pending',
    signed_at TIMESTAMP NULL,
    signature_ip_address VARCHAR(45) NULL,
    signature_user_agent TEXT NULL,
    signature_data TEXT NULL, -- Hash de firma
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE,
    FOREIGN KEY (professional_id) REFERENCES professionals(id) ON DELETE CASCADE,
    INDEX idx_contact_id (contact_id),
    INDEX idx_professional_id (professional_id),
    INDEX idx_status (status),
    INDEX idx_type (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tablas Teleconsulta

```sql
-- Sesiones de Teleconsulta
CREATE TABLE teleconsultation_sessions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    appointment_id BIGINT UNSIGNED NOT NULL,
    professional_id BIGINT UNSIGNED NOT NULL,
    contact_id BIGINT UNSIGNED NOT NULL,
    status ENUM('scheduled', 'waiting', 'active', 'ended', 'cancelled') DEFAULT 'scheduled',
    room_id VARCHAR(255) UNIQUE NOT NULL,
    room_token VARCHAR(500) NOT NULL,
    professional_joined_at TIMESTAMP NULL,
    professional_left_at TIMESTAMP NULL,
    patient_joined_at TIMESTAMP NULL,
    patient_left_at TIMESTAMP NULL,
    scheduled_start DATETIME NOT NULL,
    actual_start TIMESTAMP NULL,
    actual_end TIMESTAMP NULL,
    duration INT UNSIGNED NULL, -- minutos
    video_enabled BOOLEAN DEFAULT TRUE,
    audio_enabled BOOLEAN DEFAULT TRUE,
    screen_share_enabled BOOLEAN DEFAULT FALSE,
    chat_enabled BOOLEAN DEFAULT TRUE,
    recording_enabled BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE,
    FOREIGN KEY (professional_id) REFERENCES professionals(id) ON DELETE CASCADE,
    FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE,
    INDEX idx_appointment_id (appointment_id),
    INDEX idx_room_id (room_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Mensajes de Chat en Teleconsulta
CREATE TABLE teleconsultation_chat_messages (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    session_id BIGINT UNSIGNED NOT NULL,
    sender_id BIGINT UNSIGNED NOT NULL,
    sender_type ENUM('professional', 'patient') NOT NULL,
    content TEXT NOT NULL,
    message_type ENUM('text', 'file', 'link') DEFAULT 'text',
    file_url VARCHAR(500) NULL,
    read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES teleconsultation_sessions(id) ON DELETE CASCADE,
    INDEX idx_session_id (session_id),
    INDEX idx_sender_id (sender_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tablas Portal del Paciente

```sql
-- Usuarios del Portal del Paciente
CREATE TABLE patient_users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    contact_id BIGINT UNSIGNED NOT NULL,
    professional_id BIGINT UNSIGNED NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email_verified_at TIMESTAMP NULL,
    last_login_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE,
    FOREIGN KEY (professional_id) REFERENCES professionals(id) ON DELETE CASCADE,
    INDEX idx_email (email),
    INDEX idx_contact_id (contact_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Reservas Online
CREATE TABLE patient_bookings (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    contact_id BIGINT UNSIGNED NOT NULL,
    professional_id BIGINT UNSIGNED NOT NULL,
    requested_date DATE NOT NULL,
    requested_time TIME NOT NULL,
    duration INT UNSIGNED NOT NULL,
    type ENUM('in-person', 'teleconsultation') NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'rejected') DEFAULT 'pending',
    patient_notes TEXT NULL,
    professional_notes TEXT NULL,
    confirmed_at TIMESTAMP NULL,
    cancelled_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE,
    FOREIGN KEY (professional_id) REFERENCES professionals(id) ON DELETE CASCADE,
    INDEX idx_contact_id (contact_id),
    INDEX idx_professional_id (professional_id),
    INDEX idx_status (status),
    INDEX idx_requested_date (requested_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Mensajería
CREATE TABLE patient_conversations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    contact_id BIGINT UNSIGNED NOT NULL,
    professional_id BIGINT UNSIGNED NOT NULL,
    last_message_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE,
    FOREIGN KEY (professional_id) REFERENCES professionals(id) ON DELETE CASCADE,
    UNIQUE KEY unique_conversation (contact_id, professional_id),
    INDEX idx_professional_id (professional_id),
    INDEX idx_last_message_at (last_message_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE patient_messages (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    conversation_id BIGINT UNSIGNED NOT NULL,
    sender_id BIGINT UNSIGNED NOT NULL,
    sender_type ENUM('professional', 'patient') NOT NULL,
    recipient_id BIGINT UNSIGNED NOT NULL,
    content TEXT NOT NULL,
    message_type ENUM('text', 'file', 'link') DEFAULT 'text',
    file_url VARCHAR(500) NULL,
    status ENUM('sent', 'delivered', 'read') DEFAULT 'sent',
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (conversation_id) REFERENCES patient_conversations(id) ON DELETE CASCADE,
    INDEX idx_conversation_id (conversation_id),
    INDEX idx_sender_id (sender_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tablas de Notificaciones y Auditoría

```sql
-- Notificaciones
CREATE TABLE notifications (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    notifiable_type VARCHAR(255) NOT NULL,
    notifiable_id BIGINT UNSIGNED NOT NULL,
    type VARCHAR(255) NOT NULL,
    data JSON NOT NULL,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_notifiable (notifiable_type, notifiable_id),
    INDEX idx_read_at (read_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Logs de Auditoría
CREATE TABLE audit_logs (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NULL,
    user_type ENUM('professional', 'patient', 'system') NOT NULL,
    action VARCHAR(50) NOT NULL,
    resource_type VARCHAR(100) NOT NULL,
    resource_id BIGINT UNSIGNED NOT NULL,
    changes JSON NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_resource (resource_type, resource_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Optimizaciones MySQL

1. **Índices**: Índices en foreign keys y campos de búsqueda frecuente
2. **Particionamiento**: Considerar particionamiento por fecha en tablas grandes (audit_logs, notifications)
3. **Full-Text Search**: Para búsquedas en contenido (clinical_notes, contacts)
4. **JSON Columns**: Para datos flexibles (specialties, tags)
5. **Connection Pooling**: Usar pool de conexiones para alta concurrencia

---

## 🔌 API REST

### Estructura de Rutas

```php
// routes/api/core.php
Route::prefix('v1')->group(function () {
    // Autenticación
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/auth/refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');

    // Rutas protegidas
    Route::middleware('auth:sanctum')->group(function () {
        // Profesionales
        Route::get('/professionals/me', [ProfessionalController::class, 'me']);
        Route::put('/professionals/me', [ProfessionalController::class, 'update']);
        
        // Contactos
        Route::apiResource('contacts', ContactController::class);
        Route::post('contacts/import', [ContactController::class, 'import']);
        
        // Citas
        Route::apiResource('appointments', AppointmentController::class);
        Route::get('appointments/calendar', [AppointmentController::class, 'calendar']);
        Route::post('appointments/{id}/cancel', [AppointmentController::class, 'cancel']);
        
        // Facturación
        Route::apiResource('invoices', InvoiceController::class);
        Route::post('invoices/{id}/send', [InvoiceController::class, 'send']);
        Route::get('invoices/{id}/pdf', [InvoiceController::class, 'downloadPdf']);
        
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index']);
    });
});

// routes/api/psychology.php
Route::prefix('v1/psychology')->middleware(['auth:sanctum', 'module:psychology'])->group(function () {
    Route::apiResource('clinical-notes', ClinicalNoteController::class);
    Route::apiResource('assessments', AssessmentController::class);
    Route::post('assessments/{id}/calculate', [AssessmentController::class, 'calculate']);
    Route::apiResource('consent-forms', ConsentFormController::class);
    Route::post('consent-forms/{id}/sign', [ConsentFormController::class, 'sign']);
});

// routes/api/teleconsultation.php
Route::prefix('v1/teleconsultation')->middleware(['auth:sanctum'])->group(function () {
    Route::post('/sessions', [TeleconsultationController::class, 'create']);
    Route::get('/sessions/{id}', [TeleconsultationController::class, 'show']);
    Route::post('/sessions/{id}/join', [TeleconsultationController::class, 'join']);
    Route::post('/sessions/{id}/end', [TeleconsultationController::class, 'end']);
    Route::get('/sessions/{id}/chat', [TeleconsultationController::class, 'chat']);
    Route::post('/sessions/{id}/chat', [TeleconsultationController::class, 'sendMessage']);
});

// routes/api/patient-portal.php
Route::prefix('v1/patient')->middleware('auth:patient')->group(function () {
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::delete('/bookings/{id}', [BookingController::class, 'cancel']);
    
    Route::get('/history', [HistoryController::class, 'index']);
    Route::get('/documents', [DocumentController::class, 'index']);
    
    Route::get('/invoices', [InvoiceController::class, 'index']);
    Route::post('/invoices/{id}/pay', [PaymentController::class, 'pay']);
    
    Route::get('/messages', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class, 'store']);
});
```

### API Resources

```php
// app/Core/Contacts/Resources/ContactResource.php
class ContactResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'appointments_count' => $this->whenLoaded('appointments', fn() => $this->appointments->count()),
        ];
    }
}
```

---

## 🔔 WebSockets y Teleconsulta

### Configuración Laravel Echo + Soketi

```php
// config/broadcasting.php
'connections' => [
    'pusher' => [
        'driver' => 'pusher',
        'key' => env('PUSHER_APP_KEY'),
        'secret' => env('PUSHER_APP_SECRET'),
        'app_id' => env('PUSHER_APP_ID'),
        'options' => [
            'host' => env('PUSHER_HOST', '127.0.0.1'),
            'port' => env('PUSHER_PORT', 6001),
            'scheme' => env('PUSHER_SCHEME', 'http'),
            'encrypted' => true,
        ],
    ],
],
```

### Eventos WebSocket

```php
// app/Modules/Psychology/Teleconsultation/Events/UserJoined.php
class UserJoined implements ShouldBroadcast
{
    public function __construct(
        public TeleconsultationSession $session,
        public string $userId,
        public string $userType
    ) {}

    public function broadcastOn(): Channel
    {
        return new PrivateChannel("teleconsultation.{$this->session->room_id}");
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->userId,
            'user_type' => $this->userType,
            'joined_at' => now()->toIso8601String(),
        ];
    }
}
```

---

## 🧩 Sistema de Módulos

### Service Provider de Módulo

```php
// app/Providers/PsychologyModuleServiceProvider.php
class PsychologyModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registrar servicios del módulo
        $this->app->singleton(ClinicalNoteService::class);
        $this->app->singleton(AssessmentService::class);
    }

    public function boot(): void
    {
        // Cargar rutas
        $this->loadRoutesFrom(__DIR__ . '/../../Modules/Psychology/routes.php');
        
        // Cargar migraciones
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations/modules/psychology');
        
        // Registrar policies
        Gate::policy(ClinicalNote::class, ClinicalNotePolicy::class);
    }
}
```

### Middleware de Módulo

```php
// app/Http/Middleware/CheckModuleEnabled.php
class CheckModuleEnabled
{
    public function handle(Request $request, Closure $next, string $module): Response
    {
        if (!config("modules.enabled.{$module}")) {
            abort(404, "Module {$module} is not enabled");
        }

        return $next($request);
    }
}
```

---

## 🔐 Autenticación y Autorización

### Módulo Authentication

El módulo de autenticación utiliza **DTOs** para transferencia de datos debido a la complejidad de transformaciones y la criticidad de seguridad. Ver documentación completa en [`app/Core/Authentication/README.md`](app/Core/Authentication/README.md).

**Estructura:**
- **DTOs**: `LoginCredentialsDTO`, `RegisterUserDTO`
- **Repositories**: `UserRepository`, `ProfessionalRepository`
- **Services**: `AuthService`, `TwoFactorService`
- **Controllers**: `AuthController`, `EmailVerificationController`, `PasswordResetController`

### Laravel Sanctum para API

```php
// app/Core/Authentication/Services/AuthService.php
class AuthService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ProfessionalRepository $professionalRepository,
    ) {}

    public function login(LoginCredentialsDTO $credentials): array
    {
        $user = $this->userRepository->findByEmail($credentials->email);

        if (!$user || !Hash::check($credentials->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user->load(['professional', 'roles', 'permissions']),
            'token' => $token,
        ];
    }

    public function register(RegisterUserDTO $dto): array
    {
        // Lógica de registro usando DTOs
        // Ver app/Core/Authentication/README.md para detalles
    }
}
```

### Spatie Laravel Permission

```php
// Roles y permisos
Role::create(['name' => 'professional']);
Role::create(['name' => 'assistant']);
Role::create(['name' => 'patient']);

Permission::create(['name' => 'view clinical notes']);
Permission::create(['name' => 'create clinical notes']);
Permission::create(['name' => 'view appointments']);
// ...

$professionalRole->givePermissionTo(['view clinical notes', 'create clinical notes']);
```

---

## 📦 Jobs y Queues

### Configuración de Queues

```php
// config/queue.php
'connections' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => env('REDIS_QUEUE', 'default'),
        'retry_after' => 90,
        'block_for' => null,
    ],
],
```

### Ejemplo de Job

```php
// app/Core/Appointments/Jobs/SendAppointmentReminderJob.php
class SendAppointmentReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Appointment $appointment
    ) {}

    public function handle(NotificationService $notificationService): void
    {
        $notificationService->sendAppointmentReminder($this->appointment);
    }
}
```

---

## 🧪 Testing

### Estructura de Tests

```php
// tests/Feature/Core/Appointments/AppointmentTest.php
class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_professional_can_create_appointment(): void
    {
        $professional = Professional::factory()->create();
        $contact = Contact::factory()->create(['professional_id' => $professional->id]);

        $response = $this->actingAs($professional->user)
            ->postJson('/api/v1/appointments', [
                'contact_id' => $contact->id,
                'start_time' => now()->addDay()->toIso8601String(),
                'end_time' => now()->addDay()->addHour()->toIso8601String(),
                'type' => 'in-person',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'start_time', 'end_time', 'status'],
            ]);
    }
}
```

---

## 🚀 Despliegue

### Docker Compose (Desarrollo)

```yaml
# docker-compose.yml
version: '3.8'

services:
  app:
    build: .
    volumes:
      - .:/var/www/html
    ports:
      - "8000:8000"
    depends_on:
      - mysql
      - redis

  mysql:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: medi_pro_365
      MYSQL_ROOT_PASSWORD: root
    ports:
      - "3306:3306"
    volumes:
      - mysql_data:/var/lib/mysql

  redis:
    image: redis:7-alpine
    ports:
      - "6379:6379"

volumes:
  mysql_data:
```

---

## 📝 Notas Finales

### Mejores Prácticas

1. **Separación de Responsabilidades**: Cada capa tiene su responsabilidad clara
2. **Inyección de Dependencias**: Usar DI para desacoplar componentes
3. **Type Hints**: Usar tipos estrictos en PHP 8.2+
4. **Validación**: Form Requests para validación de entrada
5. **Autorización**: Policies para autorización granular
6. **Eventos**: Usar eventos para desacoplar acciones
7. **Jobs**: Operaciones pesadas en background
8. **Caché**: Redis para caché y sesiones
9. **Índices**: Índices apropiados en MySQL
10. **Logging**: Logs estructurados para debugging

### Próximos Pasos

1. Configurar entorno de desarrollo
2. Crear migraciones base
3. Implementar autenticación
4. Desarrollar módulo Core
5. Implementar módulo Psychology
6. Integrar teleconsulta
7. Desarrollar portal del paciente
8. Testing y optimización

---

**Versión**: 1.0  
**Última actualización**: 2024  
**Framework**: Laravel 12  
**Base de Datos**: MySQL 8.0+

