# Análisis de Base de Datos - Clinora

## 📊 Resumen Ejecutivo

**Estado General:** ✅ **Bien estructurada con algunas mejoras necesarias**

La base de datos está bien diseñada en general, pero hay algunas inconsistencias, campos duplicados y relaciones faltantes que deberían corregirse.

---

## ✅ **LO QUE ESTÁ BIEN**

### 1. **Tabla `users`** ✅
- ✅ Estructura clara con separación de responsabilidades
- ✅ Campos de seguridad bien implementados (2FA, password tracking)
- ✅ Soft deletes para auditoría
- ✅ Índices apropiados
- ✅ Campos de preferencias bien organizados

### 2. **Tabla `professionals`** ✅
- ✅ Relación 1:1 con users bien definida
- ✅ Campos de suscripción bien estructurados
- ✅ Información de contacto completa
- ✅ Índices apropiados

### 3. **Tabla `contacts`** ✅
- ✅ Relación con professional bien definida
- ✅ Campos de emergencia útiles
- ✅ Soft deletes implementado
- ✅ Auditoría con created_by/updated_by

### 4. **Tabla `appointments`** ✅
- ✅ Relaciones bien definidas
- ✅ Campos de facturación incluidos
- ✅ Estados y tipos bien estructurados
- ✅ Índices compuestos apropiados

### 5. **Tabla `clinical_notes`** ✅
- ✅ Formato SOAP bien implementado
- ✅ Evaluación de riesgo incluida
- ✅ Sistema de firma digital
- ✅ Relación con appointment opcional

---

## ⚠️ **PROBLEMAS IDENTIFICADOS**

### 🔴 **CRÍTICOS**

#### 1. **Tabla `patient_users` - Foreign Keys Faltantes**
```php
// ❌ PROBLEMA: Foreign keys comentadas
$table->unsignedBigInteger('contact_id');
$table->unsignedBigInteger('professional_id');
// $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
// $table->foreign('professional_id')->references('id')->on('professionals')->onDelete('cascade');
```

**Impacto:** 
- No hay integridad referencial
- Posibles datos huérfanos
- No se eliminan automáticamente registros relacionados

**Solución:** Agregar las foreign keys en una migración nueva.

---

#### 2. **Duplicación de Campos entre `users` y `professionals`**

**Campos duplicados:**
- `phone` → En `users` Y `professionals`
- `language` → En `users` Y `professionals`
- `timezone` → En `users` Y `professionals`

**Problema:** 
- Datos pueden estar desincronizados
- No está claro cuál es la fuente de verdad
- Confusión sobre qué campo usar

**Solución:** 
- Mantener en `users` para preferencias personales
- Mantener en `professionals` solo si es específico del perfil profesional
- O eliminar duplicados y usar solo uno

---

#### 3. **Campo `name` en `professionals` vs `first_name`/`last_name` en `users`**

**Inconsistencia:**
- `users` tiene `first_name` y `last_name`
- `professionals` tiene `name` (string completo)

**Problema:**
- Dificulta búsquedas y ordenamiento
- No hay consistencia en el modelo de datos

**Solución:** 
- Cambiar `professionals.name` a `first_name` y `last_name`
- O mantener `name` pero agregar computed attribute desde `users`

---

### 🟡 **MEDIANOS**

#### 4. **Campo `duration` en `appointments` como Virtual Column**

```php
$table->integer('duration')->virtualAs('TIMESTAMPDIFF(MINUTE, start_time, end_time)');
```

**Problema:**
- Virtual columns no funcionan en todas las bases de datos (SQLite no soporta)
- Puede causar problemas en tests

**Solución:** 
- Calcular en el modelo o usar accessor
- O hacerlo nullable y calcular al guardar

---

#### 5. **Falta Índice en `clinical_notes.session_number`**

**Problema:**
- Hay índice compuesto `['contact_id', 'session_number']` pero no individual
- Puede ser útil para búsquedas por número de sesión

**Solución:** Agregar índice individual si es necesario.

---

#### 6. **Campo `archived_at` en `contacts` pero no en otras tablas**

**Inconsistencia:**
- `contacts` tiene `archived_at` + `is_active`
- Otras tablas solo tienen `is_active` o soft deletes

