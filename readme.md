# Laravel Checkout Project Setup

## Installation Steps

### Method 1: Fresh Laravel 9 Installation (Recommended)

Starting with a fresh Laravel installation is the most reliable approach:

1. Move to the parent directory and create a backup:
   ```
   cd c:\xampp\htdocs
   rename checkout checkout_backup
   ```

2. Create a fresh Laravel 9 project:
   ```
   composer create-project --prefer-dist laravel/laravel:^9.0 checkout
   ```

3. Navigate to the new project directory:
   ```
   cd checkout
   ```

4. Copy your custom files from backup (adjust as needed):
   ```
   xcopy /E /H /C /I /Y ..\checkout_backup\app app
   xcopy /E /H /C /I /Y ..\checkout_backup\database\migrations database\migrations
   xcopy /E /H /C /I /Y ..\checkout_backup\resources\views resources\views
   xcopy /E /H /C /I /Y ..\checkout_backup\routes routes
   ```

5. Generate the application key:
   ```
   php artisan key:generate
   ```

6. Run database migrations:
   ```
   php artisan migrate
   ```

7. (Optional) Seed the database with example data:
   ```
   php artisan db:seed
   ```

### Method 2: Fix Existing Installation

If you prefer to fix your existing installation:

1. Update dependencies without running scripts:
   ```
   cd c:\xampp\htdocs\checkout
   composer update --no-scripts
   ```

2. Create the required Laravel directory structure:
   ```
   mkdir -p app/Http/Controllers app/Models bootstrap/cache config database/migrations public resources/views routes storage/app storage/framework/cache storage/framework/sessions storage/framework/views storage/logs
   ```

3. Set proper permissions on storage directory:
   ```
   icacls storage /grant Everyone:(OI)(CI)F
   ```

4. Create a basic .env file:
   ```
   copy .env.example .env
   ```

5. Generate the application key:
   ```
   php artisan key:generate
   ```

## Troubleshooting

### "Script @php artisan package:discover handling returned with error code 255"
This error occurs when Laravel can't run artisan commands because of incomplete application structure.

Solution:
1. Try running `composer update --no-scripts` first
2. Make sure you have all the required Laravel directories
3. If the issue persists, use Method 1 (fresh Laravel installation)

### Dependency Issues
If you encounter errors with composer dependencies:
1. Try updating composer: `composer update --no-scripts`
2. For version issues: `composer require laravel/framework:^9.0 --no-scripts`

### Missing Autoload File
If the autoload.php file is missing:
1. Run `composer dump-autoload`
2. If that doesn't work, follow the fresh installation steps above

### Required Folder Structure
Ensure you have a proper Laravel folder structure with:
- app/
- bootstrap/
- config/
- database/
- public/
- resources/
- routes/
- storage/
- vendor/ (created by composer install)