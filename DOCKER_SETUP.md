# 🐳 Configuración Docker para Clinora

## Estructura

Este proyecto incluye un setup completo de Docker con:

- **web**: Contenedor Laravel con PHP 8.4 y Apache
- **mysql**: Base de datos MySQL 8.0
- **phpmyadmin**: Interfaz web para MySQL
- **mailhog**: Servidor SMTP de desarrollo

## 🚀 Inicio Rápido

### 1. Construir y levantar los contenedores

```bash
docker-compose up -d --build
```

### 2. Configurar el .env para Docker

Cuando Laravel corre **dentro de Docker**, actualiza tu `.env`:

```env
# Base de datos - Usar nombre del servicio
DB_HOST=mysql
DB_PORT=3306

# Mail - Usar nombre del servicio
MAIL_HOST=mailhog
MAIL_PORT=1025
```

### 3. Instalar dependencias dentro del contenedor

```bash
docker exec -it clinora_web composer install
docker exec -it clinora_web npm install
```

### 4. Generar APP_KEY y ejecutar migraciones

```bash
docker exec -it clinora_web php artisan key:generate
docker exec -it clinora_web php artisan migrate
```

### 5. Acceder a la aplicación

- **Laravel**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8082
- **MailHog UI**: http://localhost:8026

## 📝 Comandos Útiles

### Ver logs
```bash
docker-compose logs -f web
docker-compose logs -f mysql
```

### Ejecutar comandos Artisan
```bash
docker exec -it clinora_web php artisan [comando]
```

### Acceder al contenedor
```bash
docker exec -it clinora_web bash
```

### Detener contenedores
```bash
docker-compose down
```

### Detener y eliminar volúmenes (⚠️ elimina la base de datos)
```bash
docker-compose down -v
```

## 🔧 Configuración

### Puertos

- **Laravel**: 8000
- **MySQL**: 3307 (exterior) → 3306 (interior)
- **phpMyAdmin**: 8082
- **MailHog SMTP**: 1026
- **MailHog Web**: 8026

### Credenciales MySQL

- **Usuario**: clinora
- **Contraseña**: password
- **Root**: root
- **Base de datos**: clinora

### Credenciales phpMyAdmin

- **Usuario**: root
- **Contraseña**: root

## ⚠️ Notas Importantes

1. Si ejecutas Laravel **fuera de Docker** (en tu máquina local), usa:
   - `DB_HOST=127.0.0.1`
   - `DB_PORT=3307`
   - `MAIL_HOST=127.0.0.1`
   - `MAIL_PORT=1026`

2. Si ejecutas Laravel **dentro de Docker**, usa:
   - `DB_HOST=mysql` (nombre del servicio)
   - `DB_PORT=3306`
   - `MAIL_HOST=mailhog` (nombre del servicio)
   - `MAIL_PORT=1025`

3. Los volúmenes de Docker persisten los datos de MySQL incluso si detienes los contenedores.

