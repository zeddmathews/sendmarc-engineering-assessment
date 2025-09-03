# Laravel + Vue Tennis Scoring System

This is a Laravel + Vue.js application for tracking live tennis matches between two players.

## Build process
**Depends on Docker engine**
- Clone the repo 
- Copy the `.env` file from `.env.example`  
- `cd app`
- Run `npm install` locally (outside Docker) to install frontend dependencies  
- Start the Vite development server locally with `npm run dev`
- `cd ..`
- `chmod +x scripts/01-clean_start.sh`
- `cd scripts`
- `./01-clean_start.sh`
**For build without docker**
- Update .env `DB_HOST=db` -> `DB_HOST=127.0.0.1`
- `cd app`
- `psql -U postgres`
- `CREATE DATABASE tennis;`
- `php artisan migrate`
- `php artisan db:seed`
- `composer run dev`
**Testing - local**
- `php artisan test tests/Feature/GameManagementTest.php tests/Feature/PlayerManagementTest.php tests/Unit/GameServiceTest.php tests/Feature/AuthTest.php`
**Testing - container**
- `docker-compose exec db psql -U postgres`
- `CREATE DATABASE tennis_test;`
- `docker-compose exec tennis-php-fpm bash`
- `php artisan test tests/Feature/GameManagementTest.php tests/Feature/PlayerManagementTest.php tests/Unit/GameServiceTest.php tests/Feature/AuthTest.php`

## Docker Setup
1. Clone the repo  
   `git clone https://github.com/zeddmathews/sendmarc-engineering-assessment`
2. Copy the `.env`  
   `cp .env.example .env`
3. Build & run  
   `docker compose up --build`
4. Access
   `http://localhost:9000/`

## Tech Stack

- Laravel 11 (PHP 8.3)
- Vue 3 + Vite
- PostgreSQL
- Docker & Docker Compose
- Nginx


---

## License

MIT License