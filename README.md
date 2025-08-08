# Laravel + Vue Tennis Scoring System

This is a Laravel + Vue.js application for tracking live tennis matches between two players.

## Features

- Create & manage player profiles
- Score live tennis games with proper rules (Love → Forty, Deuce, Advantage)
- Modal on match completion
- Dashboard with player insights
- Background job to update stats
- Admin seeded on first run
- Secure validation & CSRF protection
- Dockerized setup (PostgreSQL, Redis)

## Build process
**Depends on Docker engine**
**Install mutagen for live sync**

- Clone the repo 
- Copy the `.env` file from `.env.example`  
- `cd app`
- Run `npm install` locally (outside Docker) to install frontend dependencies  
- Start the Vite development server locally with `npm run dev`
- `cd ..`
- `chmod +x scripts/01-clean_start.sh`
- `cd scripts`
- `./01-clean_start.sh`

**Testing**
- `./vendor/bin/pest --init` _pest initialization_
- `./vendor/bin/pest` _run tests_
## Notes

- Current config build from docker-compose and points to entrypoint.sh to run migrations. Need to add full reset scripts, start, stop, wipe
- Need to build seeders into docker-compose (add a couple default players to test game logic)
- Modify db migrations to include player data

## Checklist (remove later)
**Models & Migrations**
- Player model + migration
   - Done
- Game model + migration
   - Done
- PlayerStats model + migration
   - Done
- Seed admin player on startup
   - Done
- Add factories for test data
   - 
**Controllers**
- PlayerController
   - Create/read player profiles
- GameController
   - Create games, view game state
- PlayerStatsController
   - View per-player and per-game stats
- SimulationController
   - Simulate gameplay: serves, points, random events
- DashboardController
   - Show match history, recommendations
- AuthController (optional)
   - Admin login/logout if needed
**Frontend**
- Match gameplay UI (serve, win point, display stats)
- Modal for when a game ends
- Dashboard view with top stats or recommendations
- create player, create game
**Backend Logic**
- Game simulation logic 
   - (aces, faults, scoring)
- End-game detection 
   - (15-30-40, deuce, winner)
- Background job to calculate complex stats
   - 
- Redis caching for dashboard recommendations
   - 
**Security & Validation**
- CSRF protection
   - (enabled by default in Laravel, implement tokens on form submissions)
- CSP headers
   - (via middleware or Laravel CSP package)
- Validation rules for all input
   - e.g. Form Requests
- admin-only access
   - 
**Docker Setup**
- Laravel container
   - Done
- PostgreSQL container
   - Done
- Vue.js container
   - Done
- Nginx container
   - Done
- Redis container
   - 
- Mailpit container 
   - for testing emails
- Queue worker container
   - maybe
**Testing**
- Unit tests for models and logic
- Feature tests for gameplay flow
- Test Redis-cached dashboard stats
- Test emails using Mailpit
**Maybe add if enough time**
- WebSocket support for live scoring
   - Technically might be a requirement but we'll have to see


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
- Redis (queues & caching)
- Docker & Docker Compose

## Security Highlights

- CSRF Protection
- Input validation via Form Requests
- CSP headers
- Auth roles (admin, player)

---

## License

MIT License

clean start script
├─ port check
├─ docker compose down (stop/remove containers & volumes)
├─ chmod scripts
├─ docker compose build (Dockerfile builds)
│   ├─ FROM php:8.4-fpm base image
│   ├─ RUN commands (install deps, PHP extensions, copy files)
│   ├─ COPY config + entrypoint
│   ├─ set ENTRYPOINT, expose port
│
├─ docker compose up -d db (start postgres container)
│   └─ postgres server process starts inside container
│
├─ wait loop: docker exec postgres pg_isready (wait for postgres readiness)
│
├─ docker compose run --rm seed (run migrations + seeding)
│   └─ php artisan migrate + db:seed commands inside seed container
│
├─ docker compose up -d app (start app container)
│   ├─ entrypoint.sh runs inside container:
│   │   ├─ composer install if needed
│   │   ├─ copy .env if needed
│   │   ├─ php artisan cache/config clear
│   │   ├─ php-fpm start (daemon mode)
│   │   └─ nginx start (foreground)
│
└─ echo "Starting services"
