# Ejemplos de API REST - FASE 1

## Catálogo: OPERADORES

### Campos FASE 1
- `id` (auto)
- `empresa_id` (auto desde auth)
- `numero_operador` (requerido, único por empresa)
- `activo` (boolean, default: true)
- `fecha_contratacion` (date, nullable)
- `apellido_paterno` (requerido)
- `apellido_materno` (nullable)
- `nombres` (requerido)
- `telefono` (nullable)
- `correo` (nullable, email)
- `licencia` (nullable)
- `vencimiento_licencia` (nullable, date)

---

### GET /operadores
**Listar operadores**

**Query Parameters:**
- `search` (opcional): Búsqueda en número, nombres, apellidos, teléfono, correo, licencia
- `activo` (opcional): 1 o 0 para filtrar por estado
- `page` (opcional): Número de página

**Ejemplo de respuesta:**
```json
{
  "operadores": {
    "data": [
      {
        "id": 1,
        "empresa_id": 1,
        "numero_operador": "OP-001",
        "activo": true,
        "fecha_contratacion": "2024-01-15",
        "apellido_paterno": "Pérez",
        "apellido_materno": "López",
        "nombres": "Juan",
        "telefono": "5551234567",
        "correo": "juan.perez@email.com",
        "licencia": "A1234567",
        "vencimiento_licencia": "2026-01-15",
        "created_at": "2024-01-15T10:00:00.000000Z",
        "updated_at": "2024-01-15T10:00:00.000000Z"
      }
    ],
    "current_page": 1,
    "total": 1
  },
  "filters": {
    "search": null,
    "activo": null
  }
}
```

---

### POST /operadores
**Crear operador**

**Body (JSON):**
```json
{
  "numero_operador": "OP-001",
  "activo": true,
  "fecha_contratacion": "2024-01-15",
  "apellido_paterno": "Pérez",
  "apellido_materno": "López",
  "nombres": "Juan",
  "telefono": "5551234567",
  "correo": "juan.perez@email.com",
  "licencia": "A1234567",
  "vencimiento_licencia": "2026-01-15"
}
```

**Validaciones:**
- `numero_operador`: requerido, string, máximo 255 caracteres, único por empresa
- `apellido_paterno`: requerido, string, máximo 255 caracteres
- `nombres`: requerido, string, máximo 255 caracteres
- `correo`: nullable, debe ser email válido
- `vencimiento_licencia`: nullable, debe ser fecha posterior o igual a fecha_contratacion

**Respuesta exitosa (302 redirect):**
Redirige a `/operadores` con mensaje de éxito.

**Errores (422):**
```json
{
  "errors": {
    "numero_operador": ["El número de operador ya existe."],
    "apellido_paterno": ["El campo apellido paterno es obligatorio."],
    "nombres": ["El campo nombres es obligatorio."],
    "correo": ["El correo debe ser una dirección de correo válida."]
  }
}
```

---

### GET /operadores/{id}/edit
**Obtener operador para editar**

**Respuesta:**
```json
{
  "operador": {
    "id": 1,
    "empresa_id": 1,
    "numero_operador": "OP-001",
    "activo": true,
    "fecha_contratacion": "2024-01-15",
    "apellido_paterno": "Pérez",
    "apellido_materno": "López",
    "nombres": "Juan",
    "telefono": "5551234567",
    "correo": "juan.perez@email.com",
    "licencia": "A1234567",
    "vencimiento_licencia": "2026-01-15",
    "created_at": "2024-01-15T10:00:00.000000Z",
    "updated_at": "2024-01-15T10:00:00.000000Z"
  }
}
```

---

### PUT /operadores/{id}
**Actualizar operador**

**Body (JSON):**
```json
{
  "numero_operador": "OP-001",
  "activo": true,
  "fecha_contratacion": "2024-01-15",
  "apellido_paterno": "Pérez",
  "apellido_materno": "López",
  "nombres": "Juan Carlos",
  "telefono": "5551234567",
  "correo": "juan.perez@email.com",
  "licencia": "A1234567",
  "vencimiento_licencia": "2026-01-15"
}
```

**Validaciones:** Iguales que POST, pero `numero_operador` debe ser único excluyendo el registro actual.

---

### DELETE /operadores/{id}
**Eliminar operador**

**Validaciones:**
- No se puede eliminar si tiene órdenes de carga asociadas

**Respuesta exitosa (302 redirect):**
Redirige a `/operadores` con mensaje de éxito.

**Error (back con mensaje):**
Si tiene órdenes asociadas, retorna error: "No se puede eliminar el operador porque tiene órdenes de carga asociadas"

---

## Catálogo: CLIENTES

### Campos FASE 1
- `id` (auto)
- `empresa_id` (auto desde auth)
- `numero_cliente` (requerido, único por empresa)
- `nombre_fiscal` (requerido)
- `nombre_comercial` (nullable)
- `rfc` (nullable, máximo 13 caracteres)
- `estatus` (enum: 'activo' | 'inactivo', default: 'activo')
- `fecha_alta` (nullable, date)
- `direccion` (nullable, text)
- `telefono` (nullable)
- `correo` (nullable, email)

