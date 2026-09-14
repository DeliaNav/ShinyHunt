# ShinyHunt

ShinyHunt es una aplicación web orientada a coleccionistas de cartas Pokémon. El proyecto combina un buscador de cartas, gestión de colecciones y listas de deseos, compraventa entre usuarios, carrito de compra, pagos mediante Stripe y mensajería privada.

El proyecto está desarrollado en PHP siguiendo una arquitectura sencilla basada en controladores, modelos y vistas, con MySQL como sistema de base de datos.

## Características principales

- Registro, inicio y cierre de sesión de usuarios.
- Gestión y edición del perfil.
- Búsqueda de cartas Pokémon mediante la API de TCGdex.
- Consulta del detalle de una carta.
- Búsqueda y consulta de perfiles de otros usuarios.
- Gestión de una colección personal de cartas.
- Gestión de una lista de deseos (wishlist).
- Publicación de cartas para su venta.
- Gestión de anuncios/listings y cancelación de ventas.
- Marketplace para consultar cartas disponibles.
- Carrito de compra.
- Proceso de checkout y pago mediante Stripe.
- Gestión y consulta de ventas realizadas.
- Mensajería privada entre usuarios.
- Sistema de reseñas y valoración de vendedores.
- Persistencia de datos mediante MySQL.
- Enrutamiento centralizado mediante `index.php`.
- Reescritura de URLs mediante Apache y `.htaccess`.

## Tecnologías utilizadas

- PHP
- MySQL / MariaDB
- HTML5
- CSS3
- JavaScript
- Apache
- Composer
- Stripe PHP SDK
- TCGdex API

## Estructura del proyecto

```text
ShinyHunt-main/
└── Codigo/
    ├── controllers/       # Controladores de la aplicación
    ├── models/            # Modelos y acceso a datos
    ├── views/              # Vistas PHP de la aplicación
    ├── lib/                # Clases auxiliares y servicios
    ├── public/             # Recursos públicos: CSS, JS, imágenes, etc.
    ├── vendor/             # Dependencias instaladas mediante Composer
    ├── bbdd.sql            # Script de creación de la base de datos
    ├── composer.json       # Dependencias PHP
    ├── composer.lock       # Versiones bloqueadas de dependencias
    ├── .htaccess           # Configuración de URLs amigables
    └── index.php           # Punto de entrada y router principal
```

## Arquitectura

La aplicación utiliza una arquitectura inspirada en MVC:

- `controllers/`: recibe las peticiones y coordina la lógica de cada funcionalidad.
- `models/`: contiene la lógica relacionada con los datos y la base de datos.
- `views/`: contiene la presentación HTML/PHP.
- `lib/`: incluye componentes compartidos como autenticación, conexión a base de datos, router, integración con Stripe y acceso a TCGdex.
- `index.php`: actúa como punto de entrada de la aplicación y define las rutas disponibles.

La autenticación utiliza sesiones de PHP. El acceso a las funcionalidades que requieren usuario autenticado se controla mediante la clase `Auth`.

## Base de datos

El archivo `bbdd.sql` crea la base de datos:

```text
shinny_hunt
```

Entre las principales tablas se encuentran:

- `users`: usuarios registrados.
- `collections`: cartas pertenecientes a la colección de cada usuario.
- `wishlists`: listas de deseos.
- `listings`: cartas publicadas para la venta.
- `cart_items`: productos añadidos al carrito.
- `orders`: pedidos realizados.
- `order_items`: líneas de cada pedido.
- `reviews`: valoraciones de vendedores.
- `messages`: mensajes privados entre usuarios.

## Requisitos

Para ejecutar el proyecto localmente se recomienda disponer de:

- PHP 8.x o una versión compatible con el código y las dependencias.
- Apache con `mod_rewrite` habilitado.
- MySQL o MariaDB.
- Composer.
- Conexión a Internet para acceder a TCGdex.
- Cuenta/configuración de Stripe si se quiere probar el sistema de pagos.

## Instalación

### 1. Clonar o copiar el proyecto

Coloca la carpeta `Codigo` dentro del directorio público de tu servidor Apache.

La configuración actual utiliza como ruta base:

```text
/TFG/Codigo/
```

Por ejemplo, si utilizas XAMPP:

```text
htdocs/
└── TFG/
    └── Codigo/
```

Si cambias esta ubicación, tendrás que adaptar las rutas definidas en `index.php` y `.htaccess`.

### 2. Crear la base de datos

Abre MySQL/phpMyAdmin y ejecuta:

```text
Codigo/bbdd.sql
```

El script crea automáticamente la base de datos `shinny_hunt` y todas las tablas necesarias.

### 3. Configurar la conexión a MySQL

La conexión actual está definida en:

```text
Codigo/lib/Database.php
```

Por defecto utiliza:

```text
Host: localhost
Base de datos: shinny_hunt
Usuario: root
Contraseña: vacía
```

Si tu instalación de MySQL utiliza otras credenciales, modifica estos datos antes de iniciar la aplicación.

### 4. Instalar dependencias

Desde la carpeta `Codigo` ejecuta:

