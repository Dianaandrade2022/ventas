# Sistema de gestión de ventas

## Descripción

**Sistema de gestión de ventas** es una aplicación que facilita la gestión de inventarios, ventas y presupuestos en una tienda. Incluye funcionalidades para la creación y gestión de facturas, presupuestos, clientes, productos, proveedores y usuarios, así como procedimientos almacenados para automatizar procesos comunes.

## Requisitos

- **Servidor de base de datos**: MariaDB 10.4.28 o superior
- **PHP**: Versión 8.2.4 o superior
- **phpMyAdmin**: Versión 5.2.1 o superior (opcional para administración de la base de datos)
- **Entorno de desarrollo**: XAMPP, MAMP, LAMP, o cualquier servidor web compatible con PHP y MariaDB

## Instalación

### Clonar el Repositorio

```bash
git clone git@github.com:danielsalina/sistema_de_gestion_de_ventas.git
cd proyecto
```

## Configurar la Base de Datos

1- Crear una base de datos en MariaDB:

```bash
CREATE DATABASE proyecto;
```

2- Importar el archivo SQL en la base de datos:

```bash
mysql -u usuario -p proyecto < tu_path/dump.sql
```

## Configurar el Entorno

1- En el archivo config de la ruta "config\config.php" completa la configuración de la base de datos:

```bash
define("HOST", "HOST");
define("USER", "USER");
define("PASSWORD", "PASSWORD");
define("DATABASE", "DATABASE");
define("MYSQLI", mysqli_init());
```

## Instalación de Dependencias (opcional)

```bash
composer install
```

## Uso

### Gestión de Productos

- Crear, actualizar y eliminar productos.
- Gestionar el stock de los productos.

### Gestión de Clientes

- Registrar nuevos clientes.
- Actualizar y eliminar información de clientes existentes.

### Gestión de Proveedores

- Añadir y gestionar proveedores.
- Creación de Presupuestos y Facturas
- Generar presupuestos y facturas basados en los productos disponibles.
- Calcular automáticamente el total de los presupuestos y facturas.

### Gestión de Usuarios y Roles

- Crear y gestionar usuarios con diferentes roles y permisos.

### Procedimientos Almacenados

- La base de datos incluye varios procedimientos almacenados para simplificar operaciones complejas:

```bash
1- sp_add_temporal_code_detail: Añade un detalle temporal basado en el código del producto.

2- sp_add_temporal_name_detail: Añade un detalle temporal basado en el nombre del producto.

3- sp_delete_temporal_detail: Elimina un detalle temporal.

4- sp_process_budget: Procesa un presupuesto.

5- sp_process_sale: Procesa una venta.
```

### Contribuir

### Si deseas contribuir a este proyecto, por favor, sigue estos pasos:

1- Haz un fork del repositorio.

2- Crea una nueva rama (git checkout -b feature/nueva-funcionalidad).

3- Realiza tus cambios y haz commits (git commit -am 'Añadir nueva funcionalidad').

4- Empuja o pushea la rama (git push origin feature/nueva-funcionalidad).

5- Abre un Pull Request.

### Licencia

- Este proyecto está licenciado bajo la Licencia MIT. Consulta el archivo LICENSE para más detalles.

### Contacto

- Para cualquier duda o consulta, puedes contactarme a través de mis redes sociales

<a href="https://www.linkedin.com/in/danielsalina/" target="_blank" rel="noopener noreferrer">
    <img src="https://img.icons8.com/fluent/48/000000/linkedin.png" alt="LinkedIn" style="width: 30px; height: 30px;"/>
</a>
<a href="https://github.com/danielsalina" target="_blank" rel="noopener noreferrer">
    <img src="https://img.icons8.com/fluent/48/000000/github.png" alt="GitHub" style="width: 30px; height: 30px;"/>
</a>
<a href="https://www.instagram.com/soydani_code/" target="_blank" rel="noopener noreferrer">
    <img src="https://img.icons8.com/fluent/48/000000/instagram-new.png" alt="Instagram" style="width: 30px; height: 30px;"/>
</a>
