# Zay Shop - E-Commerce PHP MVC

Una aplicación de e-commerce moderna construida con arquitectura PHP MVC personalizada, que migra desde HTML estático hacia un sistema dinámico y escalable.

## 🚀 Características

- **Arquitectura MVC** - Separación clara entre lógica, datos y presentación
- **Sistema de Rutas** - URLs amigables y limpias
- **Autenticación** - Sistema completo de login/registro
- **Carrito de Compras** - Funcionalidad completa de e-commerce
- **Lista de Deseos** - Productos favoritos para usuarios
- **Búsqueda Avanzada** - Filtros por categoría, precio, marca
- **Responsive Design** - Bootstrap 5 con diseño mobile-first
- **CSRF Protection** - Protección contra ataques CSRF
- **Session Management** - Manejo seguro de sesiones
- **Base de Datos** - PDO con consultas preparadas

## 📋 Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior / MariaDB 10.2+
- Apache con mod_rewrite habilitado
- Extensiones PHP: PDO, PDO_MySQL, GD (opcional para imágenes)

## 🛠️ Instalación

### 1. Clonar o descargar el proyecto

```bash
# Si usas Git
git clone <repository-url> zay-shop
cd zay-shop

# O descarga y extrae el ZIP
```

### 2. Configurar el servidor web

**Apache (recomendado):**
- Asegúrate de que el `DocumentRoot` apunte a la carpeta del proyecto
- Habilita `mod_rewrite`
- Los archivos `.htaccess` ya están configurados

**Nginx:**
```nginx
server {
    listen 80;
    server_name tu-dominio.com;
    root /path/to/project/public;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 3. Configurar la base de datos

```sql
-- Crear base de datos
CREATE DATABASE zay_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Crear usuario (opcional)
CREATE USER 'zay_user'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON zay_shop.* TO 'zay_user'@'localhost';
FLUSH PRIVILEGES;
```

### 4. Configurar variables de entorno

```bash
# Copiar archivo de ejemplo
cp .env.example .env
```

Edita el archivo `.env` con tus configuraciones:

```env
# Base de datos
DB_HOST=localhost
DB_NAME=zay_shop
DB_USER=root
DB_PASS=tu_password

# Aplicación
APP_NAME="Zay Shop"
APP_URL=http://localhost/zay-shop
APP_ENV=development
APP_DEBUG=true

# Email (opcional)
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-password
```

### 5. Importar esquema de base de datos

```bash
# Importar desde el archivo SQL
mysql -u root -p zay_shop < database/schema.sql

# O ejecutar migraciones individuales
mysql -u root -p zay_shop < database/migrations/001_create_users_table.sql
mysql -u root -p zay_shop < database/migrations/002_create_products_table.sql
# ... etc
```

### 6. Configurar permisos (Linux/Mac)

```bash
# Dar permisos de escritura a directorios necesarios
chmod -R 755 storage/
chmod -R 755 public/uploads/
chown -R www-data:www-data storage/
chown -R www-data:www-data public/uploads/
```

## 🗂️ Estructura del Proyecto

```
├── 📄 index.php                    # Punto de entrada (redirección)
├── 📁 app/                         # Núcleo de la aplicación
│   ├── 📁 controllers/             # Controladores MVC
│   ├── 📁 models/                  # Modelos de datos
│   ├── 📁 views/                   # Vistas y templates
│   ├── 📁 core/                    # Framework personalizado
│   ├── 📁 middlewares/             # Middlewares de seguridad
│   ├── 📁 helpers/                 # Funciones auxiliares
│   └── 📁 services/                # Servicios de negocio
├── 📁 public/                      # Archivos públicos
│   ├── 📄 index.php               # Front controller
│   ├── 📁 css/                    # Estilos CSS
│   ├── 📁 js/                     # JavaScript
│   ├── 📁 img/                    # Imágenes
│   └── 📁 uploads/                # Archivos subidos
├── 📁 config/                      # Configuraciones
├── 📁 database/                    # Migraciones y semillas
└── 📁 storage/                     # Logs y caché
```

## 🎯 Uso

### Rutas Disponibles

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/` | Página principal |
| GET | `/products` | Lista de productos |
| GET | `/products/{id}` | Detalle de producto |
| GET | `/cart` | Carrito de compras |
| POST | `/cart/add` | Agregar al carrito |
| GET | `/login` | Formulario de login |
| POST | `/login` | Procesar login |
| GET | `/register` | Formulario de registro |
| POST | `/register` | Procesar registro |

### Controladores

**Crear un nuevo controlador:**

