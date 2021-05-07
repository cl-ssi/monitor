Guía usando contenedores
===

## Introducción

En esta guía es para indicar los pasos de instalación para poder utilizar la infraestructura
definida en el archivo docker-compose.yml. 

## Requisitos

Para poder la infraestructura es necesario utilizar docker y docker compose,
es necesario lo siguiente:

- Instalar docker ([Windows](https://docs.docker.com/docker-for-windows/install/), [MacOS](https://docs.docker.com/docker-for-mac/install/) y [Linux](https://docs.docker.com/engine/install/))
- Instalad docker composer (Solo [Linux](https://docs.docker.com/compose/install/))

## Bootstrap

Una vez instalado el docker compose ejecutar las siguientes lineas de comando en el directorio
donde se descargó el código fuente del monitor (Para las variables de entornos que no están 
indicadas en el archivo docker-compose.yml definirlas para su entorno):

```bash
$docker-compose up -d --build nginx
$docker-compose exec php composer install
$cp .env.example .env 
$docker-compose exec php php artisan key:generate
$docker-compose exec php php artisan migrate --force
$docker-compose exec php php artisan db:seed --force
$docker-compose up -d horizon
```

Con estas líneas ya se tiene el entorno ejecutado localmente, y para acceder al 
monitor usando la url [http://localhost:8181](http://localhost:8181)

##Visualizar ejecución de los contenedores

Para ver el log o información por consola puede usar los siguientes comando

```bash
$docker-compose logs -f php
```

Para ver si se esta ejecutando toda la infraestructura
```bash
$docker-compose ps
```

##Detener la ejecución

Para detener la ejecución sin eliminar los contenedores ya creado 
```bash
$docker-compose down
```

Para detener y eliminar todo los contenedores creados. Con esto es necesario volver a ejecutar
los pasos de bootstrap.
```bash
$docker-compose down --rmi all --delete-orphans -v
```

##Agregar un nuevo paquete en laravel

Para instalar una nueva librería en el proyecto se debe usar
```bash
$docker-compose exec php compose require {paquete}
```

##Problemas

En linux generalmente los directorios creados dentro de los contenedores en el host quedan
como dueño el root impidiendo la creación de los archivos de cache del storage. Para ello
ejecutar como administrador la siguiente línea de comando

```bash
#chown -R user.user storage
#chmod -R 777 storage 
```

Donde user debe ser el usuario de sesion con que se ingresa en linux

##Bonus track

El proyecto está configurado para ejecutar con xdebug, así que es posible usar cualquier ide
que soporte el trazado de la aplicación. Además, está configurado para usarse en PhpStorm
