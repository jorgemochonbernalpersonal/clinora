# Análisis de Cumplimiento Normativo - Clinora

## 📋 Resumen Ejecutivo

Este documento analiza si el plan estratégico de Clinora es **óptimo** y cumple con la **normativa específica para psicólogos en España**. 

**Conclusión Principal**: El plan es sólido en producto y marketing, pero **insuficiente en cumplimiento normativo**. Se requiere una **Fase 0 de Compliance Legal** antes del lanzamiento.

---

## ⚖️ Normativas Aplicables

### 1. **Protección de Datos**
- ✅ **RGPD** (Reglamento UE 2016/679)
- ✅ **LOPDGDD** (Ley Orgánica 3/2018)
- ⚠️ **Datos de salud**: Categoría especial (art. 9 RGPD)

### 2. **Deontología Profesional**
- ✅ **Código Deontológico del Colegio Oficial de Psicólogos (COP)**
- ✅ **Secreto profesional** (art. 44-47 Código Deontológico)
- ✅ **Consentimiento informado** (obligatorio antes de tratamiento)
- ✅ **Límites de confidencialidad** (riesgo, orden judicial, menores)

### 3. **Normativa Sanitaria**
- ✅ **Ley 44/2003** de Ordenación de las Profesiones Sanitarias
- ✅ **Conservación de historiales**: Mínimo **5 años** (art. 17 LGS)
- ✅ **Registro de actividad profesional**

### 4. **Telemedicina** (si aplica)
- ✅ **Real Decreto 1302/2018** (si aplica)
- ✅ **Consentimiento específico** para teleconsulta

---

## 🔍 Estado Actual del Código

### ✅ **Ya Implementado**

#### 1. **GDPR/LOPD Básico**
- ✅ Consentimiento de protección de datos (`data_protection_consent`)
- ✅ Timestamp de consentimiento (`data_protection_consent_at`)
- ✅ Páginas legales (GDPR, Privacy, Terms)
- ✅ Mención de DPO en documentación

#### 2. **Notas Clínicas**
- ✅ Formato SOAP implementado
- ✅ Sistema de firma (`is_signed`, `signed_at`)
- ✅ Evaluación de riesgo
- ✅ Soft deletes (auditoría)

#### 3. **Auditoría Básica**
- ✅ `created_by` / `updated_by` en modelos
- ✅ Logging de acciones críticas
- ✅ `last_login_at` / `last_login_ip`

### ❌ **Faltante (CRÍTICO)**

#### 1. **Consentimiento Informado**
- ❌ No existe tabla `consent_forms`
- ❌ No hay gestión de consentimientos por tipo
- ❌ No hay firma electrónica de consentimientos
- ❌ No hay gestión de menores/tutores

#### 2. **Derechos ARCO/GDPR**
- ❌ No hay endpoints para ejercer derechos
- ❌ No hay exportación de datos
- ❌ No hay supresión/anonymización
- ❌ No hay portabilidad de datos

#### 3. **Conservación de Historiales**
- ❌ No hay política de retención (5 años mínimo)
- ❌ No hay proceso de archivado
- ❌ No hay gestión de eliminación segura

#### 4. **Teleconsulta**
- ❌ No hay consentimiento específico para videollamadas
- ❌ No hay registro de consentimiento de grabación

#### 5. **Secreto Profesional**
- ❌ No hay control de accesos granulares
- ❌ No hay registro de accesos a historiales
- ❌ No hay alertas de accesos no autorizados

---

## 📊 Análisis del Plan Estratégico

### ✅ **Lo que Cubre Bien**

1. ✅ Menciona GDPR y LOPD en marketing
2. ✅ Incluye consentimientos informados (aunque en Fase 2)
3. ✅ Menciona exportación de datos (GDPR compliance)

### ❌ **Lo que Falta o es Insuficiente**

#### 1. **Consentimiento Informado**
- ⚠️ **Problema**: Está en Fase 2, debería estar en Fase 1
- ⚠️ **Riesgo**: Es **obligatorio** antes de iniciar tratamiento
- ⚠️ **Impacto**: Riesgo legal alto si no está implementado

#### 2. **Derechos ARCO**
- ❌ **Problema**: No está en el plan
- ⚠️ **Riesgo**: Es **obligatorio** por GDPR
- ⚠️ **Impacto**: Debe implementarse antes del lanzamiento

#### 3. **Conservación de Historiales**
- ❌ **Problema**: No se menciona
- ⚠️ **Riesgo**: Es **obligatorio** (mínimo 5 años)
- ⚠️ **Impacto**: Debe estar desde el inicio

