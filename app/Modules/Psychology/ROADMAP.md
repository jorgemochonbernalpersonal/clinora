# Psychology Module - Roadmap

## ✅ Completado

### Estructura Base
- [x] Módulo Psychology creado e implementado
- [x] Service Provider configurado
- [x] Rutas registradas
- [x] Migraciones organizadas

### Clinical Notes
- [x] Modelo movido a módulo
- [x] Servicio y repositorio implementados
- [x] Controlador refactorizado
- [x] Componentes Livewire organizados

### Consent Forms
- [x] Plantillas específicas creadas
- [x] Componente Livewire movido a Psychology
- [x] Integración con Core/ConsentForms

## 🚧 En Progreso / Pendiente

### Assessments (Evaluaciones Psicológicas)
- [ ] Crear estructura de Assessments
- [ ] Implementar BDI-II (Beck Depression Inventory)
- [ ] Implementar PHQ-9 (Patient Health Questionnaire)
- [ ] Implementar GAD-7 (Generalized Anxiety Disorder)
- [ ] Crear calculadoras de puntuación
- [ ] Crear vistas de resultados
- [ ] Gráficos de evolución temporal

### Teleconsultation
- [ ] Integración con WebRTC
- [ ] Gestión de sesiones
- [ ] Grabación de sesiones (con consentimiento)
- [ ] Chat durante sesión
- [ ] Sala de espera virtual

### Reports & Analytics
- [ ] Informes de progreso del paciente
- [ ] Estadísticas de sesiones
- [ ] Exportación de datos
- [ ] Gráficos y visualizaciones

## 💡 Ideas Futuras

### Advanced Features
- [ ] Plantillas personalizables de notas clínicas
- [ ] Integración con escalas de evaluación adicionales
- [ ] Sistema de alertas para riesgo alto
- [ ] Exportación a PDF de notas clínicas
- [ ] Historial completo del paciente con timeline

### Integrations
- [ ] Integración con Google Calendar
- [ ] Sincronización con otros sistemas
- [ ] API para integraciones externas

## 📋 Estructura de Archivos Futura

```
Psychology/
├── ClinicalNotes/          ✅ Completo
├── ConsentForms/           ✅ Completo
├── Assessments/            🚧 Pendiente
│   ├── Models/
│   │   ├── Assessment.php
│   │   ├── AssessmentQuestion.php
│   │   └── AssessmentAnswer.php
│   ├── Services/
│   │   ├── AssessmentService.php
│   │   └── Calculators/
│   │       ├── BDI2Calculator.php
│   │       ├── PHQ9Calculator.php
│   │       └── GAD7Calculator.php
│   └── Controllers/
│
├── Teleconsultation/       🚧 Pendiente
│   ├── Models/
│   ├── Services/
│   └── Controllers/
│
└── Reports/                💡 Futuro
    ├── Services/
    └── Controllers/
```

## 🎯 Prioridades

1. **Alta**: Assessments básicos (BDI-II, PHQ-9, GAD-7)
2. **Media**: Teleconsultation básica
3. **Baja**: Reports avanzados y analytics

## 📚 Recursos

- [BDI-II Documentation](https://www.pearsonassessments.com/store/usassessments/en/Store/Professional-Assessments/Personality-%26-Social/Beck-Depression-Inventory-II/p/100000159.html)
- [PHQ-9 Documentation](https://www.phqscreeners.com/)
- [GAD-7 Documentation](https://www.phqscreeners.com/)

