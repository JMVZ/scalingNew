# 🚀 INSTRUCCIONES DE INSTALACIÓN - SCALING 1.0

## 📋 Requisitos Previos

- PHP >= 8.2
- Composer
- Node.js >= 18 y npm
- MySQL/MariaDB
- Git

---

## 🔧 PASOS DE INSTALACIÓN

### 1. Clonar el repositorio
```bash
git clone https://github.com/JMVZ/scalingNew.git
cd scalingNew
```

### 2. Instalar dependencias de PHP
```bash
composer install
```

### 3. Instalar dependencias de Node.js
```bash
npm install
```

### 4. Configurar el archivo .env
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configurar la base de datos en .env
Edita el archivo `.env` y configura tu base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 6. Ejecutar migraciones y seeders
```bash
php artisan migrate:fresh --seed
```

Este comando:
- ✅ Crea todas las tablas
- ✅ Crea la empresa demo
- ✅ Crea el usuario demo
- ✅ Crea clientes y operadores de ejemplo

### 7. Compilar assets (modo desarrollo)
```bash
npm run dev
```

O para producción:
```bash
npm run build
```

### 8. Iniciar el servidor
```bash
php artisan serve
```

El servidor estará disponible en: `http://localhost:8000`

---

## 🔐 CREDENCIALES DE INICIO DE SESIÓN

Después de ejecutar `php artisan migrate:fresh --seed`, puedes iniciar sesión con:

### **RFC de la Empresa:**
```
TDE010101AAA
```

### **Email:**
```
admin@demo.com
```

### **Contraseña:**
```
password
```

---

## 📊 DATOS DE DEMO INCLUIDOS

Después de ejecutar el seeder, tendrás:

- ✅ **1 Empresa**: Transportes Demo S.A. de C.V.
- ✅ **1 Usuario**: Admin Demo
- ✅ **3 Clientes**: Coca-Cola, Liverpool, CEMEX
- ✅ **2 Operadores**: Juan Pérez y María González

---

## 🛠️ COMANDOS ÚTILES

### Limpiar caché
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Recrear base de datos (CUIDADO: borra todo)
```bash
php artisan migrate:fresh --seed
```

### Ver rutas disponibles
```bash
php artisan route:list
```

### Ejecutar en modo desarrollo (con hot reload)
```bash
# Terminal 1: Servidor Laravel
php artisan serve

# Terminal 2: Vite (hot reload)
npm run dev
```

---

## 📁 ESTRUCTURA DEL PROYECTO

```
scalingNew/
├── app/
│   ├── Http/Controllers/Web/    # Controladores de catálogos
│   ├── Models/                   # Modelos Eloquent
│   └── ...
├── database/
│   ├── migrations/               # Migraciones de BD
│   └── seeders/                  # Seeders (DemoSeeder)
├── resources/
│   ├── js/
│   │   ├── Pages/                # Vistas Vue/Inertia
│   │   │   ├── Clientes/         # CRUD Clientes
│   │   │   ├── Catalogos/        # Otros catálogos
│   │   │   └── Dashboard.vue      # Dashboard principal
│   │   └── Layouts/              # Layouts (AppLayout.vue)
│   └── ...
└── routes/
    └── web.php                   # Rutas web
```

---

## 🎯 ESTADO ACTUAL DEL PROYECTO

### ✅ FASE 1 - Completado (50%)
- ✅ **Clientes**: CRUD completo
- ✅ **Operadores**: CRUD completo
- ⚠️ **Unidades**: Solo listado (falta Create/Edit)
- ⚠️ **Usuarios y Roles**: Autenticación básica (sin roles)

### ⚠️ FASE 2 - Backend listo, falta frontend
- Bancos, Cuentas Bancarias, Impuestos, Conceptos, Centros de Costo

### ⚠️ FASE 3 - Backend listo, falta frontend
- Tarifas, Proveedores

---

## 🐛 SOLUCIÓN DE PROBLEMAS

### Error: "Vite manifest not found"
```bash
npm run build
```

### Error: "Class not found"
```bash
composer dump-autoload
```

### Error: "Database connection failed"
- Verifica las credenciales en `.env`
- Asegúrate de que MySQL esté corriendo
- Verifica que la base de datos exista

### Error: "Permission denied" en storage
```bash
chmod -R 775 storage bootstrap/cache
```

---

## 📞 SOPORTE

Si tienes problemas, revisa:
1. Los logs en `storage/logs/laravel.log`
2. La consola del navegador (F12)
3. Los logs de Vite en la terminal

---

**Última actualización**: 8 de enero de 2026
