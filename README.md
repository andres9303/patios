# Patios

## Descripción del Proyecto
Este proyecto es una aplicación web modular desarrollada con Laravel, Livewire y la plantilla Jetstream. El sistema está diseñado para dar solución al cliente en diferentes áreas para el manejo y control de la información.

### Aplicación Modular
- **Asignación de permisos y accesos:** Manejo detallado de permisos para diferentes roles de usuario, garantizando que cada módulo y acción tenga un acceso restringido basado en las políticas de usuario.
- **Diseño adaptable:** La aplicación utiliza diferentes plantillas para usuarios con distintos roles, lo que asegura una experiencia de usuario coherente y personalizada.

### Primer Módulo: Mesa de Ayuda y Front Desk
Este primer módulo combina funcionalidades de una mesa de ayuda y un front desk, lo que permite gestionar solicitudes de servicio, reportes de incidentes, y soporte directo. Los usuarios pueden crear y gestionar tickets, los cuales serán manejados de acuerdo con las prioridades y niveles de acceso asignados.

### Segundo Módulo: Gestión de Sistemas y Subsistemas
Este módulo permitirá:
- **Registro de sistemas y subsistemas:** Crear diferentes sistemas (como hotel, habitaciones, piscina, etc.) y subsistemas estructurales asociados a cada uno.
- **Gestión de mantenimientos y costos:** Cargar y gestionar los mantenimientos necesarios para cada sistema, registrar sus costos y generar órdenes de trabajo.
- **Programación de mantenimientos periódicos:** Programar mantenimientos y acciones preventivas o correctivas, con una bitácora de los eventos de mantenimiento realizados para cada sistema y subsistema.
- **Hoja de vida de los sistemas:** Mantener un historial completo de cada sistema y subsistema, ayudando a calcular el costo de mantener cada uno de ellos funcional.

## Tecnologías Utilizadas

- **Framework:** Laravel
- **Frontend:** Laravel Jetstream (con Livewire y Tailwind CSS)
- **Interactividad:** Livewire
- **Base de Datos:** MySQL

## Requisitos Previos

- **PHP >= 8.1**
- **Composer**
- **MySQL**
- **Node.js y NPM** (para compilación de assets con Laravel Mix)

## Instalación
Sigue estos pasos para instalar y configurar el proyecto en tu entorno local:

1. Clona el repositorio:
    ```bash
    git clone https://github.com/andres9303/patios.git
    ```

2. Navega al directorio del proyecto:
    ```bash
    cd patios
    ```

3. Instala las dependencias:
    ```bash
    composer install
    npm install
    ```

4. Configura el archivo `.env`:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5. Configura la base de datos en el archivo `.env` y luego ejecuta las migraciones:
    ```bash
    php artisan migrate --seed
    ```

6. Compila los assets:
    ```bash
    npm run dev
    ```

7. Inicia el servidor de desarrollo:
    ```bash
    php artisan serve
    ```

## Uso

### Roles y Permisos
El sistema incluye un módulo completo de gestión de roles y permisos, donde podrás asignar distintos niveles de acceso a los usuarios.

### Mesa de Ayuda y Front Desk
Permite a los usuarios:

1. Crear y gestionar tickets de soporte.
2. Visualizar el estado de los tickets en curso.

### Gestión de Sistemas y Subsistemas
Permite registrar y organizar los sistemas estructurales y sus subsistemas, programar y gestionar mantenimientos, y registrar los costos asociados para llevar un control exhaustivo de los activos.

## Estructura del Proyecto

- **app/Http/Controllers:** Controladores de los módulos de gestión de permisos, sistemas, subsistemas y mesa de ayuda.
- **app/Models:** Modelos que representan los sistemas, subsistemas, usuarios, roles y permisos.
- **database/migrations:** Migraciones para crear y modificar la estructura de la base de datos.
- **resources/views:** Vistas de la aplicación para cada uno de los módulos y plantillas personalizadas según el rol del usuario.
- **routes/web.php:** Definición de las rutas de la aplicación, incluyendo la gestión de permisos y accesos.

## Licencia
Este proyecto está licenciado bajo la Licencia MIT. Consulta el archivo [LICENSE](LICENSE) para más detalles.