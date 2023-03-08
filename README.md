# da4fy-familes

Create project:

    composer create-project --prefer-dist laravel/lumen da4fy-users

Run project:

    php -S localhost:8002 -t public

Create migration:

    php artisan make:migration create_users_table

Run migrations:

    php artisan migrate

Rollback migration:

    php artisan migrate:rollback

Create seeder:

    php artisan make:seeder UserSeeder

Run seeder:

    php artisan db:seed
