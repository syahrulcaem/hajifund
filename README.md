# Laravel Project with PostgreSQL Database

## Setup Instructions

Follow these steps to set up your Laravel project using a PostgreSQL database.

### 1. Install Dependencies
Make sure you have the following installed:
- PHP (>= 8.0)
- Composer
- Laravel
- PostgreSQL

### 2. Configure Environment Variables
Edit the `.env` file in your Laravel project and set up the database connection as follows:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

Replace `your_database_name`, `your_database_user`, and `your_database_password` with your actual PostgreSQL database credentials.

### 3. Install Laravel Dependencies
Run the following command to install Laravel dependencies:

```
composer install
```

### 4. Generate Application Key
Run this command to generate a new application key:

```
php artisan key:generate
```

### 5. Run Migrations
Migrate the database schema with:

```
php artisan migrate
```

### 6. Start Laravel Server
Start the local development server with:

```
php artisan serve
```

Your Laravel application should now be running with a PostgreSQL database.

### Additional Notes
- If using Docker, ensure your PostgreSQL container is running and accessible.
- Use `php artisan config:cache` after changing `.env` to apply changes.
- Install `pgAdmin` or use `psql` CLI to manage the PostgreSQL database.

---

This README provides essential steps to configure Laravel with PostgreSQL. Adjust the configuration based on your server setup.

