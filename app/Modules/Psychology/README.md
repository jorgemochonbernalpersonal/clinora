# Psychology Module

Módulo específico para profesionales de psicología en Clinora.

## Estructura

```
Psychology/
├── ClinicalNotes/          # Notas clínicas SOAP
│   ├── Controllers/
│   ├── Models/
│   ├── Repositories/
│   ├── Services/
│   └── Requests/
├── ConsentForms/          # Formularios de consentimiento
│   └── Templates/
├── Assessments/           # Evaluaciones psicológicas (futuro)
├── PsychologyModule.php   # Clase principal del módulo
└── PsychologyModuleServiceProvider.php
```

## Características

### Clinical Notes (Notas Clínicas)
- Formato SOAP (Subjective, Objective, Assessment, Plan)
- Numeración automática de sesiones
- Evaluación de riesgo
- Firma digital de notas
- Historial completo por paciente

### Consent Forms (Formularios de Consentimiento)
- Plantillas específicas para psicología
- Consentimiento inicial de tratamiento
- Consentimiento para teleconsulta
- Gestión de consentimientos para menores

## Uso

### Obtener el módulo actual

```php
use App\Shared\Helpers\ModuleHelper;

$module = ModuleHelper::getCurrentModule();
```

### Usar servicios del módulo

```php
use App\Modules\Psychology\ClinicalNotes\Services\ClinicalNoteService;

$service = app(ClinicalNoteService::class);
$notes = $service->getNotesForProfessional($professionalId);
```

## Configuración

El módulo se habilita/deshabilita en `config/modules.php`:

```php
'psychology' => [
    'enabled' => env('MODULE_PSYCHOLOGY_ENABLED', true),
],
```

## Rutas

- **Web**: `routes/psychologist.php`
- **API**: `routes/api/psychology.php`

## Migraciones

Las migraciones del módulo se encuentran en:
`database/migrations/modules/psychology/`