---

### GET /clientes
**Listar clientes**

**Query Parameters:**
- `search` (opcional): Búsqueda en número, nombre fiscal, nombre comercial, RFC, teléfono, correo
- `estatus` (opcional): 'activo' o 'inactivo'
- `page` (opcional): Número de página

**Ejemplo de respuesta:**
```json
{
  "clientes": {
    "data": [
      {
        "id": 1,
        "empresa_id": 1,
        "numero_cliente": "CLI-001",
        "nombre_fiscal": "Coca-Cola FEMSA S.A. de C.V.",
        "nombre_comercial": "Coca-Cola",
        "rfc": "CCF990101XXX",
        "estatus": "activo",
        "fecha_alta": "2024-01-10",
        "direccion": "Av. Insurgentes Sur 1234, CDMX",
        "telefono": "5556667777",
        "correo": "contacto@coca-cola.com",
        "created_at": "2024-01-10T10:00:00.000000Z",
        "updated_at": "2024-01-10T10:00:00.000000Z"
      }
    ],
    "current_page": 1,
    "total": 1
  },
  "filters": {
    "search": null,
    "estatus": null
  }
}
```

---

### POST /clientes
**Crear cliente**

**Body (JSON):**
```json
{
  "numero_cliente": "CLI-001",
  "nombre_fiscal": "Coca-Cola FEMSA S.A. de C.V.",
  "nombre_comercial": "Coca-Cola",
  "rfc": "CCF990101XXX",
  "estatus": "activo",
  "fecha_alta": "2024-01-10",
  "direccion": "Av. Insurgentes Sur 1234, CDMX",
  "telefono": "5556667777",
  "correo": "contacto@coca-cola.com"
}
```

**Validaciones:**
- `numero_cliente`: requerido, string, máximo 255 caracteres, único por empresa
- `nombre_fiscal`: requerido, string, máximo 255 caracteres
- `nombre_comercial`: nullable, string, máximo 255 caracteres
- `rfc`: nullable, string, máximo 13 caracteres
- `estatus`: requerido, debe ser 'activo' o 'inactivo'
- `correo`: nullable, debe ser email válido

**Respuesta exitosa (302 redirect):**
Redirige a `/clientes` con mensaje de éxito.

**Errores (422):**
```json
{
  "errors": {
    "numero_cliente": ["El número de cliente ya existe."],
    "nombre_fiscal": ["El campo nombre fiscal es obligatorio."],
    "estatus": ["El estatus seleccionado no es válido."],
    "correo": ["El correo debe ser una dirección de correo válida."]
  }
}
```

---

### GET /clientes/{id}/edit
**Obtener cliente para editar**

**Respuesta:**
```json
{
  "cliente": {
    "id": 1,
    "empresa_id": 1,
    "numero_cliente": "CLI-001",
    "nombre_fiscal": "Coca-Cola FEMSA S.A. de C.V.",
    "nombre_comercial": "Coca-Cola",
    "rfc": "CCF990101XXX",
    "estatus": "activo",
    "fecha_alta": "2024-01-10",
    "direccion": "Av. Insurgentes Sur 1234, CDMX",
    "telefono": "5556667777",
    "correo": "contacto@coca-cola.com",
    "created_at": "2024-01-10T10:00:00.000000Z",
    "updated_at": "2024-01-10T10:00:00.000000Z"
  }
}
```

---

### PUT /clientes/{id}
**Actualizar cliente**

**Body (JSON):**
```json
{
  "numero_cliente": "CLI-001",
  "nombre_fiscal": "Coca-Cola FEMSA S.A. de C.V.",
  "nombre_comercial": "Coca-Cola México",
  "rfc": "CCF990101XXX",
  "estatus": "activo",
  "fecha_alta": "2024-01-10",
  "direccion": "Av. Insurgentes Sur 1234, CDMX",
  "telefono": "5556667777",
  "correo": "contacto@coca-cola.com"
}
```

**Validaciones:** Iguales que POST, pero `numero_cliente` debe ser único excluyendo el registro actual.

---

### DELETE /clientes/{id}
**Eliminar cliente**

**Validaciones:**
- No se puede eliminar si tiene órdenes de carga asociadas

**Respuesta exitosa (302 redirect):**
Redirige a `/clientes` con mensaje de éxito.

**Error (back con mensaje):**
Si tiene órdenes asociadas, retorna error: "No se puede eliminar el cliente porque tiene órdenes de carga asociadas"

---

## Notas Importantes

1. **Multiempresa**: Todos los endpoints filtran automáticamente por `empresa_id` del usuario autenticado
2. **Autenticación**: Todos los endpoints requieren autenticación (`auth` middleware)
3. **Validación única**: Los campos `numero_operador` y `numero_cliente` son únicos por empresa, no globalmente
4. **Fechas**: Formato ISO 8601 (YYYY-MM-DD)
5. **Paginación**: Por defecto 15 registros por página
