# Project Title: Laravel Resource Reservation API

## System Structure and Design

This system is developed in Laravel version 12.0, following a modular and decoupled architecture. Design patterns have been implemented to facilitate scalability, maintainability, and code reusability.

## System Layers:

-   **Controllers**: Handle HTTP requests and respond with JSON at the REST API endpoints.
-   **Services (Factories and Repositories)**: Encapsulate business logic and data management.
-   **Models and Eloquent ORM**: Represent system entities and handle database persistence.
-   **Migrations and Seeders**: Ensure database structure and allow populating it with test data.
-   **Unit and Integration Tests**: Implemented with PHPUnit to guarantee software quality.

## Design Decisions

### 1. MVC (Model-View-Controller) Pattern
-   **Why?** Laravel implements the MVC pattern, which allows for structured code organization and separation of business logic from presentation.
-   **Benefits**:
    -   Improves code organization and maintainability.
    -   Allows for better separation of responsibilities.
    -   Facilitates system scalability.

### 2. Repository Pattern
-   **Why?** Used to decouple data access logic from controllers, making it easier to change the database without affecting business logic.
-   **Benefits**:
    -   Improves organization and maintainability.
    -   Enables dependency injection and implementation of unit tests.

### 3. Factory Pattern
-   **Why?** Used to standardize the creation of reservations without directly depending on the database.
-   **Benefits**:
    -   Allows for consistent generation of reservation objects.
    -   Facilitates the generation of test data in tests.

### 4. RESTful API
-   **Why?** The API was designed following REST principles to ensure system interoperability and scalability.
-   **Benefits**:
    -   Use of standard HTTP verbs (GET, POST, PUT, DELETE).
    -   Structured responses in JSON.

### 5. Application Dockerization
-   **Why?** Docker was chosen to containerize the application, ensuring consistent and portable environments.
-   **Benefits**:
    -   Allows the application to run in any environment without depending on specific operating system configurations.
    -   Facilitates the configuration of dependencies like MySQL, Nginx, etc., using Docker Compose.
    -   Improves deployment and scalability across different environments.
-   **Project Implementation**:
    -   A `Dockerfile` was created to define the Laravel image.
    -   `docker-compose.yml` was configured to manage application containers, database, and additional services.
    -   Persistent volumes were ensured to prevent data loss between restarts.

## Configuration Instructions

### 1. Clone the repository
```sh
git clone https://github.com/oscarock/test-coco
```

### 2. Navigate to the project folder
```sh
cd test-coco
```

### 3. Modify the `.env` file
Navigate into the `test-coco` folder and modify the database configuration section in the `.env` file (you might need to copy `.env.example` to `.env` first if it doesn't exist):
```ini
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=secret
```

### 4. Start the project with Docker
```sh
docker compose up -d
```
This will create three containers: `app`, `nginx`, and `db` (database).

### 5. Verify that the containers are running
```sh
docker ps -a
```

### 6. Access the `app` container and run migrations
Replace `ID_CONTAINER` with the actual ID or name of your `app` container (e.g., `test-coco-app-1`).
```sh
docker exec -it ID_CONTAINER bash
php artisan migrate
```

### 7. Inside the container, run the seeders to create resources
Still inside the `app` container (if you exited, use `docker exec -it ID_CONTAINER bash` again):
```sh
php artisan db:seed --class=ResourceSeeder
```

### 8. Access the application
If default ports were not changed, the URLs will be:

-   **API**: [http://localhost:85/](http://localhost:85/)

### 9. Postman API Collection
Save the following JSON script into a file (e.g., `test-coco.postman_collection.json`) and import it into Postman:

```json
{
	"info": {
		"_postman_id": "c7cee916-76f0-49df-ad31-77fb208b30a6",
		"name": "test-coco",
		"schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json",
		"_exporter_id": "13576845"
	},
	"item": [
		{
			"name": "list-resources",
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
			"name": "check-availability",
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
			"name": "create-reservation",
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
			"name": "cancel-reservation",
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