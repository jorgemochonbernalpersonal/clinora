# Guía de Componentes de Formularios Reutilizables

## 📦 Componentes Disponibles

### 1. `<x-forms.layout>`
Layout principal con estructura de 2 columnas (main + sidebar).

**Props:**
- `maxWidth` (default: `max-w-7xl`)

**Slots:**
- `main`: Contenido principal (2/3 ancho)
- `sidebar`: Sidebar (1/3 ancho, sticky)

**Ejemplo:**
```blade
<x-forms.layout wire:submit="save">
    <x-slot:main>
        {{-- Contenido principal --}}
    </x-slot:main>
    
    <x-slot:sidebar>
        {{-- Sidebar --}}
    </x-slot:sidebar>
</x-forms.layout>
```

---

### 2. `<x-forms.header>`
Header sticky con título, descripción y botones de acción.

**Props:**
- `title` (required): Título del formulario
- `description`: Descripción opcional
- `cancelRoute`: Ruta para botón cancelar
- `cancelLabel`: Texto del botón cancelar (default: "Cancelar")
- `submitLabel`: Texto del botón submit (default: "Crear" o "Guardar Cambios")
- `isEditing`: Si está en modo edición (default: false)
- `submitIcon`: Icono del botón ('check' o 'save', default: 'check')
- `loadingTarget`: Target para wire:loading (default: 'save')

**Ejemplo:**
```blade
<x-forms.header 
    :title="$isEditing ? 'Editar Paciente' : 'Nuevo Paciente'"
    description="Complete la ficha clínica."
    :cancel-route="route('patients.index')"
    :is-editing="$isEditing"
/>
```

---

### 3. `<x-forms.section>`
Sección colapsable con Alpine.js.

**Props:**
- `section`: ID único de la sección (para Alpine.js)
- `title`: Título de la sección
- `icon`: Icono ('user', 'contact', 'clinical', 'social')
- `iconSvg`: SVG personalizado (opcional)
- `open`: Si está abierta por defecto (default: false)
- `highlighted`: Si está destacada (default: false)

**Ejemplo:**
```blade
<x-forms.section section="basic" title="Datos Básicos" icon="user" :open="true">
    {{-- Contenido de la sección --}}
</x-forms.section>
```

---

### 4. `<x-forms.field>`
Wrapper para campos con label y manejo de errores.

**Props:**
- `name`: Nombre del campo (para errores automáticos)
- `label`: Label del campo
- `required`: Si es requerido (muestra asterisco)
- `help`: Texto de ayuda
- `error`: Mensaje de error personalizado

**Ejemplo:**
```blade
<x-forms.field name="first_name" label="Nombre" required>
    <x-forms.input name="first_name" placeholder="Nombre del paciente" />
</x-forms.field>
```

---

### 5. `<x-forms.input>`
Input estilizado con soporte para errores.

**Props:**
- `type`: Tipo de input (default: 'text')
- `name`: Nombre del campo (para wire:model)
- `placeholder`: Placeholder
- `value`: Valor inicial
- `required`: Si es requerido
- `error`: Si tiene error (se detecta automáticamente si se proporciona name)
- `size`: Tamaño ('sm', 'base', 'lg', default: 'base')

**Ejemplo:**
```blade
<x-forms.input 
    name="email" 
    type="email" 
    placeholder="email@ejemplo.com" 
/>
```

---

### 6. `<x-forms.select>`
Select estilizado con opciones.

**Props:**
- `name`: Nombre del campo
- `options`: Array de opciones ['value' => 'label']
- `placeholder`: Placeholder (default: 'Seleccionar...')
- `value`: Valor inicial
- `required`: Si es requerido
- `error`: Si tiene error
- `size`: Tamaño

**Ejemplo:**
```blade
<x-forms.select 
    name="gender" 
    :options="[
        'male' => 'Masculino',
        'female' => 'Femenino'
    ]" 
/>
```

---

### 7. `<x-forms.textarea>`
Textarea estilizado.

**Props:**
- `name`: Nombre del campo
- `rows`: Número de filas (default: 3)
- `placeholder`: Placeholder
- `required`: Si es requerido
- `error`: Si tiene error
- `size`: Tamaño

**Ejemplo:**
```blade
<x-forms.textarea 
    name="notes" 
    :rows="4" 
    placeholder="Notas..." 
/>
```

---

### 8. `<x-forms.checkbox>`
Checkbox con label y ayuda.

**Props:**
- `name`: Nombre del campo
- `label`: Label del checkbox
- `value`: Valor del checkbox
- `checked`: Si está marcado
- `help`: Texto de ayuda

**Ejemplo:**
```blade
<x-forms.checkbox 
    name="data_protection_consent" 
    label="Consentimiento RGPD *"
>
    Autorizo el tratamiento de mis datos...
</x-forms.checkbox>
```

