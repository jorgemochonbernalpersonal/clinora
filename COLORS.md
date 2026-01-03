# 🎨 Clinora - Guía de Colores (Minimalista Premium)

Esta guía documenta la paleta de colores oficial de **Clinora** y cómo usarla en toda la aplicación.

## 📋 Paleta de Colores

### Colores Principales (Azul Oscuro Sofisticado)

Los colores primarios transmiten **elegancia, sofisticación y profesionalismo premium**.

```css
/* Uso en Tailwind */
bg-primary-500    /* Azul oscuro casi negro - Elementos principales */
bg-primary-600    /* Más oscuro - Hover, estados activos */
bg-primary-400    /* Gris azulado - Estados secundarios */
text-primary-500  /* Texto principal oscuro */
border-primary-500 /* Bordes oscuros */
```

**Valores:**
- `primary-500`: `#0F172A` - Azul oscuro casi negro (Color principal)
- `primary-600`: `#0C1220` - Más oscuro (hover, activo)
- `primary-400`: `#94A3B8` - Gris azulado (suave)

**Uso recomendado:**
- Navegación y headers
- Texto de títulos importantes
- Fondos de secciones hero
- Elementos de marca premium
- Tipografía de alto contraste

---

### Colores Secundarios (Cyan Innovación)

Los colores secundarios representan **innovación, claridad y tecnología moderna**.

```css
/* Uso en Tailwind */
bg-secondary-500  /* Cyan brillante - Botones principales */
bg-secondary-600  /* Cyan oscuro - Hover */
text-secondary-500 /* Texto cyan */
border-secondary-500 /* Bordes cyan */
```

**Valores:**
- `secondary-500`: `#06B6D4` - Cyan brillante
- `secondary-600`: `#0891B2` - Cyan oscuro
- `secondary-400`: `#22D3EE` - Cyan claro

**Uso recomendado:**
- Botones de acción principales (CTA)
- Enlaces interactivos
- Indicadores de progreso
- Iconos de acciones positivas
- Elementos interactivos modernos

---

### Colores de Acento (Ámbar Dorado Calidez)

Los colores de acento añaden **calidez, confianza y energía**.

```css
/* Uso en Tailwind */
bg-accent-500     /* Ámbar dorado - Elementos destacados */
bg-accent-600     /* Ámbar oscuro */
text-accent-500    /* Texto ámbar */
```

**Valores:**
- `accent-500`: `#F59E0B` - Ámbar dorado
- `accent-600`: `#D97706` - Ámbar oscuro
- `accent-400`: `#FBBF24` - Ámbar claro

**Uso recomendado:**
- Badges y etiquetas premium
- Destacar información importante
- Botones secundarios cálidos
- Iconos de alertas positivas
- Elementos decorativos selectos

---

### Colores Neutros

**Fondos:**
- `background`: `#F9FAFB` - Fondo principal de la aplicación
- `surface`: `#FFFFFF` - Superficies (tarjetas, modales, paneles)

**Texto:**
- `text-primary`: `#111827` - Texto principal (gris oscuro)
- `text-secondary`: `#6B7280` - Texto secundario (gris medio)
- `text-muted`: `#9CA3AF` - Texto deshabilitado/muted (gris claro)

```css
/* Uso en Tailwind */
bg-background     /* Fondo principal */
bg-surface        /* Superficies blancas */
text-text-primary /* Texto principal */
text-text-secondary /* Texto secundario */
text-text-muted   /* Texto muted */
```

---

### Colores de Estado

#### Success (Éxito)
```css
bg-success-500    /* Verde - Éxito */
bg-success-bg     /* Fondo verde claro */
text-success-600  /* Texto verde */
```

**Uso:** Confirmaciones, operaciones exitosas, estados completados

#### Warning (Advertencia)
```css
bg-warning-500    /* Ámbar - Advertencia */
bg-warning-bg     /* Fondo ámbar claro */
text-warning-600  /* Texto ámbar */
```

