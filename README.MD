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
     8. Optional if you need phpmyadmin run ``docker compose up -d phpmyadmin``