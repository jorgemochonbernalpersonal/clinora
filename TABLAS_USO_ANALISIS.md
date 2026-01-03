# Análisis de Uso de Tablas - Clinora

## 📊 RESUMEN: ¿Qué tablas se usan realmente?

---

## ✅ **TABLAS QUE SÍ SE USAN**

### 1. **`users`** ✅ **USADA ACTIVAMENTE**
- ✅ Autenticación web (sessions)
- ✅ Autenticación API (Sanctum tokens)
- ✅ Relación con professionals
- ✅ Sistema de roles (Spatie Permission)
- ✅ 2FA habilitado
- **Uso:** Totalmente activa

### 2. **`professionals`** ✅ **USADA ACTIVAMENTE**
- ✅ Perfil profesional de cada usuario
- ✅ Información de suscripción
- ✅ Relación 1:1 con users
- **Uso:** Totalmente activa

### 3. **`contacts`** ✅ **USADA ACTIVAMENTE**
- ✅ Pacientes/contactos de profesionales
- ✅ Relación con appointments y clinical_notes
- **Uso:** Totalmente activa

### 4. **`appointments`** ✅ **USADA ACTIVAMENTE**
- ✅ Citas/agenda
- ✅ Relación con contacts y professionals
- **Uso:** Totalmente activa

### 5. **`clinical_notes`** ✅ **USADA ACTIVAMENTE**
- ✅ Notas clínicas SOAP
- ✅ Relación con contacts, professionals y appointments
- **Uso:** Totalmente activa

### 6. **`personal_access_tokens`** ✅ **USADA ACTIVAMENTE**
- ✅ **Laravel Sanctum** - Autenticación API
- ✅ Se crean tokens en: `AuthService::login()`, `AuthService::register()`, `EmailVerificationController`
- ✅ Se usan en todas las rutas API (`auth:sanctum`)
- **Uso:** Totalmente activa - **NECESARIA**

### 7. **`sessions`** ✅ **USADA ACTIVAMENTE**
- ✅ **Laravel Sessions** - Autenticación web
- ✅ Se usa en login/register web
- ✅ Guarda sesiones de usuarios
- **Uso:** Totalmente activa - **NECESARIA**

### 8. **`roles`** ✅ **USADA ACTIVAMENTE**
- ✅ **Spatie Permission** - Sistema de roles
- ✅ Se crea rol "professional" en registro
- ✅ Se usa en middleware: `CheckProfessionalSubscription`
- ✅ Se verifica con `$user->hasRole('professional')`
- **Uso:** Activa - **NECESARIA**

### 9. **`model_has_roles`** ✅ **USADA ACTIVAMENTE**
- ✅ **Spatie Permission** - Asigna roles a usuarios
- ✅ Se usa cuando se asigna rol: `$user->assignRole($professionalRole)`
- ✅ Se consulta con: `$user->hasRole()`, `$user->roles`
- **Uso:** Activa - **NECESARIA**

---

## ⚠️ **TABLAS QUE SE CREAN PERO NO SE USAN (AÚN)**

### 10. **`permissions`** ⚠️ **CREADA PERO NO USADA**
- ⚠️ **Spatie Permission** - Permisos individuales
- ❌ No se crean permisos en el código
- ❌ No se asignan permisos a roles
- ❌ No se verifica con `$user->can()` o `$user->hasPermissionTo()`
- **Estado:** Tabla existe pero vacía
- **Recomendación:** 
  - Si vas a usar permisos → Crear permisos y asignarlos
  - Si NO vas a usar → Puedes ignorarla (Spatie la necesita)

### 11. **`model_has_permissions`** ⚠️ **CREADA PERO NO USADA**
- ⚠️ **Spatie Permission** - Asigna permisos directamente a usuarios
- ❌ No se usa en el código
- **Estado:** Tabla existe pero vacía
- **Recomendación:** Igual que permissions

