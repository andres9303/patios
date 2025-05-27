# Patios - Sistema de Gestión Integral

## 🚀 Descripción del Proyecto

Patios es una aplicación web modular desarrollada con Laravel, Livewire y Jetstream, diseñada para ofrecer una solución integral en la gestión de espacios, mantenimientos, proyectos y soporte. El sistema está construido con un enfoque en la escalabilidad y la facilidad de uso, permitiendo una gestión eficiente de múltiples áreas operativas.

## 🛠️ Módulos Principales

### 1. Gestión de Espacios y CheckLists
- **Plantillas de CheckList:** Crea y personaliza plantillas para diferentes tipos de inspecciones.
- **CheckLists Dinámicos:** Genera listas de verificación con diferentes tipos de campos (texto, número, fecha, booleano).
- **Gestión de Respuestas:** Almacena respuestas estructuradas con soporte para descripciones adicionales.
- **Historial Completo:** Mantén un registro histórico de todas las inspecciones realizadas.

### 2. Mesa de Ayuda y Gestión de Tickets
- **Sistema de Tickets:** Gestión completa de solicitudes de servicio e incidencias.
- **Seguimiento en Tiempo Real:** Actualizaciones en tiempo real del estado de los tickets.
- **Asignación de Responsables:** Distribución eficiente de tareas entre equipos.
- **Soporte para Archivos Adjuntos:** Capacidad para adjuntar imágenes y documentos a los tickets.

### 3. Gestión de Proyectos y Actividades
- **Planificación de Proyectos:** Crea y gestiona proyectos con sus respectivas actividades.
- **Seguimiento de Avances:** Monitorea el progreso de cada proyecto y sus tareas asociadas.
- **Asignación de Recursos:** Asigna responsables y recursos a las diferentes actividades.

### 4. Inventario y Gestión de Costos
- **Control de Inventario:** Registro y seguimiento de productos y materiales.
- **Movimientos de Inventario:** Entradas, salidas y ajustes de inventario.
- **Cálculo de Costos:** Seguimiento detallado de costos asociados a proyectos y actividades.

### 5. Reportes y Análisis
- **Reportes Personalizables:** Genera informes detallados de todas las áreas del sistema.
- **Tableros de Control:** Visualización intuitiva de métricas clave.
- **Exportación de Datos:** Capacidad para exportar reportes en diferentes formatos.

## 🏗️ Estructura Técnica

### Tecnologías Principales
- **Backend:** PHP 8.1+, Laravel 10+
- **Frontend:** Livewire, Tailwind CSS, Alpine.js
- **Base de Datos:** MySQL 8.0+
- **Autenticación:** Laravel Jetstream con Fortify
- **Despliegue:** Compatible con la mayoría de servidores web (Apache/Nginx)

### Requisitos del Sistema
- PHP >= 8.1
- Composer
- MySQL 8.0+
- Node.js 16+ y NPM

## 🚀 Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/andres9303/patios.git
   cd patios
   ```

2. **Instalar dependencias**
   ```bash
   composer install
   npm install
   ```

3. **Configuración del entorno**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configuración de la base de datos**
   - Crear una base de datos MySQL
   - Configurar las variables de conexión en el archivo `.env`

5. **Ejecutar migraciones y seeders**
   ```bash
   php artisan migrate --seed
   ```

6. **Compilar assets**
   ```bash
   npm run dev
   # O para producción:
   # npm run build
   ```

7. **Iniciar el servidor**
   ```bash
   php artisan serve
   ```

8. **Acceder al sistema**
   - URL: http://localhost:8000
   - Credenciales por defecto (si se usó el seeder):
     - Email: admin@example.com
     - Contraseña: password

## 🔐 Seguridad

- Control de acceso basado en roles (RBAC)
- Protección CSRF

## Estructura del Proyecto

- **app/Http/Controllers:** Controladores de los módulos de gestión de permisos, sistemas, subsistemas y mesa de ayuda.
- **app/Models:** Modelos que representan los sistemas, subsistemas, usuarios, roles y permisos.
- **database/migrations:** Migraciones para crear y modificar la estructura de la base de datos.
- **resources/views:** Vistas de la aplicación para cada uno de los módulos y plantillas personalizadas según el rol del usuario.
- **routes/web.php:** Definición de las rutas de la aplicación, incluyendo la gestión de permisos y accesos.

## 📄 Licencia

Este proyecto está licenciado bajo la Licencia MIT.