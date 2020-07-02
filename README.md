# Cafe - Management - System CMS
This is a CMS to manage a cafe

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/7.x/installation)

Install all the dependencies using composer
    
    `composer install`
    
Copy the example env file and make the required configuration changes in the .env file
    
    `cp .env.example .env`
    
Generate a new application key
    
    `php artisan key:generate`
    
Generate a new JWT authentication secret key
    
    `php artisan jwt:secret`
    
Run the database migrations (**Set the database connection in .env before migrating**)
**Make sure you set the correct database connection information before running the migrations**
    
    `php artisan migrate`
    
    
Start the local development server

    php artisan serve
    
Now you can access the server at http://localhost:8000

## Database seeding

Run the database seeder and you're done

    php artisan db:seed
    
***Note*** : It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running the following command
    
    php artisan migrate:refresh
    
 ## Dependencies
 - [jwt-auth](https://github.com/tymondesigns/jwt-auth) - For authentication using JSON Web Tokens