### 12. **`role_has_permissions`** ⚠️ **CREADA PERO NO USADA**
- ⚠️ **Spatie Permission** - Asigna permisos a roles
- ❌ No se asignan permisos a roles
- **Estado:** Tabla existe pero vacía
- **Recomendación:** Igual que permissions

### 13. **`patient_users`** ⚠️ **CREADA PERO NO USADA**
- ⚠️ Portal del paciente
- ❌ No se crean registros en el código
- ❌ No hay rutas que la usen
- ❌ Foreign keys comentadas (problema)
- **Estado:** Tabla existe pero vacía
- **Recomendación:** 
  - Si vas a implementar portal del paciente → Usarla
  - Si NO → Eliminarla o dejarla para futuro

### 14. **`telescope_entries` y tablas relacionadas** ⚠️ **SOLO EN DESARROLLO**
- ⚠️ **Laravel Telescope** - Debugging y monitoreo
- ✅ Se usa en desarrollo (`TELESCOPE_ENABLED=true`)
- ❌ NO se debe usar en producción
- **Estado:** Solo desarrollo
- **Recomendación:** Deshabilitar en producción

---

## 🔧 **TABLAS DEL SISTEMA (Laravel Core)**

### 15. **`password_reset_tokens`** ✅ **USADA**
- ✅ Laravel - Reset de contraseñas
- **Uso:** Automática cuando se usa `ForgotPassword`

### 16. **`cache` y `cache_locks`** ✅ **USADA**
- ✅ Laravel Cache
- **Uso:** Automática

### 17. **`jobs`, `job_batches`, `failed_jobs`** ⚠️ **SI USAS QUEUES**
- ⚠️ Laravel Queues
- **Uso:** Solo si procesas jobs en background

---

## 📋 **RESUMEN POR CATEGORÍA**

### ✅ **NECESARIAS Y ACTIVAS**
1. `users` - Core
2. `professionals` - Core
3. `contacts` - Core
4. `appointments` - Core
5. `clinical_notes` - Core
6. `personal_access_tokens` - API Auth (Sanctum)
7. `sessions` - Web Auth
8. `roles` - Sistema de roles
9. `model_has_roles` - Asignación de roles
10. `password_reset_tokens` - Reset passwords

### ⚠️ **CREADAS PERO NO USADAS (AÚN)**
1. `permissions` - Spatie (vacía)
2. `model_has_permissions` - Spatie (vacía)
3. `role_has_permissions` - Spatie (vacía)
4. `patient_users` - Portal paciente (vacía, sin foreign keys)

### 🔧 **SISTEMA / DESARROLLO**
1. `telescope_*` - Solo desarrollo
2. `cache`, `cache_locks` - Sistema Laravel
3. `jobs`, `job_batches`, `failed_jobs` - Si usas queues

---

## 🎯 **RECOMENDACIONES**

### **Inmediatas:**
1. ✅ **Agregar Foreign Keys en `patient_users`** (si la vas a usar)
2. ✅ **Decidir sobre `patient_users`**: ¿La usas o la eliminas?
3. ✅ **Deshabilitar Telescope en producción**

### **Futuras:**
1. ⚠️ **Si usas permisos**: Crear permisos y asignarlos a roles
2. ⚠️ **Si NO usas permisos**: Puedes ignorar las tablas (Spatie las necesita pero pueden estar vacías)

---

## 💡 **CONCLUSIÓN**

**Tablas esenciales que SÍ se usan:**
- ✅ `users`, `professionals`, `contacts`, `appointments`, `clinical_notes`
- ✅ `personal_access_tokens` (API)
- ✅ `sessions` (Web)
- ✅ `roles`, `model_has_roles` (Sistema de roles)

**Tablas que existen pero están vacías:**
- ⚠️ `permissions`, `model_has_permissions`, `role_has_permissions` (Spatie - pueden estar vacías)
- ⚠️ `patient_users` (Portal paciente - no implementado aún)

**Recomendación:** Todas las tablas tienen sentido, solo algunas no se usan aún porque son para funcionalidades futuras o son parte del sistema de Spatie Permission.

