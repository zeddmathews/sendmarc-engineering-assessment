# Laravel + Vue Tennis Scoring System

This is an overengineered Laravel + Vue.js application for tracking live tennis matches between two players.

## Features

- Create & manage player profiles
- Score live tennis games with proper rules (Love → Forty, Deuce, Advantage)
- Modal on match completion
- Dashboard with player insights
- Background job to update stats
- Admin seeded on first run
- Secure validation & CSRF protection
- Dockerized setup (PostgreSQL, Redis)

## Docker Setup

1. Clone the repo  
   `git clone https://github.com/zeddmathews/sendmarc-engineering-assessment`

2. Copy the `.env`  
   `cp .env.example .env`

3. Build & run  
   `docker compose up --build`

## Tech Stack

- Laravel 11 (PHP 8.3)
- Vue 3 + Vite
- PostgreSQL
- Redis (queues & caching)
- Docker & Docker Compose

## 🔐 Security Highlights

- CSRF Protection
- Input validation via Form Requests
- CSP headers
- Auth roles (admin, player)

---

## 📜 License

MIT License