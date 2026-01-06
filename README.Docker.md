# Docker Setup for Nouni Laravel Project

This guide will help you run the Nouni Laravel application using Docker on any device.

## Prerequisites

- Docker installed on your machine ([Download Docker](https://www.docker.com/get-started))
- Docker Compose installed (usually comes with Docker Desktop)

## Quick Start

### 1. Clone the Repository

```bash
git clone <your-repository-url>
cd Nouni_Project
```

### 2. Setup Environment File

Copy the Docker environment file:

```bash
cp .env.docker .env
```

Or manually copy `.env.example` to `.env` and update these values:

```env
DB_HOST=db
DB_DATABASE=nouni_db
DB_USERNAME=nouni_user
DB_PASSWORD=root

REDIS_HOST=redis

APP_URL=http://localhost:8080
```

### 3. Build and Start Containers

```bash
docker-compose up -d --build
```

This will:
- Build the application image
- Start MySQL, Redis, Nginx, and PHP-FPM containers
- Install all dependencies
- Set up the database

### 4. Run Database Migrations

```bash
docker-compose exec app php artisan migrate --seed
```

### 5. Create Storage Link

```bash
docker-compose exec app php artisan storage:link
```

### 6. Set Permissions (if needed)

```bash
docker-compose exec app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
docker-compose exec app chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
```

## Access the Application

- **Application**: http://localhost:8080
- **PhpMyAdmin**: http://localhost:8081
  - Server: `db`
  - Username: `nouni_user`
  - Password: `root`

## Useful Docker Commands

### View Running Containers
```bash
docker-compose ps
```

### View Logs
```bash
docker-compose logs -f
docker-compose logs -f app  # For specific service
```

### Stop Containers
```bash
docker-compose down
```

### Stop and Remove Volumes
```bash
docker-compose down -v
```

### Restart Containers
```bash
docker-compose restart
```

### Execute Commands in Container
```bash
docker-compose exec app php artisan <command>
docker-compose exec app composer install
docker-compose exec app npm run dev
```

### Access Container Shell
```bash
docker-compose exec app bash
```

### Clear Laravel Cache
```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

## Services

### App (PHP-FPM)
- Container: `nouni_app`
- PHP version: 8.2
- Extensions: PDO, MySQL, GD, Zip, etc.

### Nginx
- Container: `nouni_nginx`
- Port: 8080 (mapped to container port 80)

### MySQL
- Container: `nouni_db`
- Port: 3307 (mapped to container port 3306)
- Database: `nouni_db`
- Username: `nouni_user`
- Password: `root`

### Redis
- Container: `nouni_redis`
- Port: 6380 (mapped to container port 6379)

### PhpMyAdmin
- Container: `nouni_phpmyadmin`
- Port: 8081

## Development Workflow

### Install New Composer Package
```bash
docker-compose exec app composer require <package-name>
```

### Install New NPM Package
```bash
docker-compose exec app npm install <package-name>
```

### Build Assets
```bash
docker-compose exec app npm run build
```

### Watch Assets (Development)
```bash
docker-compose exec app npm run dev
```

### Run Tests
```bash
docker-compose exec app php artisan test
```

## Troubleshooting

### Port Already in Use
If ports 8080, 8081, 3307, or 6380 are already in use, edit `docker-compose.yml` and change the host port:

```yaml
ports:
  - "9090:80"  # Change 8080 to 9090
```

### Permission Issues
```bash
docker-compose exec app chown -R www-data:www-data /var/www/html
docker-compose exec app chmod -R 775 /var/www/html/storage
```

### Database Connection Issues
Make sure the database service is running:
```bash
docker-compose ps db
```

Check database logs:
```bash
docker-compose logs db
```

### Rebuild from Scratch
```bash
docker-compose down -v
docker-compose up -d --build
docker-compose exec app php artisan migrate:fresh --seed
```

## Production Considerations

For production deployment:

1. Update `.env` file with production values
2. Set `APP_DEBUG=false`
3. Set `APP_ENV=production`
4. Use proper database credentials
5. Configure SSL/TLS for Nginx
6. Remove PhpMyAdmin service from docker-compose.yml
7. Optimize autoloader:
   ```bash
   docker-compose exec app composer install --optimize-autoloader --no-dev
   ```
8. Cache configuration:
   ```bash
   docker-compose exec app php artisan config:cache
   docker-compose exec app php artisan route:cache
   docker-compose exec app php artisan view:cache
   ```

## Support

For issues or questions, please open an issue on the repository.