```bash
composer install
```

El proyecto utiliza principalmente el SDK oficial de Stripe para PHP.

Si la carpeta `vendor/` ya está incluida en el proyecto, este paso puede no ser necesario, aunque se recomienda ejecutar `composer install` para garantizar que las dependencias coincidan con `composer.lock`.

### 5. Configurar Stripe

La integración de Stripe obtiene las claves desde un archivo:

```text
Codigo/.env
```

Crea el archivo `.env` con:

```env
STRIPE_SECRET_KEY=tu_clave_secreta
STRIPE_PUBLISHABLE_KEY=tu_clave_publicable
```

No publiques ni subas las claves secretas a un repositorio.

### 6. Configurar Apache

El proyecto incluye un `.htaccess` que redirige las peticiones al `index.php` cuando no existe físicamente el recurso solicitado.

Asegúrate de que `mod_rewrite` esté habilitado y que Apache permita el uso de `.htaccess`.

## Ejecución

Una vez configurado Apache y MySQL, accede desde el navegador a:

```text
http://localhost/TFG/Codigo/
```

Desde ahí se puede acceder a la página de bienvenida y al resto de funcionalidades de la aplicación.

## Rutas principales

Algunas de las rutas definidas actualmente son:

```text
/TFG/Codigo/                  # Página de bienvenida
/TFG/Codigo/home              # Página principal
/TFG/Codigo/login             # Inicio de sesión
/TFG/Codigo/registro          # Registro
/TFG/Codigo/logout            # Cierre de sesión

/TFG/Codigo/buscar            # Buscador de cartas
/TFG/Codigo/cards/{id}        # Detalle de carta

/TFG/Codigo/coleccion         # Colección personal
/TFG/Codigo/wishlist          # Lista de deseos

/TFG/Codigo/usuarios/buscar   # Buscador de usuarios
/TFG/Codigo/usuario/{id}      # Perfil de otro usuario

/TFG/Codigo/perfil            # Perfil propio

/TFG/Codigo/vender            # Publicar una carta
/TFG/Codigo/vender/{cardId}   # Crear anuncio para una carta

/TFG/Codigo/carrito           # Carrito
/TFG/Codigo/carrito/checkout  # Checkout
/TFG/Codigo/carrito/pagar     # Pago
/TFG/Codigo/carrito/resultado # Resultado del pago

/TFG/Codigo/ventas            # Ventas

/TFG/Codigo/mensajes          # Mensajería
/TFG/Codigo/mensajes/{user}   # Chat con un usuario
```

## API de cartas

La aplicación utiliza TCGdex para obtener información sobre sets y cartas Pokémon.

La integración se encuentra en:

```text
Codigo/lib/TCGApi.php
```

La aplicación utiliza la API para:

- Obtener los sets disponibles.
- Consultar información de un set.
- Obtener el detalle de una carta.
- Buscar cartas por nombre.
- Obtener cartas aleatorias.
- Construir las URLs de las imágenes de las cartas.

La API utilizada actualmente es:

```text
https://api.tcgdex.net/v2/en
```

La disponibilidad y el comportamiento de la API externa pueden afectar a las funcionalidades de búsqueda y visualización de cartas.

## Pagos

Los pagos se integran mediante Stripe utilizando el paquete:

```text
stripe/stripe-php
```

La versión declarada en `composer.json` pertenece a la rama:

```text
^20.1
```

Para realizar pruebas reales de pago se deben utilizar las claves y configuración correspondientes de Stripe.

## Seguridad

Para desplegar el proyecto en un entorno real se recomienda:

- No almacenar claves de Stripe en el código fuente.
- Mantener `.env` fuera del control de versiones.
- Utilizar contraseñas seguras para MySQL.
- Configurar HTTPS.
- Revisar y validar todos los datos recibidos del usuario.
- Mantener actualizadas las dependencias de Composer.
- Revisar la configuración de PHP y Apache antes de pasar a producción.

## Desarrollo

Los puntos principales para modificar o ampliar la aplicación son:

```text
controllers/    -> lógica de las peticiones
models/         -> acceso y operaciones con datos
views/          -> interfaz
lib/            -> servicios compartidos
public/css/     -> estilos
public/js/      -> scripts
index.php       -> rutas
bbdd.sql        -> estructura de la base de datos
```

Para añadir una nueva funcionalidad normalmente se debe:

1. Crear o modificar el modelo correspondiente.
2. Crear el controlador.
3. Crear las vistas necesarias.
4. Registrar las rutas en `index.php`.
5. Añadir o modificar las tablas de `bbdd.sql` si se necesitan nuevos datos.
6. Añadir los estilos o scripts necesarios en `public/`.

## Estado del proyecto

ShinyHunt es una aplicación web de gestión y compraventa de cartas coleccionables que integra funcionalidades de colección, marketplace, comunicación entre usuarios y pagos online.

El proyecto está preparado principalmente para un entorno de desarrollo local basado en Apache + PHP + MySQL.

## Autor

Proyecto académico / TFG: **ShinyHunt**.
Delia Méndez