#### 4. **Código Deontológico**
- ⚠️ **Problema**: No se menciona explícitamente
- ⚠️ **Riesgo**: Los psicólogos deben cumplirlo
- ⚠️ **Impacto**: Debería incluirse en documentación

#### 5. **Teleconsulta**
- ⚠️ **Problema**: Consentimiento específico no está en Fase 1
- ⚠️ **Riesgo**: Necesario antes de videollamadas
- ⚠️ **Impacto**: Debe estar antes de Fase 2

---

## 🚨 Recomendaciones Prioritarias

### 🔴 **CRÍTICO (Antes del Lanzamiento)**

#### 1. **Sistema de Consentimiento Informado**
```
PRIORIDAD: 🔴 CRÍTICA
TIMELINE: Inmediato (antes de Fase 1)

Requisitos mínimos:
- Tabla consent_forms con tipos de consentimiento
- Firma electrónica (base64 o servicio externo)
- Plantillas según tipo (tratamiento, teleconsulta, menores)
- Revocación de consentimientos
- Control de versiones de documentos
```

#### 2. **Derechos ARCO/GDPR**
```
PRIORIDAD: 🔴 CRÍTICA
TIMELINE: Inmediato

Endpoints necesarios:
- GET /api/v1/gdpr/export (portabilidad)
- DELETE /api/v1/gdpr/delete (supresión)
- PUT /api/v1/gdpr/rectify (rectificación)
- GET /api/v1/gdpr/access (acceso)
```

#### 3. **Política de Conservación**
```
PRIORIDAD: 🔴 CRÍTICA
TIMELINE: Inmediato

Implementar:
- Archivado automático después de 5 años
- Eliminación segura después de período legal
- Notificaciones antes de eliminación
```

### 🟠 **ALTA PRIORIDAD (Fase 1 Revisada)**

#### 4. **Control de Accesos**
```
PRIORIDAD: 🟠 ALTA
TIMELINE: Fase 1

Implementar:
- Audit log de accesos a historiales
- Alertas de accesos no autorizados
- Permisos granulares por tipo de dato
```

#### 5. **Consentimiento para Teleconsulta**
```
PRIORIDAD: 🟠 ALTA
TIMELINE: Antes de videollamadas (Fase 2)

Requisitos:
- Consentimiento específico antes de primera videollamada
- Información sobre plataforma y seguridad
- Consentimiento para grabación (si aplica)
```

---

## 📅 Plan Estratégico Revisado

### **FASE 0: Compliance Legal** (NUEVA - Antes de Fase 1)

> **Objetivo**: Cumplimiento normativo mínimo antes del lanzamiento

#### Features Críticas:
1. ✅ Sistema de consentimiento informado completo
2. ✅ Endpoints de derechos ARCO/GDPR
3. ✅ Política de conservación de historiales
4. ✅ Audit log completo de accesos
5. ✅ Documentación de cumplimiento normativo

**Timeline**: 4-6 semanas

---

### **FASE 1: MVP + Compliance** (Revisada)

> **Objetivo**: MVP funcional con cumplimiento normativo completo

#### Features:
1. Portal del paciente (con consentimientos)
2. Reservas online (con consentimiento de datos)
3. Facturación mejorada
4. Control de accesos granular
5. Exportación de datos para pacientes

**Timeline**: +4 meses (extendido desde +2 meses)

---

## ✅ Checklist de Cumplimiento Normativo

### **GDPR/LOPD**
- [x] Consentimiento de protección de datos
- [x] Páginas legales (Privacy, GDPR, Terms)
- [ ] Endpoints de derechos ARCO
- [ ] Exportación de datos (portabilidad)
- [ ] Supresión/anonymización
- [ ] DPO designado (mencionado, verificar)

### **Código Deontológico COP**
- [ ] Consentimiento informado antes de tratamiento
- [ ] Gestión de límites de confidencialidad
- [ ] Registro de excepciones al secreto profesional
- [ ] Documentación de derivaciones

### **Normativa Sanitaria**
- [ ] Conservación mínima de 5 años
- [ ] Política de archivado
- [ ] Eliminación segura después de período legal
- [ ] Registro de actividad profesional

### **Telemedicina**
- [ ] Consentimiento específico para teleconsulta
- [ ] Información sobre plataforma y seguridad
- [ ] Consentimiento para grabación (si aplica)

---

## 🎯 Implementación Técnica Sugerida

### 1. **Tabla de Consentimientos Informados**

