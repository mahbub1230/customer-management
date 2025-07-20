# Customer Management - Laravel 11 + Vue 3 + Docker

This is a Laravel 11 project integrated with Vue 3 and Docker. It uses PHP 8.2, Apache, MySQL 8.0, and Vite for asset bundling.

---

## ✅ Prerequisites

- Docker installed: https://www.docker.com/products/docker-desktop
- Git installed: https://git-scm.com
- (Optional) Composer and Node.js (for local, non-Docker workflows)

---

## 🚀 First-Time Setup (Local + Docker)

Follow these steps after cloning the repo or when setting up the project for the first time:

### 1. Clone the repository

```bash
git clone https://github.com/mahbub1230/customer-management.git
cd customer-management
```

### 2. Start Docker containers (build if needed)

```bash
docker-compose up --build -d
```

### 3. Enter the app container

```bash
docker exec -it laravel_app bash
```

### 4. Install Laravel & frontend dependencies

```bash
composer install
npm install
npm run build
```

### 5. Set up `.env` and application key

```bash
cp .env.example .env
php artisan key:generate
```

### 6. Configure database connection in `.env`

Use the following DB values in `.env`:

```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=customer_management
DB_USERNAME=root
DB_PASSWORD=root
```

### 7. Update APP_URL in `.env`

APP_URL=https://demo.slipstram.com

## 🌐 Access via Custom Domain: https://demo.slipstram.com

To run this project under `https://demo.slipstram.com` locally using Docker with HTTPS:

### 7.1. Edit your hosts file

Add the following line to your system's `hosts` file:

```
127.0.0.1 demo.slipstram.com
```

- **Windows**: `C:\Windows\System32\drivers\etc\hosts`
- **Mac/Linux**: `/etc/hosts`

### 7.2. Use the preconfigured Docker setup

This project includes a Dockerfile and startup script that will automatically generate a self-signed SSL certificate on container startup.

No manual cert generation is required.

### 7.3. Build and run the containers

```bash
docker-compose down
docker-compose up --build -d
```

> The entrypoint script will create the certificate and key in `/etc/apache2/ssl/` if they do not already exist.


### 8. Run migrations

```bash
php artisan migrate
```

### 8. Optional: Fix permissions

```bash
chown -R www-data:www-data /var/www/html
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 🌐 Access the Application

### 9. Open the app in your browser

```
https://demo.slipstram.com
```

> ⚠️ The browser may warn about the self-signed certificate. Accept it to proceed.

---

## 📄 SSL Notes

- SSL certs are generated inside the container at runtime.
- You can customize the cert generation by editing `docker/scripts/generate-ssl.sh`.
- Apache reads the cert from `/etc/apache2/ssl/ssl.crt` and `/ssl.key`.


---

## 🔁 Subsequent Usage

When restarting or continuing development:

### Start the containers

```bash
docker-compose up -d
```

### Stop the containers

```bash
docker-compose down
```

### Re-enter the container if needed

```bash
docker exec -it laravel_app bash
```

---

## ⚙️ Laravel/Vue Scripts

Run these inside the container or locally if dependencies are installed:

```bash
# Laravel
php artisan migrate
php artisan serve

# Vue/Vite
npm run dev
npm run build
```

## 📂 Project Structure

```
customer-management/
├── app/                    # Laravel application root
├── docker/
│   ├── apache/             # Apache config
│   └── Dockerfile          # PHP + Apache Dockerfile
├── docker-compose.yml      # Docker Compose config
├── .env                    # Laravel env config
├── README.md               # This file
```



