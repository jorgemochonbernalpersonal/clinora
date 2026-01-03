# Análisis de Filtros y Roles - Clinora

## 📊 **SITUACIÓN ACTUAL**

### ✅ **Filtrado Actual: Por `professional_id`**

**Cómo funciona ahora:**
- Todos los datos se filtran por `professional_id`
- Cada profesional solo ve sus propios datos:
  - Sus pacientes (`contacts`)
  - Sus citas (`appointments`)
  - Sus notas clínicas (`clinical_notes`)

**Ejemplo en código:**
```php
// ContactController.php
$professional = $request->user()->professional;
$contacts = $this->contactService->getContactsForProfessional($professional, $filters);

// Internamente filtra por:
$filters['professional_id'] = $professional->id;
```

### ✅ **Rol Actual: Solo "professional"**

- Todos los usuarios registrados tienen rol "professional"
- No hay otros roles activos (admin, assistant, etc.)
- El middleware `CheckProfessionalSubscription` verifica el rol pero todos lo tienen

---

## 🎯 **¿HAY QUE FILTRAR POR ROL AHORA?**

### ❌ **NO, no hace falta ahora**

**Razones:**
1. ✅ Solo existe el rol "professional"
2. ✅ Todos los usuarios tienen el mismo rol
3. ✅ El filtrado por `professional_id` es suficiente
4. ✅ No hay diferencias de acceso entre roles

---

## 🔮 **¿CUÁNDO SÍ HABRÍA QUE FILTRAR POR ROL?**

### **Escenario 1: Rol "admin"**
Si agregas administradores que deben ver **TODO**:
```php
// Admin ve todos los profesionales
if ($user->hasRole('admin')) {
    // Sin filtro de professional_id
    $contacts = Contact::all();
} else {
    // Professional solo ve sus datos
    $contacts = Contact::where('professional_id', $user->professional->id)->get();
}
```

### **Escenario 2: Rol "assistant"**
Si agregas asistentes que trabajan para un profesional:
```php
// Assistant ve datos de su profesional asignado
if ($user->hasRole('assistant')) {
    $professionalId = $user->assistant->professional_id; // Relación assistant -> professional
    $contacts = Contact::where('professional_id', $professionalId)->get();
} else if ($user->hasRole('professional')) {
    $contacts = Contact::where('professional_id', $user->professional->id)->get();
}
```

### **Escenario 3: Rol "patient"**
Si los pacientes acceden al portal:
```php
// Patient solo ve sus propios datos
if ($user->hasRole('patient')) {
    $contactId = $user->patientUser->contact_id;
    $appointments = Appointment::where('contact_id', $contactId)->get();
}
```

---

## 🔧 **ESTRUCTURA ACTUAL (Correcta para ahora)**

### **Controladores:**
```php
// ContactController.php
public function index(Request $request): JsonResponse
{
    $professional = $request->user()->professional; // ✅ Obtiene professional_id
    $contacts = $this->contactService->getContactsForProfessional($professional, $filters);
    // ✅ Filtra automáticamente por professional_id
}
```

### **Servicios:**
```php
// ContactService.php
public function getContactsForProfessional(Professional $professional, array $filters = [])
{
    $filters['professional_id'] = $professional->id; // ✅ Filtro automático
    return $this->repository->findAll($filters);
}
```

### **Repositorios:**
```php
// ContactRepository.php
public function findAll(array $filters = []): Collection
{
    $query = $this->model->newQuery();
    
    if (isset($filters['professional_id'])) {
        $query->where('professional_id', $filters['professional_id']); // ✅ Filtro
    }
    
    return $query->get();
}
```

---

## 🚀 **PREPARACIÓN PARA EL FUTURO**

### **Opción 1: Helper Method en Base Controller**

Crear un método helper que maneje el filtrado según el rol:

```php
// app/Http/Controllers/Controller.php (base)
protected function getProfessionalIdForUser(User $user): ?int
{
    // Admin ve todo (retorna null = sin filtro)
    if ($user->hasRole('admin')) {
        return null;
    }
    
    // Assistant ve datos de su profesional
    if ($user->hasRole('assistant')) {
        return $user->assistant->professional_id ?? null;
    }
    
    // Professional ve sus propios datos
    if ($user->hasRole('professional')) {
        return $user->professional->id ?? null;
    }
    
    return null;
}
```

### **Opción 2: Scope Global en Modelos**

Agregar scope global que filtre automáticamente:

```php
// app/Shared/Traits/HasProfessional.php
protected static function bootHasProfessional()
{
    static::addGlobalScope('professional', function ($query) {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Admin ve todo
            if ($user->hasRole('admin')) {
                return; // Sin filtro
            }
            
            // Otros roles filtran por professional_id
            if ($user->professional) {
                $query->where('professional_id', $user->professional->id);
            }
        }
    });
}
```

### **Opción 3: Middleware de Filtrado**

Crear middleware que inyecte el filtro automáticamente:

```php
// app/Http/Middleware/FilterByProfessional.php
public function handle(Request $request, Closure $next)
{
    $user = $request->user();
    
    if ($user && !$user->hasRole('admin')) {
        // Agregar professional_id a todas las requests
        $request->merge(['professional_id' => $user->professional->id]);
    }
    
    return $next($request);
}
```

---

## ✅ **RECOMENDACIÓN**

### **Ahora (Solo "professional"):**
✅ **Mantener como está** - El filtrado por `professional_id` es correcto y suficiente

### **Futuro (Múltiples roles):**
✅ **Implementar Opción 1** (Helper Method) cuando agregues roles:
- Es más explícito
- Fácil de entender
- Fácil de mantener
- No afecta queries existentes

---

## 📋 **EJEMPLO DE IMPLEMENTACIÓN FUTURA**

```php
// ContactController.php (futuro)
public function index(Request $request): JsonResponse
{
    $user = $request->user();
    
    // Helper method que maneja roles
    $professionalId = $this->getProfessionalIdForUser($user);
    
    $filters = [
        'search' => $request->input('search'),
        'is_active' => $request->has('is_active') ? (bool) $request->input('is_active') : null,
    ];
    
    // Solo filtrar si no es admin
    if ($professionalId !== null) {
        $filters['professional_id'] = $professionalId;
    }
    
    $contacts = $this->contactService->getContacts($filters);
    
    return response()->json([
        'success' => true,
        'data' => ContactResource::collection($contacts),
    ]);
}
```

---

## 🎯 **CONCLUSIÓN**

**Ahora:**
- ✅ NO hace falta filtrar por rol
- ✅ El filtrado por `professional_id` es correcto
- ✅ La estructura actual es adecuada

**Futuro:**
- ✅ Cuando agregues roles (admin, assistant), SÍ habrá que filtrar por rol
- ✅ Preparar helper methods o scopes globales
- ✅ Mantener la estructura actual y agregar lógica de roles

**Tu código actual está bien diseñado para escalar cuando necesites múltiples roles.** 🚀

