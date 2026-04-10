## Ejecutar Proyecto (1 click)

[![Run](https://gitpod.io/button/open-in-gitpod.svg)](https://gitpod.io/#https://github.com/oscar2004JT/NOSQL)


# Mi Mercado Global

Aplicacion web construida con Laravel para consultar informacion de usuarios, pedidos e items almacenados en una base de datos NoSQL en Amazon DynamoDB o DynamoDB Local.

## Descripcion general

El proyecto muestra:

- Perfil del usuario
- Lista de pedidos recientes
- Detalle de un pedido con sus items

La aplicacion expone rutas web y API, consulta la informacion desde DynamoDB y la presenta en una interfaz simple construida con React cargado desde CDN.

## Arquitectura usada

El proyecto sigue una arquitectura por capas, cercana a una arquitectura hexagonal / clean architecture ligera:

- `app/Domain`
  Contiene las entidades del negocio como `UserProfile`, `Order` y `OrderItem`.
- `app/Application`
  Contiene los casos de uso y servicios de aplicacion, por ejemplo `MercadoQueryService` y los datos demo en `SampleData`.
- `app/Contracts`
  Define contratos o interfaces, como `UserRepository`.
- `app/Infrastructure`
  Implementa el acceso a DynamoDB mediante `DynamoDbUserRepository`.
- `app/Http/Controllers`
  Expone la aplicacion mediante controladores web y API.
- `resources/views` y `public/js`
  Contienen la vista Blade y el frontend que consume la API.

Esta separacion permite desacoplar la logica de negocio del framework y de la tecnologia de persistencia.

## Dependencias necesarias

Para ejecutar el proyecto se necesita tener instalado:

- PHP 8.2 o superior
- Composer
- Node.js
- npm
- Laravel 12
- DynamoDB Local o acceso a una instancia real de Amazon DynamoDB

Si vas a usar DynamoDB Local, normalmente lo puedes correr con:

- Docker

## Dependencias del proyecto

### Dependencias PHP

Definidas en [`composer.json`](c:\Users\oscar\Downloads\NOSQL\V1\laravel_app\composer.json):

- `laravel/framework`
- `laravel/tinker`
- `aws/aws-sdk-php`

### Dependencias de desarrollo en PHP

- `fakerphp/faker`
- `laravel/pail`
- `laravel/pint`
- `laravel/sail`
- `mockery/mockery`
- `nunomaduro/collision`
- `phpunit/phpunit`

### Dependencias de frontend

Definidas en [`package.json`](c:\Users\oscar\Downloads\NOSQL\V1\laravel_app\package.json):

- `vite`
- `laravel-vite-plugin`
- `tailwindcss`
- `@tailwindcss/vite`
- `axios`
- `concurrently`

### Librerias usadas en tiempo de ejecucion del frontend

La vista principal tambien carga estas librerias desde CDN:

- React 18
- ReactDOM 18
- Babel Standalone

## Libreria usada para conectarse a DynamoDB

La conexion a la base NoSQL se hace con la libreria:

- `aws/aws-sdk-php`

En el codigo se usan principalmente estas clases:

- `Aws\DynamoDb\DynamoDbClient`
- `Aws\DynamoDb\Marshaler`

La configuracion e inyeccion de esta conexion esta en:

- [`app/Providers/AppServiceProvider.php`](c:\Users\oscar\Downloads\NOSQL\V1\laravel_app\app\Providers\AppServiceProvider.php)
- [`config/dynamodb.php`](c:\Users\oscar\Downloads\NOSQL\V1\laravel_app\config\dynamodb.php)

La implementacion del repositorio que consulta y guarda datos esta en:

- [`app/Infrastructure/DynamoDbUserRepository.php`](c:\Users\oscar\Downloads\NOSQL\V1\laravel_app\app\Infrastructure\DynamoDbUserRepository.php)

## Herramientas usadas

Estas son las herramientas y tecnologias principales usadas en el proyecto:

- Laravel 12 como framework backend
- PHP 8.2 como lenguaje principal del servidor
- Blade para la vista base
- React 18 para la interfaz del cliente
- JavaScript para la logica del frontend
- DynamoDB como base de datos NoSQL
- AWS SDK for PHP para la comunicacion con DynamoDB
- Vite como herramienta de build frontend
- Tailwind CSS como dependencia disponible para estilos
- Composer para la gestion de paquetes PHP
- npm para la gestion de paquetes JavaScript
- Artisan para comandos del framework
- PHPUnit para pruebas
- Laravel Pint para formateo de codigo

## Estructura del proyecto

```text
app/
  Application/
  Contracts/
  Domain/
  Http/Controllers/
  Infrastructure/
config/
public/
resources/views/
routes/
```

## Configuracion del entorno

1. Clona el proyecto.
2. Entra a la carpeta `laravel_app`.
3. Instala las dependencias:

```bash
composer install
npm install
```

4. Crea el archivo `.env` si no existe:

```bash
copy .env.example .env
```

5. Genera la clave de la aplicacion:

```bash
php artisan key:generate
```

6. Configura las variables para DynamoDB en el archivo `.env`.

Variables importantes:

```env
AWS_ACCESS_KEY_ID=fake
AWS_SECRET_ACCESS_KEY=fake
AWS_DEFAULT_REGION=us-east-1
DYNAMODB_ENDPOINT=http://localhost:8000
DYNAMODB_TABLE=MiMercado
```

Nota: la configuracion del proyecto lee `DYNAMODB_ENDPOINT` desde [`config/dynamodb.php`](c:\Users\oscar\Downloads\NOSQL\V1\laravel_app\config\dynamodb.php).

## Como correr el proyecto

1. Levanta DynamoDB Local en el puerto `8000`.
2. Ejecuta las migraciones de Laravel para los componentes relacionales auxiliares:

```bash
php artisan migrate
```

3. Carga los datos demo en DynamoDB:

```bash
php artisan mercado:seed-demo
```

4. Inicia el servidor:

```bash
php artisan serve
```

5. Abre en el navegador:

```text
http://127.0.0.1:8000
```

## Rutas disponibles

### Web

- `/`

### API

- `GET /api/usuarios/{userId}`
- `GET /api/usuarios/{userId}/pedidos`
- `GET /api/usuarios/{userId}/pedidos/{orderId}`

## Comando util del proyecto

Para crear la tabla y cargar datos de prueba en DynamoDB:

```bash
php artisan mercado:seed-demo
```

Este comando usa los datos definidos en:

- [`app/Application/SampleData.php`](c:\Users\oscar\Downloads\NOSQL\V1\laravel_app\app\Application\SampleData.php)

## Observaciones

- El flujo actual esta orientado principalmente a consulta de datos.
- La informacion principal de negocio vive en DynamoDB.
- Laravel tambien conserva configuraciones y componentes auxiliares que pueden usar SQLite segun el entorno.
