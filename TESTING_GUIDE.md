# 🧪 Guía de Testing - Sistema de Límites y Emails

## Configuración Inicial

### 1. Configurar Email Local

En tu `.env`:

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS=info@clinora.es
MAIL_FROM_NAME="Clinora"
```

Esto guardará emails en `storage/logs/laravel.log` en vez de enviarlos.

### 2. Limpiar Caché

```bash
php artisan config:clear
php artisan cache:clear
```

---

## Test 1: Dashboard con Card de Suscripción

### Pasos:
1. Inicia la aplicación:
   ```bash
   php artisan serve
   ```

2. Accede a http://localhost:8000

3. Login con tu usuario

4. **Verifica:** Deberías ver en la parte superior derecha un card que muestra:
   - Plan actual (Gratis/Pro/Equipo)
   - Badge con el nombre del plan
   - Si es Free: progreso de pacientes (X/3)
   - Si es Free: barra de progreso visual
   - Botón "Actualizar Plan"

---

## Test 2: Límite de Pacientes (Plan Free)

### Pasos:
1. Ve a "Pacientes" en el menú

2. Crea el **primer paciente**:
   - Nombre: "Paciente Test 1"
   - Completa campos obligatorios
   - Acepta consentimiento RGPD
   - Guarda

3. **Verifica en dashboard:** Card debe mostrar "1 / 3 pacientes"

4. Crea el **segundo paciente** ("Paciente Test 2")
   - **Verifica:** Card muestra "2 / 3 pacientes"
   - **Verifica logs:** Debería haber un email de "¡Tu clínica está creciendo!" (66%)
   
   ```bash
   tail -f storage/logs/laravel.log | grep "Subject:"
   ```

5. Crea el **tercer paciente** ("Paciente Test 3")
   - **Verifica:** Card muestra "3 / 3 pacientes"
   - **Verifica:** Aparece alerta "⚠️ Límite alcanzado"
   - **Verifica logs:** Email de "Has alcanzado el límite"

6. Intenta crear **cuarto paciente**:
   - **Esperado:** Modal de upgrade aparece
   - **Esperado:** Mensaje de error sobre límite
   - **No se crea** el paciente

---

## Test 3: Modal de Upgrade

### Desde el dashboard:
1. Click en botón "Actualizar Plan"

2. **Verifica que el modal muestra:**
   - ✅ Logo de Clinora
   - ✅ Mensaje contextual (límite alcanzado o feature bloqueada)
   - ✅ 3 cards de planes (Gratis, Pro, Equipo)
   - ✅ Plan actual destacado con badge "ACTUAL"
   - ✅ Badge "Más Popular" en plan Pro
   - ✅ Comparación de features
   - ✅ Botones de CTA funcionales
   - ✅ Footer con email de contacto

---

## Test 4: Emails de Límite

### Revisar en logs:

```bash
# Ver últimos emails enviados
tail -n 500 storage/logs/laravel.log | grep -A 50 "Subject:"
```

### Email 1: Warning (66%)
**Busca:** Subject: "🎉 ¡Tu clínica está creciendo! - Clinora"

**Debe contener:**
- Saludo personalizado con nombre
- "2/3 pacientes"
- Porcentaje (66%)
- Lista de beneficios Pro
- Precio €1/paciente
- Link de contacto

### Email 2: Limit Reached (100%)
**Busca:** Subject: "🚀 Has alcanzado el límite de tu plan - Clinora"

**Debe contener:**
- "3/3 pacientes"
- Tabla comparativa de features
- Ejemplos de precio
- CTA para actualizar

---

## Test 5: Resumen Semanal

### Modo Test:

```bash
php artisan send:weekly-summary --test
```

**Esperado:**
- Lista de profesionales
- Para cada uno: stats calculadas
- Muestra a quién enviaría (sin enviar realmente)

### Envío Real:

```bash
php artisan send:weekly-summary
```

**Verifica en logs:**
- Subject: "📊 Tu resumen semanal - Clinora"
- Estadísticas de la semana
- Próximas citas
- Consejo semanal

---

## Test 6: Prevención de Duplicados

### Pasos:
1. Elimina un paciente (para volver a 2/3)

2. Crea nuevamente para llegar a 3/3

3. **Verifica:** NO debería enviar email de límite alcanzado de nuevo

4. Revisa metadata del usuario:
   ```php
   $user = User::find(1);
   dd($user->metadata);
   ```
   
   Deberías ver:
   ```php
   [
       'limit_warning_email_sent' => '2026-01-04 12:00:00',
       'limit_reached_email_sent' => '2026-01-04 12:05:00'
   ]
   ```

---

## ✅ Checklist de Verificación

- [ ] Dashboard muestra card de suscripción con plan actual
- [ ] Barra de progreso actualiza al crear pacientes
- [ ] Email de warning (66%) se envía al 2do paciente
- [ ] Email de límite (100%) se envía al 3er paciente
- [ ] Modal de upgrade aparece al intentar 4to paciente
- [ ] No se permite crear 4to paciente en plan Free
- [ ] Emails NO se duplican si vuelves al mismo umbral
- [ ] Comando de resumen semanal ejecuta correctamente
- [ ] Todos los emails contienen logo de Clinora
- [ ] Links en emails son clickeables

---

## 🐛 Problemas Comunes

### Email no aparece en logs
- Verifica `MAIL_MAILER=log` en .env
- Ejecuta `php artisan config:clear`
- Verifica permisos de `storage/logs/`

### Modal no aparece
- Revisa consola del navegador (F12)
- Verifica que Alpine.js esté cargado
- Comprueba sesión flash existe

### Límite no se aplica
- Verifica que usuario tiene plan "gratis"
- Revisa que ContactObserver está registrado
- Comprueba logs de Laravel por errores

---

## 📝 Notas

Una vez que todo funcione correctamente, procederemos a:
1. Aplicar restricciones a features premium
2. Implementar onboarding
3. Crear página de gestión de suscripción

¡Avísame cuando hayas probado todo! 🚀