**Problema:** 
- No hay consistencia en el manejo de archivos

**Solución:** 
- Estandarizar: usar solo soft deletes O agregar `archived_at` a todas

---

### 🟢 **MENORES / MEJORAS**

#### 7. **Falta Validación de Rangos**

**Campos que necesitan validación:**
- `clinical_notes.progress_rating` → Debería tener CHECK constraint (1-10)
- `appointments.price` → Debería ser >= 0

**Solución:** Agregar validaciones en el modelo o constraints.

---

#### 8. **Falta Campo `avatar_path` en Migración Original**

**Problema:**
- `avatar_path` se agregó en migración posterior
- No está en la migración inicial de `users`

**Solución:** Ya está corregido en migración posterior, pero documentar.

---

#### 9. **Falta Índice en `appointments.end_time`**

**Problema:**
- Hay índice en `start_time` pero no en `end_time`
- Útil para búsquedas de citas que terminan en un rango

**Solución:** Agregar índice si es necesario para consultas.

---

#### 10. **Campo `metadata` JSON sin Esquema**

**Problema:**
- `metadata` es JSON flexible pero sin validación
- Puede almacenar cualquier cosa

**Solución:** 
- Documentar estructura esperada
- O crear tabla separada para metadatos estructurados

---

## 📋 **RELACIONES FALTANTES**

### 1. **Foreign Keys en `patient_users`**
```php
// FALTA:
$table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
$table->foreign('professional_id')->references('id')->on('professionals')->onDelete('cascade');
```

### 2. **Relación Inversa: `users` → `patient_users`**
- Falta relación en modelo `User` para acceder a `patient_users`

---

## 🔧 **RECOMENDACIONES**

### **Prioridad ALTA**

1. ✅ **Agregar Foreign Keys en `patient_users`**
   ```php
   Schema::table('patient_users', function (Blueprint $table) {
       $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
       $table->foreign('professional_id')->references('id')->on('professionals')->onDelete('cascade');
   });
   ```

2. ✅ **Estandarizar Campos de Nombre**
   - Decidir: ¿`professionals.name` o `first_name`/`last_name`?
   - Si mantener `name`, agregar accessor desde `users`

3. ✅ **Eliminar Duplicación de Campos**
   - Decidir qué campos mantener en `users` vs `professionals`
   - Documentar la decisión

### **Prioridad MEDIA**

4. ✅ **Estandarizar Sistema de Archivos**
   - Usar solo soft deletes O agregar `archived_at` a todas las tablas

5. ✅ **Agregar Validaciones de Rangos**
   - Constraints o validaciones en modelos

6. ✅ **Documentar Estructura de `metadata` JSON**
   - Crear documentación o schema

### **Prioridad BAJA**

7. ✅ **Optimizar Índices**
   - Revisar índices según queries reales
   - Agregar índices compuestos si es necesario

8. ✅ **Considerar Tabla de `subscription_history`**
   - Para tracking de cambios de planes

---

## 📊 **DIAGRAMA DE RELACIONES**

```
users (1) ──< (1) professionals
  │
  ├──< (N) contacts (created_by, updated_by)
  │
  ├──< (N) appointments (created_by, updated_by)
  │
  └──< (1) patient_users

professionals (1) ──< (N) contacts
professionals (1) ──< (N) appointments
professionals (1) ──< (N) clinical_notes
professionals (1) ──< (N) patient_users

contacts (1) ──< (N) appointments
contacts (1) ──< (N) clinical_notes
contacts (1) ──< (1) patient_users

appointments (1) ──< (0..1) clinical_notes
```

---

## ✅ **CONCLUSIÓN**

La base de datos está **bien estructurada** en general, con:
- ✅ Relaciones claras
- ✅ Campos apropiados
- ✅ Índices bien pensados
- ✅ Soft deletes implementados
- ✅ Auditoría con created_by/updated_by

**Problemas principales a resolver:**
1. Foreign keys faltantes en `patient_users`
2. Duplicación de campos entre `users` y `professionals`
3. Inconsistencia en nombres (name vs first_name/last_name)

**Recomendación:** Resolver los problemas de prioridad ALTA antes de producción.

