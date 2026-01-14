# Authentication Module

## 📋 Descripción

Módulo de autenticación completo que maneja login, registro, verificación de email, recuperación de contraseña y autenticación de dos factores (2FA).

## 🏗️ Estructura

```
Authentication/
├── Controllers/          # HTTP Layer
│   ├── AuthController.php
│   ├── EmailVerificationController.php
│   └── PasswordResetController.php
├── Services/             # Business Logic Layer
│   ├── AuthService.php
│   └── TwoFactorService.php
├── Repositories/         # Data Access Layer
│   ├── UserRepository.php
│   └── ProfessionalRepository.php
├── DTOs/                 # Data Transfer Objects
│   ├── LoginCredentialsDTO.php
│   └── RegisterUserDTO.php
├── Requests/             # Validation Layer
│   ├── LoginRequest.php
│   ├── RegisterRequest.php
│   ├── ForgotPasswordRequest.php
│   └── ResetPasswordRequest.php
├── Resources/            # API Transformation Layer
│   ├── AuthenticationResource.php
│   └── UserResource.php
└── Notifications/        # Notification Layer
    └── VerifyEmailNotification.php
```

## 🎯 ¿Por qué DTOs en Authentication?

### Decisión Arquitectónica

El módulo de **Authentication** utiliza **DTOs (Data Transfer Objects)** mientras que otros módulos Core (Contacts, Appointments, ConsentForms) utilizan arrays directamente. Esta es una decisión arquitectónica consciente basada en las siguientes razones:

### 1. **Complejidad de Transformación de Datos**

Authentication requiere transformaciones complejas de datos:

```php
// RegisterUserDTO encapsula lógica compleja:
- Mapeo de profession string → ProfessionType enum
- Separación de datos de usuario vs profesional
- Validación de términos y condiciones
- Generación de datos por defecto (language, timezone, subscription)
```

**Comparación:**
- **Contacts**: Datos simples, arrays suficientes
- **Authentication**: Datos complejos con múltiples transformaciones → DTOs justificados

### 2. **Type Safety Crítico**

En autenticación, la seguridad es crítica:

```php
// Con DTO (type-safe)
public function login(LoginCredentialsDTO $credentials): array
{
    $user = $this->userRepository->findByEmail($credentials->email);
    // ✅ IDE autocompleta, type checking en tiempo de desarrollo
}

// Sin DTO (menos seguro)
public function login(array $credentials): array
{
    $user = $this->userRepository->findByEmail($credentials['email']);
    // ⚠️ Sin type checking, errores en runtime
}
```

### 3. **Múltiples Fuentes de Datos**

Authentication recibe datos de múltiples fuentes:
- **API**: JSON requests
- **Web**: Form submissions (Livewire)
- **Interno**: Otros servicios

Los DTOs proporcionan una capa de abstracción consistente:

```php
// Mismo DTO desde diferentes fuentes
$dto = RegisterUserDTO::fromArray($request->validated());      // API
$dto = RegisterUserDTO::fromArray($livewireData);              // Web
$dto = RegisterUserDTO::fromArray($internalServiceData);       // Interno
```

### 4. **Lógica de Negocio Encapsulada**

Los DTOs encapsulan lógica de transformación:

```php
// RegisterUserDTO tiene métodos específicos:
$dto->getUserData()           // Prepara datos para User
$dto->getProfessionalData()   // Prepara datos para Professional
$dto->getProfessionType()     // Mapea string a enum
$dto->hasAcceptedTerms()      // Valida términos
```

Esto mantiene el Service más limpio y enfocado en la lógica de negocio.

### 5. **Inmutabilidad y Seguridad**

Los DTOs son `readonly` (inmutables), lo cual es importante para datos sensibles:

```php
readonly class LoginCredentialsDTO
{
    // Una vez creado, no puede modificarse
    // Previene mutaciones accidentales de credenciales
}
```

## 📊 Comparación con Otros Módulos

| Módulo | Usa DTOs | Razón |
|--------|----------|-------|
| **Authentication** | ✅ Sí | Datos complejos, múltiples transformaciones, seguridad crítica |
| **Contacts** | ❌ No | Datos simples, arrays suficientes |
| **Appointments** | ❌ No | Datos simples, arrays suficientes |
| **ConsentForms** | ❌ No | Datos simples, arrays suficientes |

## 🎨 Cuándo Usar DTOs vs Arrays

### ✅ Usa DTOs cuando:

1. **Transformaciones complejas** de datos
2. **Múltiples fuentes** de datos (API, Web, Interno)
3. **Type safety crítico** (seguridad, validaciones complejas)
4. **Lógica de negocio** encapsulada en la transformación
5. **Inmutabilidad** es importante

### ❌ No uses DTOs cuando:

1. **Datos simples** sin transformaciones complejas
2. **Una sola fuente** de datos
3. **Arrays suficientes** para la complejidad
4. **Simplicidad** es prioridad sobre type safety

## 🔄 Flujo de Datos

```
HTTP Request
    ↓
Form Request (Validación)
    ↓
DTO::fromArray() (Transformación + Type Safety)
    ↓
Service (Lógica de Negocio)
    ↓
Repository (Acceso a Datos)
    ↓
Model (Eloquent)
    ↓
Database
```

## 📝 Ejemplo de Uso

### Login

```php
// Controller
public function login(LoginRequest $request): JsonResponse
{
    $credentials = LoginCredentialsDTO::fromArray($request->validated());
    $result = $this->authService->login($credentials);
    // ...
}

// Service
public function login(LoginCredentialsDTO $credentials): array
{
    $user = $this->userRepository->findByEmail($credentials->email);
    // Type-safe, autocompletado en IDE
    // ...
}
```

### Register

```php
// Controller
public function register(RegisterRequest $request): JsonResponse
{
    $dto = RegisterUserDTO::fromArray($request->validated());
    $result = $this->authService->register($dto);
    // ...
}

// Service
public function register(RegisterUserDTO $dto): array
{
    $userData = $dto->getUserData();
    $professionalData = $dto->getProfessionalData();
    // Lógica encapsulada en el DTO
    // ...
}
```

## 🛡️ Beneficios de los DTOs en Authentication

1. **Type Safety**: Detección de errores en tiempo de desarrollo
2. **Autocompletado**: Mejor experiencia de desarrollo en IDE
3. **Encapsulación**: Lógica de transformación centralizada
4. **Inmutabilidad**: Previene mutaciones accidentales
5. **Testabilidad**: Fácil de mockear y testear
6. **Documentación**: El DTO documenta qué datos se esperan

## ⚠️ Consideraciones

- **Complejidad adicional**: Más capas que arrays simples
- **Inconsistencia**: Diferente a otros módulos Core
- **Overhead**: Pequeño overhead de creación de objetos

**Decisión**: Los beneficios superan los costos en Authentication debido a la complejidad y criticidad de seguridad.

## 📚 Referencias

- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [PHP 8.2 Readonly Classes](https://www.php.net/manual/en/language.oop5.properties.php#language.oop5.properties.readonly-properties)
- [Data Transfer Object Pattern](https://martinfowler.com/eaaCatalog/dataTransferObject.html)

---

**Última actualización**: 2026-01-07  
**Versión**: 1.0
