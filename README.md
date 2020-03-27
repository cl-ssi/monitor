## Instalación

### Programas

- Instalar PHP >= 7.3
- Instalar Composer
- Instalar Git
- (opcional) Instalar MySql

#### Configurar

- Agregar todos los programas al PATH
- En php.ini habilitar extención gd, mbstring, pdo_sqlite y no me acuerdo cual mas
-

### Ejecutar en interprete de comandos

- $ git clone https://github.com/cl-ssi/monitor
- $ cd monitor
- $ cp .env.example .env (copy en vez de cp en windows)
- $ php artisan key:generate

### Base de datos
- Editar archivo .env

#### 1. Sqlite (más fácil)
- DB_CONNECTION=sqlite
- DB_DATABASE=C:\\Users\\Atorres\\monitor\\database\\database.sqlite
- Crear un archivo vacío en monitor\database\database.sqlite con el block de notas por ejemplo

#### 2. MySql editar
- Configurar en .env los campos de base de datos, empiezan con DB_

### Ejecutar los siguientes comandos
- $ php artisan migrate --seed   ( Para cargar la base de datos )
- $ php artisan serve    ( Para iniciar el servidor web )


### Abrir en navegador
- http://localhost:8000
- usuario: sistemas.ssi@redsalud.gob.cl
- clave: admin
