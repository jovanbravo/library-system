### Book Library System
##### tech: Laravel
#### Setup:
- Clone the project from repo
- Inside ``/etc/hosts`` add ``127.0.0.1 library-book.devel``
- Open the right project location from the terminal and follow these steps:
     1. Set up docker environments: 'cp .env.example .env'
     2. Build project: ``docker compose build --no-cache``
     3. Setup project environments: ``cp ./src/.example.env ./src/.env``
     4. Install Laravel project: ``docker compose run --rm composer install``
     5. Run migrations: ``docker compose run --rm artisan migrate``
     6. Generate App Key: ``docker compose run --rm artisan key:generate``
     7. Start servers: ``docker compose up -d nginx``
     8. Optional if you need phpmyadmin run ``docker compose up -d phpmyadmin`` and open in browser ``http://library-book.devel:8090``
     9. Run command ``sh php_artisan.sh`` and inside container start reverb with this command ``php artisan reverb:start``
    10. Run command ``sh php_artisan.sh`` and inside container start queues with this command ``php artisan queue:work``

-----------------------------------------------------------------------------------------------
#### Seeding Database
- Run database seeder
  - ``docker compose run --rm artisan db:seed``
-----------------------------------------------------------------------------------------------
#### Testing
- To run tests and check the application, follow these steps:
    1. Login to MySQL as root and create a test database with name ``lib_book_test``
    2. Run tests with command ``docker compose run --rm artisan test`` 