```php
<?php

class MiControlador extends Controller
{
    public function index()
    {
        $data = ['titulo' => 'Mi Página'];
        return $this->renderWithLayout('mi-vista', $data);
    }
    
    public function store()
    {
        $this->validateCsrf();
        
        $data = $this->getPost();
        // Procesar datos...
        
        return $this->json(['success' => true]);
    }
}
```

### Modelos

**Crear un nuevo modelo:**

```php
<?php

class MiModelo extends Model
{
    protected $table = 'mi_tabla';
    protected $fillable = ['campo1', 'campo2'];
    
    public function metodoPersonalizado()
    {
        $sql = "SELECT * FROM {$this->table} WHERE condicion = :valor";
        return $this->db->select($sql, ['valor' => 123]);
    }
}
```

### Vistas

**Crear una vista:**

```php
<!-- app/views/mi-carpeta/mi-vista.php -->
<div class="container">
    <h1><?php echo View::escape($titulo); ?></h1>
    
    <form action="<?php echo View::url('ruta'); ?>" method="post">
        <?php echo csrf_field(); ?>
        <input type="text" name="campo" value="<?php echo old('campo'); ?>">
        
        <?php if (has_errors('campo')): ?>
            <div class="alert alert-danger">
                <?php echo errors('campo'); ?>
            </div>
        <?php endif; ?>
        
        <button type="submit">Enviar</button>
    </form>
</div>
```

## 🛡️ Seguridad

### CSRF Protection
Todas las formas incluyen automáticamente protección CSRF:

```php
// En el controlador
$this->validateCsrf();

// En la vista
<?php echo csrf_field(); ?>
```

### Sanitización de datos
```php
// Escapar output
echo View::escape($data);

// Sanitizar input
$clean = sanitize($input);
```

### Autenticación
```php
// Verificar si está autenticado
if (Session::isAuthenticated()) {
    // Usuario logueado
}

// Obtener datos del usuario
$userId = Session::getUserId();
$userData = Session::getUserData();
```

## 📊 Base de Datos

### Tablas Principales

- **users** - Usuarios del sistema
- **products** - Catálogo de productos
- **categories** - Categorías de productos
- **brands** - Marcas
- **cart** - Carrito de compras
- **orders** - Pedidos
- **reviews** - Reseñas de productos

### Migraciones

Las migraciones se encuentran en `database/migrations/` y deben ejecutarse en orden:

1. `001_create_users_table.sql`
2. `002_create_products_table.sql`
3. `003_create_categories_table.sql`
4. etc.

## 🎨 Personalización

### Themes y Estilos

Los estilos se organizan en:
- `public/css/main.css` - Estilos principales
- `public/css/components.css` - Componentes reutilizables
- `public/css/responsive.css` - Media queries

### Agregar nuevas páginas

1. Crear controlador en `app/controllers/`
2. Crear vista en `app/views/`
3. Agregar ruta en `config/routes.php`

### Middlewares

Crear middleware personalizado:

```php
<?php

class MiMiddleware
{
    public function handle($request, $response)
    {
        // Lógica del middleware
        if (!$condicion) {
            $response->forbidden();
        }
    }
}
```

## 🔧 Desarrollo

### Debugging

```php
// Dump and die
dd($variable);

// Log de errores
error_log('Mensaje de debug');

// Modo debug en .env
APP_DEBUG=true
```

### Caché

```php
// Limpiar caché (implementar según necesidades)
// Cache::clear();
```

## 📱 API Endpoints

### Cart API
- `POST /api/cart/add` - Agregar producto
- `POST /api/cart/update` - Actualizar cantidad
- `POST /api/cart/remove` - Remover producto
- `GET /api/cart/count` - Contador de productos

### Wishlist API  
- `POST /api/wishlist/toggle` - Agregar/remover de favoritos

## 🚀 Despliegue en Producción

### 1. Configurar entorno
```env
APP_ENV=production
APP_DEBUG=false
```

### 2. Optimizaciones
- Habilitar caché de OPcache
- Configurar compresión gzip
- Optimizar imágenes
- Minificar CSS/JS

### 3. Seguridad
- Configurar HTTPS
- Restringir acceso a archivos sensibles
- Configurar firewall
- Backups regulares de BD

## 🤝 Contribuir

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la licencia MIT. Ver archivo `LICENSE` para más detalles.

## 📞 Soporte

- **Documentación**: Revisa este README y los comentarios en el código
- **Issues**: Reporta problemas en el repositorio
- **Email**: Contacta al equipo de desarrollo

## 🎉 Créditos

- **Template Original**: TemplateMo 559 Zay Shop
- **Framework CSS**: Bootstrap 5
- **Iconos**: FontAwesome
- **JavaScript**: jQuery

---

¡Gracias por usar Zay Shop! 🛍️