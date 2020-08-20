## Instalación Monitor Esmeralda :ship:

### Programas

- Instalar PHP >= 7.3
- Instalar Composer
- Instalar Git
- (opcional) Instalar MySql

#### Configurar

- Agregar todos los programas al PATH
- En php.ini habilitar (descomentar) extención gd2, mbstring, pdo_sqlite, fileinfo, ssl y no me acuerdo cual mas.

### Ejecutar en interprete de comandos

- $ git clone https://github.com/cl-ssi/monitor
- $ cd monitor
- $ cp .env.example .env (copy en vez de cp en windows)
- $ composer install
- $ php artisan key:generate

### Base de datos
- Editar archivo .env

#### Opción 1. Sqlite (más fácil)
- DB_CONNECTION=sqlite
- DB_DATABASE=C:\\\\Users\\\\Atorres\\\\monitor\\\\database\\\\database.sqlite
- Crear un archivo vacío en monitor\database\database.sqlite con el block de notas por ejemplo

#### Opción 2. MySql editar
- Configurar en .env los campos de base de datos, empiezan con DB_

### (Opcional) Agregar el cert.pem para integración con webservice de minsal
- Descargar https://curl.haxx.se/ca/cacert.pem
- En php.ini y agregar la ruta al archivo cacert.pem en la opción: curl.cainfo = "C:\\\\ruta\\\\cacert.pem"

### Georeferencia
- Para que funcione hay que poner en el archivo .env la api key de google maps y here.com

### Configurar un servidor de correo en .env
- Para que funcionen los correos a través del sistema hay que agregar, el servidor, usuario, clave, puerto, etc.

### Ejecutar los siguientes comandos
- $ php artisan migrate --seed   ( Para cargar la base de datos )
- $ php artisan serve    ( Para iniciar el servidor web )

### Abrir en navegador
- http://localhost:8000
- usuario: sistemas.ssi@redsalud.gob.cl
- clave: admin

## Para mantener actualizado el sistema
- $ git pull
- $ composer install
- $ php artisan migrate


## Para consultas e información sobre actualizaciones del sistema
- http://monitor-esmeralda.slack.com/
- esmeralda.ssi@redsalud.gob.cl

## Otros comandos
- $ php artisan migrate:fresh --seed  ( Restaura al base de datos al punto de partida )

## Si presenta algún error en archivos Seeder
- $ composer dump-autoload

## Contribución
- El sistema está abierto a quien quiera colaborar.