**Uso:** Advertencias, acciones que requieren atención

#### Error (Error)
```css
bg-error-500      /* Rojo - Error */
bg-error-bg       /* Fondo rojo claro */
text-error-600    /* Texto rojo */
```

**Uso:** Errores, validaciones fallidas, acciones peligrosas

#### Info (Información)
```css
bg-info-500       /* Azul - Información */
bg-info-bg        /* Fondo azul claro */
text-info-600     /* Texto azul */
```

**Uso:** Información, tips, mensajes informativos

---

## 🎯 Ejemplos de Uso por Componente

### Botones

```html
<!-- Botón Principal -->
<button class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg">
  Guardar
</button>

<!-- Botón Secundario -->
<button class="bg-secondary-500 hover:bg-secondary-600 text-white px-4 py-2 rounded-lg">
  Cancelar
</button>

<!-- Botón Outline -->
<button class="border-2 border-primary-500 text-primary-600 hover:bg-primary-50 px-4 py-2 rounded-lg">
  Ver más
</button>
```

### Tarjetas

```html
<div class="bg-surface border border-gray-200 rounded-lg p-6 shadow-sm">
  <h3 class="text-text-primary text-lg font-semibold">Título</h3>
  <p class="text-text-secondary mt-2">Contenido de la tarjeta</p>
</div>
```

### Badges

```html
<!-- Badge Success -->
<span class="bg-success-bg text-success-700 px-2 py-1 rounded-full text-sm">
  Confirmado
</span>

<!-- Badge Warning -->
<span class="bg-warning-bg text-warning-700 px-2 py-1 rounded-full text-sm">
  Pendiente
</span>

<!-- Badge Error -->
<span class="bg-error-bg text-error-700 px-2 py-1 rounded-full text-sm">
  Cancelado
</span>
```

### Alertas

```html
<!-- Alerta Success -->
<div class="bg-success-bg border-l-4 border-success-500 p-4 rounded">
  <p class="text-success-700">Operación completada exitosamente</p>
</div>

<!-- Alerta Error -->
<div class="bg-error-bg border-l-4 border-error-500 p-4 rounded">
  <p class="text-error-700">Ha ocurrido un error</p>
</div>
```

### Navegación

```html
<nav class="bg-primary-600 text-white">
  <div class="container mx-auto px-4 py-4">
    <a href="#" class="hover:text-primary-200">Inicio</a>
  </div>
</nav>
```

---

## 📐 Reglas de Uso

### ✅ Hacer

- Usar `primary-500` para acciones principales
- Usar `secondary-500` para acciones relacionadas con salud
- Usar `accent-500` para elementos destacados
- Mantener consistencia en toda la aplicación
- Usar colores de estado apropiados para feedback

### ❌ Evitar

- No mezclar múltiples colores primarios en un mismo componente
- No usar colores de estado para decoración
- No usar más de 3 colores principales en una vista
- No usar colores muy saturados para texto largo
- No ignorar el contraste (usar herramientas de accesibilidad)

---

## ♿ Accesibilidad

Todos los colores han sido seleccionados para cumplir con **WCAG AA**:
- Contraste mínimo de 4.5:1 para texto normal
- Contraste mínimo de 3:1 para texto grande
- Los colores de estado son distinguibles para usuarios con daltonismo

**Herramientas recomendadas:**
- [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/)
- [Coolors Contrast Checker](https://coolors.co/contrast-checker)

---

## 🎨 Variables CSS Personalizadas

También puedes usar las variables CSS directamente:

```css
.custom-button {
  background-color: var(--color-primary);
  color: white;
}

.custom-text {
  color: var(--color-text-primary);
}
```

---

## 📱 Modo Oscuro (Futuro)

La paleta está preparada para soportar modo oscuro. Los colores se ajustarán automáticamente cuando se implemente.

---

**Última actualización:** 2024  
**Versión:** 1.0

