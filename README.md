# Estructura y deseño del sistema

El sistema está desarrollado en Laravel version 12.0, siguiendo una arquitectura modular y desacoplada. Se implementaron patrones de diseño que facilitan la escalabilidad, mantenibilidad y reutilización del código.


## Capas del sistema:

- Controladores: Manejan las solicitudes HTTP y responden con JSON en los endpoints de la API REST.

- Servicios (Factories y Repositories): Encapsulan la lógica de negocio y la gestión de datos.

- Modelos y Eloquent ORM: Representan las entidades del sistema y manejan la persistencia en la base de datos.

- Migraciones y Seeders: Aseguran la estructura de la base de datos y permiten poblarla con datos de prueba.

- Pruebas Unitarias y de Integración: Implementadas con PHPUnit para garantizar la calidad del software.

# Decisiones de diseño

### 1 - Patrón MVC (Modelo-Vista-Controlador)
- ¿Por qué? Laravel implementa el patrón MVC, lo que permite organizar el código de manera estructurada y separar la lógica de negocio de la presentación.

Beneficios:
  - Mejora la organización y la mantenibilidad del código.
  - Permite una mejor separación de responsabilidades.
  - Facilita la escalabilidad del sistema.

### 2 - Patrón Repository:
- ¿Por qué? Se usó para desacoplar la lógica de acceso a datos de los controladores, facilitando cambios en la base de datos sin afectar la lógica de negocio.

Beneficios:
  - Mejora la organización y mantenibilidad.
  - Permite la inyección de dependencias y la implementación de pruebas unitarias. 

### 3 - Patrón Factory
- ¿Por qué? Se utilizó para estandarizar la creación de reservas sin depender directamente de la base de datos.

Beneficios:
  - Permite generar objetos de reserva de manera consistente.
  - Facilita la generación de datos de prueba en los tests.

### 4 - API RESTful
- ¿Por qué? Se diseñó la API siguiendo principios REST para asegurar la interoperabilidad y escalabilidad del sistema.

Beneficios:
 - Uso de verbos HTTP estándar (GET, POST, PUT, DELETE).
 - Respuestas estructuradas en JSON.

### 5 - Dockerización de la Aplicación
- ¿Por qué? Se decidió utilizar Docker para contenerizar la aplicación, asegurando que en los diferentes entorno de sean consistentes y portables.

Beneficios:
- Permite que la aplicación se ejecute en cualquier entorno sin depender de configuraciones específicas del sistema operativo.
- Facilita la configuración de dependencias como MySQL, Nginx etc... mediante Docker Compose.
- Mejora el despliegue y la escalabilidad en los diferentes entornos.

Implementación en el proyecto:

- Se creó un Dockerfile para definir la imagen de Laravel.
- Se configuró docker-compose.yml para gestionar contenedores de la aplicación, base de datos y servicios adicionales.
- Se aseguraron volúmenes persistentes para evitar la pérdida de datos entre reinicios.

# Instrucciones de configuración

### 1 - Clonar el repositorio
```sh
git clone https://github.com/oscarock/test-coco
```

### 2 - Ingresar a la carpeta del proyecto
```sh
cd test-coco
```

### 3 - Ingresar a la carpeta `test-coco` y modificar el archivo `.env` en la sección de configuración de la base de datos: 
```sh
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=secret
```

### 4 - Levantar el proyecto con Docker
```sh
docker compose up -d
```
Se crearán tres contenedores: app, nginx y base de datos.

### 5 - Verificar los contenedores que esten arriba
```sh
docker ps -a
```

### 6 - Ingresar al contenedor app y ejecutar las migraciones 
```sh
docker exec -it ID_CONTENEDOR bash
php artisan migrate
```

### 7 - Dentro del contenedor ejecutar los seed para crear los resources
```sh
docker exec -it ID_CONTENEDOR bash
php artisan db:seed --class=ResourceSeeder
```

### 8 - Acceder a la aplicación
Si no se cambiaron los puertos por defecto, las URLs serán:

- **API:** [localhost:85](http://localhost:85/)

### 9 - Coleccion del API guardar este script en un archivo e importarlo en postman
```sh
{
	"info": {
		"_postman_id": "c7cee916-76f0-49df-ad31-77fb208b30a6",
		"name": "test-coco",
		"schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json",
		"_exporter_id": "13576845"
	},
	"item": [
		{
			"name": "listar-recursos",
			"request": {
				"auth": {
					"type": "noauth"
				},
				"method": "GET",
				"header": [
					{
						"key": "Accept",
						"value": "application/json"
					}
				],
				"url": {
					"raw": "http://localhost:85/api/resources",
					"protocol": "http",
					"host": [
						"localhost"
					],
					"port": "85",
					"path": [
						"api",
						"resources"
					]
				}
			},
			"response": []
		},
		{
			"name": "consultar-disponibilidad",
			"request": {
				"auth": {
					"type": "noauth"
				},
				"method": "GET",
				"header": [
					{
						"key": "Accept",
						"value": "application/json"
					}
				],
				"url": {
					"raw": "http://localhost:85/api/resources/1/availability?reserved_at=2025-04-01T10:00:00&duration=60",
					"protocol": "http",
					"host": [
						"localhost"
					],
					"port": "85",
					"path": [
						"api",
						"resources",
						"1",
						"availability"
					],
					"query": [
						{
							"key": "reserved_at",
							"value": "2025-04-01T10:00:00"
						},
						{
							"key": "duration",
							"value": "60"
						}
					]
				}
			},
			"response": []
		},
		{
			"name": "crear-reserva",
			"request": {
				"auth": {
					"type": "noauth"
				},
				"method": "POST",
				"header": [
					{
						"key": "Content-Type",
						"value": "application/json"
					}
				],
				"body": {
					"mode": "raw",
					"raw": "{\n    \"resource_id\": 1,\n    \"reserved_at\": \"2025-04-01T10:00:00\",\n    \"duration\": 60\n}",
					"options": {
						"raw": {
							"language": "json"
						}
					}
				},
				"url": {
					"raw": "http://localhost:85/api/reservations",
					"protocol": "http",
					"host": [
						"localhost"
					],
					"port": "85",
					"path": [
						"api",
						"reservations"
					]
				}
			},
			"response": []
		},
		{
			"name": "cancelar-reserva",
			"request": {
				"auth": {
					"type": "noauth"
				},
				"method": "DELETE",
				"header": [
					{
						"key": "Accept",
						"value": "application/json"
					}
				],
				"url": {
					"raw": "http://localhost:85/api/reservations/2",
					"protocol": "http",
					"host": [
						"localhost"
					],
					"port": "85",
					"path": [
						"api",
						"reservations",
						"2"
					]
				}
			},
			"response": []
		}
	]
}
```