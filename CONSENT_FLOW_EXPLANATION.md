# Flujo de Consentimientos en Clinora

## 📋 Dos Tipos de Consentimientos

### 1. **Consentimiento Básico de Protección de Datos (RGPD)**
**Cuándo se acepta:** Al crear el paciente  
**Dónde:** Formulario de creación de paciente  
**Qué es:** Checkbox simple que autoriza el tratamiento básico de datos personales

```php
// En Contact (contacts table)
data_protection_consent: boolean
data_protection_consent_at: timestamp
```

**Propósito:**
- ✅ Cumplir con RGPD/LOPDGDD
- ✅ Autorizar almacenamiento de datos básicos del paciente
- ✅ Requisito mínimo para crear un registro de paciente

**Texto:**
> "Autorizo el tratamiento de mis datos personales de salud con fines asistenciales y administrativos."

---

### 2. **Consentimiento Informado Completo**
**Cuándo se acepta:** Antes de iniciar el tratamiento psicológico  
**Dónde:** Módulo de Consentimientos (`/psychologist/consent-forms`)  
**Qué es:** Documento completo y detallado que el paciente debe leer y firmar digitalmente

```php
// En ConsentForm (consent_forms table)
- Documento completo con todas las secciones
- Firma digital (canvas)
- IP, dispositivo, timestamp
- Versión del documento
- Datos adicionales (duración, técnicas, etc.)
```

**Propósito:**
- ✅ Cumplir con Código Deontológico del COP
- ✅ Informar al paciente sobre el tratamiento
- ✅ Documentar consentimiento explícito para tratamiento psicológico
- ✅ Protección legal del profesional

**Contenido:**
- Identificación del profesional
- Naturaleza del tratamiento
- Objetivos y metodología
- Duración y frecuencia
- Confidencialidad y excepciones
- Protección de datos (RGPD)
- Costes y cancelaciones
- Derechos del paciente

---

## 🔄 Flujo Recomendado

### Paso 1: Crear Paciente
```
1. Profesional crea nuevo paciente
2. Paciente acepta checkbox de "Consentimiento de Protección de Datos (RGPD)"
3. Se guarda: data_protection_consent = true, data_protection_consent_at = now()
4. Paciente creado ✅
```

### Paso 2: Crear Consentimiento Informado
```
1. Profesional va a "Consentimientos" en el sidebar
2. Crea nuevo consentimiento para el paciente
3. Selecciona tipo (inicial_treatment, teleconsultation, etc.)
4. Completa datos adicionales (duración, frecuencia, etc.)
5. Sistema genera documento automáticamente
6. Consentimiento creado (estado: PENDIENTE) ⏳
```

### Paso 3: Firmar Consentimiento
```
1. Profesional abre el consentimiento
2. Paciente lee el documento completo
3. Paciente firma con canvas digital
4. Se guarda: signed_at = now(), is_valid = true
5. Consentimiento firmado ✅
```

### Paso 4: Iniciar Tratamiento
```
1. Ahora SÍ se puede:
   - Crear citas
   - Crear notas clínicas
   - Iniciar tratamiento
```

---

## ⚠️ Validaciones Recomendadas

### Antes de crear cita:
```php
// Verificar que tenga consentimiento informado válido
if (!$contact->hasValidConsent('initial_treatment')) {
    // Mostrar advertencia o bloquear creación de cita
}
```

### Antes de crear nota clínica:
```php
// Verificar que tenga consentimiento informado válido
if (!$contact->hasValidConsent('initial_treatment')) {
    // Mostrar advertencia
}
```

---

## 📊 Diferencia Clave

| Aspecto | Consentimiento RGPD | Consentimiento Informado |
|---------|-------------------|------------------------|
| **Momento** | Al crear paciente | Antes de tratamiento |
| **Complejidad** | Checkbox simple | Documento completo |
| **Firma** | No requiere | Sí, firma digital |
| **Propósito** | Autorizar datos | Autorizar tratamiento |
| **Legal** | RGPD/LOPDGDD | Código Deontológico COP |
| **Almacenamiento** | `contacts.data_protection_consent` | `consent_forms` table |

---

## 💡 Mejoras Futuras Sugeridas

1. **Validación automática:**
   - Bloquear creación de citas sin consentimiento informado
   - Mostrar recordatorio si falta consentimiento

2. **Integración en flujo:**
   - Botón "Crear Consentimiento" desde ficha de paciente
   - Verificación automática antes de primera cita

3. **Notificaciones:**
   - Recordatorio si hay consentimiento pendiente de firma
   - Alerta si el consentimiento está próximo a vencer

4. **Dashboard:**
   - Widget mostrando pacientes sin consentimiento informado
   - Lista de consentimientos pendientes de firma