```sql
CREATE TABLE consent_forms (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    professional_id BIGINT UNSIGNED NOT NULL,
    contact_id BIGINT UNSIGNED NOT NULL,
    
    -- Tipo de consentimiento
    consent_type ENUM(
        'initial_treatment',      -- Consentimiento inicial
        'teleconsultation',       -- Para sesiones online
        'minors',                 -- Consentimiento parental
        'recording',              -- Grabación de sesiones
        'research',               -- Participación en investigación
        'third_party_communication', -- Comunicación con otros profesionales
        'medication_referral'     -- Derivación a psiquiatría
    ) NOT NULL,
    
    -- Contenido del consentimiento
    consent_title VARCHAR(255),
    consent_text LONGTEXT NOT NULL,
    
    -- Información específica para menores
    legal_guardian_name VARCHAR(255),
    legal_guardian_relationship VARCHAR(100),
    legal_guardian_id_document VARCHAR(50),
    
    minor_assent BOOLEAN DEFAULT FALSE,
    minor_assent_details TEXT,
    
    -- Firma electrónica
    patient_signature_data TEXT,  -- Base64 de firma
    patient_ip_address VARCHAR(45),
    patient_device_info TEXT,
    
    guardian_signature_data TEXT,  -- Para menores
    
    -- Validación
    signed_at TIMESTAMP NULL,
    is_valid BOOLEAN DEFAULT FALSE,
    
    -- Revocación
    revoked_at TIMESTAMP NULL,
    revocation_reason TEXT,
    
    -- Versión del documento
    document_version VARCHAR(20),
    
    -- Auditoría
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (professional_id) REFERENCES professionals(id),
    FOREIGN KEY (contact_id) REFERENCES contacts(id)
);
```

### 2. **Endpoints GDPR**

```php
// routes/api/gdpr.php
Route::prefix('gdpr')->middleware(['auth:sanctum'])->group(function () {
    // Acceso a datos
    Route::get('/access', [GDPRController::class, 'access']);
    
    // Rectificación
    Route::put('/rectify', [GDPRController::class, 'rectify']);
    
    // Portabilidad
    Route::get('/export', [GDPRController::class, 'export']);
    
    // Supresión
    Route::delete('/delete', [GDPRController::class, 'delete']);
    
    // Oposición
    Route::post('/oppose', [GDPRController::class, 'oppose']);
});
```

### 3. **Política de Conservación**

```php
// app/Console/Commands/ArchiveOldRecords.php
class ArchiveOldRecords extends Command
{
    public function handle()
    {
        // Archivar registros de más de 5 años
        $cutoffDate = now()->subYears(5);
        
        ClinicalNote::where('session_date', '<', $cutoffDate)
            ->where('archived', false)
            ->update(['archived' => true, 'archived_at' => now()]);
            
        // Notificar antes de eliminación (7 años)
        $deletionDate = now()->subYears(7);
        // ... lógica de notificación
    }
}
```

---

## 📚 Referencias Legales

### **Normativas Principales**
- [RGPD - Reglamento UE 2016/679](https://gdpr-info.eu/)
- [LOPDGDD - Ley Orgánica 3/2018](https://www.boe.es/buscar/act.php?id=BOE-A-2018-16673)
- [Código Deontológico COP](https://www.cop.es/index.php?page=CodigoDeontologico)
- [Ley 44/2003 - Ordenación Profesiones Sanitarias](https://www.boe.es/buscar/act.php?id=BOE-A-2003-21340)

### **Guías de Implementación**
- [AEPD - Guía para el Responsable del Tratamiento](https://www.aepd.es/es/guias/guia-responsable.pdf)
- [AEPD - Guía de Consentimiento](https://www.aepd.es/es/guias/guia-consentimiento.pdf)
- [COP - Guía de Buenas Prácticas en Telepsicología](https://www.cop.es/)

---

## 🏁 Conclusión

### **Puntos Críticos**

1. **Consentimiento Informado**: Debe estar **antes del lanzamiento**, no en Fase 2
2. **Derechos ARCO**: No están en el plan y son **obligatorios**
3. **Conservación de Historiales**: No se menciona y es **obligatorio**

### **Recomendación Final**

**Agregar una Fase 0 de Compliance Legal** antes de Fase 1, o integrar estos requisitos en Fase 1 como **prioridad máxima**.

Sin esto, hay **riesgo legal alto** y posibles **sanciones de la AEPD** (hasta 20M€ o 4% facturación anual).

---

**Última actualización**: 2026-01-05  
**Próxima revisión**: 2026-02-01  
**Responsable**: Equipo Legal + Desarrollo

