# 🔐 Clinora Authentication API

## Endpoints

### Base URL
```
http://localhost:8000/api/v1
```

---

## 📝 Public Endpoints

### 1. Register (Crear cuenta profesional)

**POST** `/auth/register`

**Body:**
```json
{
  "email": "doctor@ejemplo.com",
  "password": "Password123",
  "password_confirmation": "Password123",
  "first_name": "Juan",
  "last_name": "Pérez García",
  "phone": "+34 612 345 678",
  "license_number": "28123456",
  "profession": "psychology",
  "specialties": ["Terapia Cognitivo-Conductual", "Ansiedad"],
  "terms_accepted": true
}
```

**Response 201:**
```json
{
  "success": true,
  "message": "Registro exitoso. ¡Bienvenido a Clinora!",
  "data": {
    "access_token": "1|laravel_sanctum_...",
    "token_type": "Bearer",
    "expires_in": null,
    "user": {
      "id": 1,
      "email": "doctor@ejemplo.com",
      "first_name": "Juan",
      "last_name": "Pérez García",
      "full_name": "Juan Pérez García",
      "user_type": "professional",
      "is_active": true,
      "professional": {
        "id": 1,
        "name": "Juan Pérez García",
        "license_number": "28123456",
        "profession": "psychology",
        "subscription_plan": "starter",
        "subscription_status": "active"
      },
      "roles": ["professional"]
    }
  }
}
```

---

### 2. Login (Iniciar sesión)

**POST** `/auth/login`

**Body:**
```json
{
  "email": "doctor@ejemplo.com",
  "password": "Password123",
  "remember": true
}
```

**Response 200:**
```json
{
  "success": true,
  "message": "Login exitoso",
  "data": {
    "access_token": "2|laravel_sanctum_...",
    "token_type": "Bearer",
    "user": {
      "id": 1,
      "email": "doctor@ejemplo.com",
      "full_name": "Juan Pérez García",
      "professional": {...}
    }
  }
}
```

**Error 422:**
```json
{
  "success": false,
  "message": "Credenciales incorrectas",
  "errors": {
    "email": ["Las credenciales proporcionadas son incorrectas."]
  }
}
```

---

### 3. Forgot Password (Recuperar contraseña)

**POST** `/auth/forgot-password`

**Body:**
```json
{
  "email": "doctor@ejemplo.com"
}
```

**Response 200:**
```json
{
  "success": true,
  "message": "Te hemos enviado un enlace para restablecer tu contraseña"
}
```

---

### 4. Reset Password (Restablecer contraseña)

**POST** `/auth/reset-password`

**Body:**
```json
{
  "token": "a1b2c3d4e5f6...",
  "email": "doctor@ejemplo.com",
  "password": "NewPassword123",
  "password_confirmation": "NewPassword123"
}
```

**Response 200:**
```json
{
  "success": true,
  "message": "Contraseña restablecida exitosamente. Por favor, inicia sesión."
}
```

---

## 🔒 Protected Endpoints (Require Authentication)

**Headers:**
```
Authorization: Bearer {access_token}
Accept: application/json
```

---

### 5. Get Current User (Mi perfil)

**GET** `/auth/me`

**Headers:**
```
Authorization: Bearer 1|laravel_sanctum_...
```

**Response 200:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "email": "doctor@ejemplo.com",
    "first_name": "Juan",
    "last_name": "Pérez García",
    "full_name": "Juan Pérez García",
    "phone": "+34 612 345 678",
    "user_type": "professional",
    "language": "es",
    "timezone": "Europe/Madrid",
    "is_active": true,
    "two_factor_enabled": false,
    "last_login_at": "2026-01-02T20:00:00.000000Z",
    "professional": {
      "id": 1,
      "name": "Juan Pérez García",
      "license_number": "28123456",
      "profession": "psychology",
      "specialties": ["Terapia Cognitivo-Conductual", "Ansiedad"],
      "subscription_plan": "starter",
      "subscription_status": "active"
    },
    "roles": ["professional"]
  }
}
```

---

### 6. Logout (Cerrar sesión)

**POST** `/auth/logout`

**Response 200:**
```json
{
  "success": true,
  "message": "Sesión cerrada exitosamente"
}
```

---

### 7. Logout All Devices (Cerrar sesión en todos los dispositivos)

**POST** `/auth/logout-all`

**Response 200:**
```json
{
  "success": true,
  "message": "Sesión cerrada en todos los dispositivos"
}
```

---

## 🧪 Test with cURL

### Register
```bash
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "test@ejemplo.com",
    "password": "Password123",
    "password_confirmation": "Password123",
    "first_name": "Test",
    "last_name": "User",
    "license_number": "TEST123",
    "profession": "psychology",
    "terms_accepted": true
  }'
```

### Login
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "test@ejemplo.com",
    "password": "Password123"
  }'
```

### Get Me
```bash
curl -X GET http://localhost:8000/api/v1/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

---

## 📋 Validation Rules

### Register
- **email**: required, email, unique
- **password**: required, min:8, mixed case, numbers
- **first_name**: required, max:255
- **last_name**: required, max:255
- **license_number**: required, unique
- **profession**: required, must be: **psychology** (solo psicólogos al inicio)
- **terms_accepted**: required, must be true

### Login
- **email**: required, email
- **password**: required

---

## ⚙️ Configuration

### Token Expiration
By default, Sanctum tokens don't expire. Configure in `config/sanctum.php`:

```php
'expiration' => 60 * 24 * 7, // 7 days
```

### CORS
Already configured in `config/cors.php` for local development.

---

## 🔧 Next Steps

- [ ] Email verification
- [ ] 2FA implementation
- [ ] Rate limiting
- [ ] API documentation (Swagger/OpenAPI)
- [ ] OAuth providers (Google, Facebook)