---

### 9. `<x-forms.info-box>`
Caja informativa para sidebar.

**Props:**
- `type`: Tipo ('info', 'warning', 'success', 'error', default: 'info')
- `title`: Título opcional
- `sticky`: Si es sticky (default: false)

**Ejemplo:**
```blade
<x-forms.info-box type="info" title="ℹ️ Información">
    Texto informativo...
</x-forms.info-box>
```

---

### 10. `<x-forms.flash-messages>`
Muestra mensajes flash (success, error, warning, info).

**Ejemplo:**
```blade
<x-forms.flash-messages />
```

---

## 📝 Ejemplo Completo

```blade
<x-forms.layout wire:submit="save">
    {{-- Header --}}
    <x-forms.header 
        :title="$isEditing ? 'Editar' : 'Crear'"
        description="Descripción del formulario"
        :cancel-route="route('index')"
        :is-editing="$isEditing"
    />

    {{-- Flash Messages --}}
    <x-forms.flash-messages />

    <x-slot:main>
        {{-- Sección 1 --}}
        <x-forms.section section="basic" title="Datos Básicos" icon="user" :open="true">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-forms.field name="name" label="Nombre" required>
                    <x-forms.input name="name" placeholder="Nombre completo" />
                </x-forms.field>
                
                <x-forms.field name="email" label="Email" required>
                    <x-forms.input name="email" type="email" placeholder="email@ejemplo.com" />
                </x-forms.field>
            </div>
        </x-forms.section>

        {{-- Sección 2 --}}
        <x-forms.section section="details" title="Detalles" icon="clinical">
            <x-forms.field name="description" label="Descripción">
                <x-forms.textarea name="description" :rows="5" placeholder="Descripción..." />
            </x-forms.field>
        </x-forms.section>
    </x-slot:main>

    <x-slot:sidebar>
        <x-forms.info-box type="info" title="ℹ️ Información" sticky>
            Información útil para el usuario.
        </x-forms.info-box>
        
        <x-forms.info-box type="warning" title="⚠️ Advertencia">
            Requisitos importantes.
        </x-forms.info-box>
    </x-slot:sidebar>
</x-forms.layout>
```

---

## 🎨 Características

### Validación Automática
Los componentes detectan automáticamente errores de Livewire si se proporciona el `name`:

```blade
<x-forms.field name="email" label="Email">
    <x-forms.input name="email" />
    {{-- El error se muestra automáticamente si existe --}}
</x-forms.field>
```

### Estilos Consistentes
- Todos los inputs tienen el mismo estilo
- Estados de error consistentes
- Focus states uniformes
- Responsive por defecto

### Iconos Predefinidos
- `user`: Icono de usuario
- `contact`: Icono de contacto/email
- `clinical`: Icono de documento clínico
- `social`: Icono de grupo/social

### Secciones Colapsables
- Animaciones suaves con Alpine.js
- Estado persistente (puede usar Alpine persist si es necesario)
- Soporte para destacar secciones importantes

---

## 🔄 Migración

Para migrar un formulario existente:

1. **Reemplazar estructura base:**
   ```blade
   <!-- Antes -->
   <div class="max-w-7xl mx-auto...">
       <form wire:submit="save">
   
   <!-- Después -->
   <x-forms.layout wire:submit="save">
   ```

2. **Reemplazar header:**
   ```blade
   <!-- Antes -->
   <div class="sticky top-0...">
       <h1>...</h1>
       <button>...</button>
   </div>
   
   <!-- Después -->
   <x-forms.header ... />
   ```

3. **Reemplazar secciones:**
   ```blade
   <!-- Antes -->
   <div class="bg-white rounded-xl...">
       <button @click="toggleSection('basic')">...</button>
       <div x-show="openSections.basic">...</div>
   </div>
   
   <!-- Después -->
   <x-forms.section section="basic" title="..." icon="user">
       ...
   </x-forms.section>
   ```

4. **Reemplazar campos:**
   ```blade
   <!-- Antes -->
   <div>
       <label>Nombre *</label>
       <input wire:model="first_name" class="...">
       @error('first_name') ... @enderror
   </div>
   
   <!-- Después -->
   <x-forms.field name="first_name" label="Nombre" required>
       <x-forms.input name="first_name" />
   </x-forms.field>
   ```

---

## ✅ Beneficios

1. **Código más limpio:** Reducción de ~70% de código repetitivo
2. **Consistencia:** Mismo estilo en todos los formularios
3. **Mantenibilidad:** Cambios centralizados
4. **Productividad:** Desarrollo más rápido
5. **Accesibilidad:** Estructura semántica consistente

