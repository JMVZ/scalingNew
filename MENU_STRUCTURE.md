# Estructura del Menú del Sistema

## Arquitectura

El menú del sistema está completamente rediseñado con una arquitectura basada en configuración JSON, lo que permite:

- **Mantenimiento centralizado**: Todos los elementos del menú se definen en un solo archivo JSON
- **Control por roles**: Preparado para filtrar módulos según permisos del usuario
- **Escalabilidad**: Fácil agregar nuevos módulos y submenús sin modificar código
- **SPA Ready**: Diseñado para aplicaciones de página única (Single Page Applications)

## Archivos Clave

### 1. `resources/js/config/menu.json`
Archivo de configuración central que define todos los módulos y sus elementos.

### 2. `resources/js/composables/useMenu.js`
Composable Vue que maneja la lógica del menú:
- Estado de módulos abiertos/cerrados
- Detección de rutas activas
- Funciones de control del menú

### 3. `resources/js/Layouts/AppLayout.vue`
Layout principal que renderiza el menú lateral y los paneles deslizables.

## Estructura del Menú

El menú está organizado en **6 módulos principales**:

### 1. Configuración
Catálogos maestros compartidos por todo el sistema:
- Clientes
- Grupos de Clientes
- Impuestos
- Monedas
- Bancos
- Cuentas Bancarias
- Certificados de Sello Digital
- Folios
- Addendas
- Parámetros Generales

### 2. Operación
Todo lo relacionado con la operación diaria del tráfico:
- Órdenes de Carga (Viajes)
- Remitente / Destinatario
- Rutas
- Tipos de Viaje
- Clasificaciones de Viaje
- Estatus de Viaje
- Clasificación de Mercancía
- Evidencias

### 3. Flotilla
Recursos físicos exclusivamente:
- Unidades
- Tipos de Unidades
- Grupos de Unidades
- Estatus de Unidades
- Propietarios de Equipo

### 4. Operadores
Información del personal operativo:
- Operadores
- Puestos
- Incidencias

### 5. Facturación
Procesos CFDI únicamente:
- Conceptos de Facturación
- Facturas
- Notas de Crédito
- Carta Porte

### 6. Cobranza
Pagos posteriores a facturación:
- Pagos
- Complementos de Pago
- Estados de Cuenta

## Formato del JSON

Cada módulo tiene la siguiente estructura:

```json
{
  "id": "identificador-unico",
  "name": "Nombre del Módulo",
  "icon": "path-d-svg",
  "description": "Descripción breve",
  "items": [
    {
      "id": "identificador-item",
      "name": "Nombre del Item",
      "route": "/ruta",
      "icon": "path-d-svg"
    }
  ]
}
```

### Campos Requeridos

- **id**: Identificador único del módulo/item (usado para control de estado)
- **name**: Nombre visible en el menú
- **icon**: Path del SVG (formato Heroicons)
- **description**: Descripción que aparece en el header del panel
- **route**: Ruta de navegación (debe coincidir con las rutas definidas en `routes/web.php`)

## Agregar Nuevos Elementos

### Agregar un nuevo item a un módulo existente:

1. Edita `resources/js/config/menu.json`
2. Localiza el módulo correspondiente
3. Agrega un nuevo objeto en el array `items`:

```json
{
  "id": "nuevo-item",
  "name": "Nuevo Item",
  "route": "/nuevo-item",
  "icon": "M12 4v16m8-8H4"
}
```

4. Asegúrate de que la ruta esté definida en `routes/web.php`

### Agregar un nuevo módulo:

1. Edita `resources/js/config/menu.json`
2. Agrega un nuevo objeto en el array `modules`:

```json
{
  "id": "nuevo-modulo",
  "name": "Nuevo Módulo",
  "icon": "M12 4v16m8-8H4",
  "description": "Descripción del módulo",
  "items": [
    {
      "id": "item-1",
      "name": "Item 1",
      "route": "/item-1",
      "icon": "M12 4v16m8-8H4"
    }
  ]
}
```

## Control por Roles (Futuro)

El sistema está preparado para control por roles. Para implementarlo:

1. Modifica `useMenu.js` para filtrar módulos según permisos:

```javascript
const filteredModules = computed(() => {
  const userPermissions = page.props.auth.user.permissions;
  return menuConfig.modules.filter(module => 
    userPermissions.includes(`menu.${module.id}`)
  );
});
```

2. Usa `filteredModules` en lugar de `menuConfig.modules` en el template

## Iconos

Los iconos usan el formato de paths SVG de Heroicons. Puedes encontrar iconos en:
- https://heroicons.com/
- Copia el path del atributo `d` del SVG

Ejemplo:
```html
<!-- Heroicon -->
<path d="M12 4v16m8-8H4" />

<!-- En JSON -->
"icon": "M12 4v16m8-8H4"
```

## Rutas Existentes

Las siguientes rutas ya están implementadas y funcionando:
- `/dashboard`
- `/clientes`
- `/ordenes-carga`
- `/unidades`
- `/operadores`
- `/rutas`
- `/tarifas`
- `/centros-costo`
- `/proveedores`
- `/bancos`
- `/cuentas-bancarias`
- `/conceptos`
- `/impuestos`

## Rutas Pendientes de Implementar

Las siguientes rutas están definidas en el menú pero aún no tienen controladores:
- `/grupos-clientes`
- `/monedas`
- `/certificados`
- `/folios`
- `/addendas`
- `/parametros`
- `/remitente-destinatario`
- `/tipos-viaje`
- `/clasificaciones-viaje`
- `/estatus-viaje`
- `/clasificacion-mercancia`
- `/evidencias`
- `/tipos-unidades`
- `/grupos-unidades`
- `/estatus-unidades`
- `/propietarios-equipo`
- `/puestos`
- `/incidencias`
- `/facturas`
- `/notas-credito`
- `/carta-porte`
- `/pagos`
- `/complementos-pago`
- `/estados-cuenta`

Estas rutas pueden agregarse gradualmente sin modificar la estructura del menú.

## Características de UI

- **Sidebar colapsable**: Menú lateral con iconos que se expande al hacer clic
- **Paneles deslizables**: Cada módulo abre un panel lateral con sus submenús
- **Detección automática**: El módulo activo se abre automáticamente al navegar
- **Overlay**: Fondo semitransparente que permite cerrar el menú al hacer clic fuera
- **Tooltips**: Información adicional al pasar el mouse sobre los iconos
- **Estados activos**: Resaltado visual de la ruta actual

## Mejoras Futuras

- [ ] Implementar control por roles
- [ ] Agregar búsqueda en el menú
- [ ] Favoritos/Accesos rápidos
- [ ] Historial de navegación
- [ ] Modo oscuro
- [ ] Personalización de orden de módulos
