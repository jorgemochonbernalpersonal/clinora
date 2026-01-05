# Reorganización de Componentes Livewire

## Estructura Nueva

### Componentes por Profesión
```
app/Livewire/
├── Psychology/                    # Componentes específicos de Psychology
│   ├── ClinicalNotes/
│   │   ├── ClinicalNoteForm.php
│   │   └── Timeline.php
│   ├── ConsentForms/
│   │   └── ConsentFormList.php
│   ├── Appointments/
│   ├── Patients/
│   └── DashboardHome.php
│
├── Shared/                        # Componentes compartidos (futuro)
│   ├── Auth/
│   ├── Profile/
│   └── Patients/
│
└── [Otras carpetas actuales]      # Mantener por compatibilidad
```

## Cambios Realizados

### 1. ConsentForms movidos a Psychology
- ✅ `ConsentFormList` movido a `Livewire/Psychology/ConsentForms/`
- ✅ Rutas actualizadas en `routes/psychologist.php`

### 2. Componentes Psychology
Los componentes específicos de Psychology ya están correctamente organizados en:
- `Livewire/Psychologist/ClinicalNotes/`
- `Livewire/Psychologist/Appointments/`
- `Livewire/Psychologist/Patients/`

## Próximos Pasos (Opcional)

### Mover componentes compartidos a Shared
Los siguientes componentes son compartidos y podrían moverse a `Shared/`:
- `Auth/` - Login, Register, VerifyEmail, etc.
- `Profile/` - ProfileSettings, ChangePassword, etc.
- `Patients/PatientList.php` - Lista genérica de pacientes

### Eliminar duplicados
Hay componentes duplicados en `Dashboard/` que podrían eliminarse:
- `Dashboard/ClinicalNotes/` (duplicado de `Psychologist/ClinicalNotes/`)
- `Dashboard/Patients/` (duplicado de `Psychologist/Patients/`)
- `Dashboard/Appointments/` (duplicado de `Psychologist/Appointments/`)

## Compatibilidad

Por ahora, mantenemos los componentes antiguos para no romper referencias. La migración puede hacerse gradualmente.

