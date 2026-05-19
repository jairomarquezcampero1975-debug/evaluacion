# PapuStore - Sistema Web de Venta

Sistema web academico para venta y gestion de tecnologia. Incluye landing page moderna, catalogo, carrito, favoritos, roles, panel administrativo, ventas, stock y autenticacion 2FA por correo.

## Tecnologias usadas

- PHP
- MySQL / MariaDB
- HTML5
- CSS3
- JavaScript
- Bootstrap 5
- Bootstrap Icons
- PHPMailer
- Git y GitHub

## Modulos del sistema

### Pagina publica
- Landing page moderna y responsive
- Navbar con secciones
- Banner principal premium
- Productos destacados
- Categorias
- Beneficios de la tienda
- Footer informativo

### Autenticacion y seguridad
- Registro de usuarios
- Validacion de datos
- Contrasena minima de 8 caracteres, una mayuscula y un numero
- Contrasenas encriptadas con `password_hash()`
- Login con `password_verify()`
- Verificacion 2FA despues del login
- Envio de codigo 2FA por correo usando PHPMailer
- Manejo de sesiones con `$_SESSION`
- Logout funcional
- Proteccion de rutas privadas
- Separacion de roles admin y cliente

### Cliente
- Catalogo de productos activos
- Busqueda de productos
- Filtro por categoria
- Filtro por marca
- Vista detalle de producto
- Agregar productos a favoritos
- Eliminar favoritos
- Agregar productos al carrito
- Modificar cantidades del carrito
- Eliminar productos del carrito
- Calcular subtotal y total
- Confirmar compra
- Historial de compras

### Administrador
- Dashboard profesional con metricas
- CRUD de productos
- CRUD de categorias
- Ver usuarios registrados
- Ver ventas realizadas
- Cambiar estado de ventas
- Control de stock bajo
- Gestion visual moderna con sidebar

## Instalacion en XAMPP

1. Copiar la carpeta `evaluacion` dentro de `htdocs`.
2. Iniciar Apache y MySQL desde XAMPP.
3. Entrar a phpMyAdmin.
4. Importar el archivo:

```text
database/sistema_ventas.sql
```

5. Revisar la conexion en:

```text
config/conexion.php
```

Configuracion por defecto:

```php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "sistema_ventas";
```

6. Abrir el sistema:

```text
http://localhost/evaluacion/
```

## Usuarios de prueba

### Administrador

```text
Correo: admin@gmail.com
Contrasena: Admin123
```

### Cliente

```text
Correo: cliente@gmail.com
Contrasena: Cliente123
```

## Correo 2FA

El sistema usa el correo temporal configurado en:

```text
includes/enviar_correo.php
```

Ese archivo contiene la cuenta SMTP usada para enviar el codigo de verificacion. Si se cambia el correo, tambien se debe cambiar la contrasena de aplicacion.

Importante: si el proyecto se sube a GitHub, se recomienda quitar la contrasena del codigo o usar variables de entorno.

## Flujo Git y GitHub recomendado

```bash
git checkout -b modulo-productos
git add .
git commit -m "Mejora modulo de productos"
git push origin modulo-productos
```

Recomendacion para el grupo:

- Un integrante trabaja autenticacion y seguridad.
- Un integrante trabaja admin y productos.
- Un integrante trabaja cliente, carrito y favoritos.
- Hacer commits claros por cada mejora.
- Usar pull antes de trabajar para evitar conflictos.

## Defensa tecnica breve

Este proyecto cumple con una arquitectura academica completa para una tienda web. La autenticacion usa contrasenas protegidas con `password_hash()` y verificacion con `password_verify()`. Despues del login se activa un segundo factor por correo. Las rutas privadas estan protegidas por sesiones y separacion de roles. El cliente puede comprar mediante carrito, favoritos e historial. El administrador puede gestionar productos, categorias, usuarios, ventas, estados y stock. La base de datos incluye claves primarias, foraneas, indices y relaciones entre usuarios, productos, categorias, ventas, detalle de venta y favoritos.

## Integrantes

- Integrante 1
- Integrante 2
- Integrante 3
