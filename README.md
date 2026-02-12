# Insider One – Champions League Simulation

Insider One is a small-scale league simulation app that plays weekly matches, calculates standings dynamically, and estimates championship probabilities in the last 3 weeks using Monte Carlo simulation.

- **Live Demo:** https://insider-one-league.furkanadiguzel.com/
- **Dashboard:** https://insider-one-league.furkanadiguzel.com/dashboard

---

## About

This project is built to demonstrate a **clean, testable, and extendable architecture** rather than only producing match results.

- **Backend:** Laravel  
- **Frontend:** Vue 3  
- **Architecture:** Service Pattern + DTO  
- **Prediction:** Monte Carlo (last 3 weeks only)

---

## Tech Stack

- Laravel
- Vue 3
- Vite
- Service Layer
- DTOs
- Monte Carlo Simulation
- PHPUnit
- Docker

---

## Architecture

- **Controllers** handle only HTTP request/response.
- **Service layer** contains all business logic.
- **DTOs** standardize the response structure.
- **Simulation** and **Prediction** are designed as separate services.
- **Standings are not persisted**; they are calculated from match results every time.

This keeps the codebase readable, testable, and easy to extend.

---

## Features

- Team CRUD (create / update / delete)
- Round-robin fixture generation
- Play next week
- Play full season
- Edit match score
- Automatic standings recalculation after score changes
- Championship probability (Monte Carlo) in the last 3 weeks

---

## Simulation Logic

### Standings (Deterministic)

- Calculated from played match scores
- 3 points for win, 1 point for draw
- Sorting priority:
  1. Points
  2. Goal difference
  3. Goals scored

### Match Simulation

- Uses team **power** values
- Generates scores probabilistically
- Includes a small home advantage
- Randomized (not deterministic)

### Prediction (Monte Carlo)

- Enabled only in the **last 3 weeks**
- Remaining matches are simulated thousands of times
- A champion is determined each iteration
- Final result is a percentage chance per team

---

## Local Setup

```bash
### 1) Clone


git clone <repo-url>
cd insider-one-league

2) Backend
composer install
cp .env.example .env
php artisan key:generate
Configure your database in .env, then run migrations:

php artisan migrate
SQLite (optional)
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
touch database/database.sqlite
php artisan migrate
3) Frontend
npm install
npm run dev
Production build:

npm run build
4) Run
php artisan serve
Open:

http://localhost:8000/dashboard

Docker
docker compose up -d --build
docker compose exec app php artisan migrate
Tests
php artisan test
Covers:

Full simulation flow

Recalculation after score edits

Prediction validation (last 3 weeks)

API endpoint verification

API Endpoints
GET    /api/league
GET    /api/teams
POST   /api/teams
PATCH  /api/teams/{teamId}
DELETE /api/teams/{teamId}

GET    /api/fixtures

POST   /api/simulation/generate-fixtures
POST   /api/simulation/play-next-week
POST   /api/simulation/play-all
POST   /api/simulation/reset
PATCH  /api/simulation/matches/{matchId}
Notes
Default league setup is based on a 6-week season.

Monte Carlo iteration count is selected with a performance/accuracy balance.

UI is intentionally minimal; architecture is the main focus.

In production, make sure Vite build exists (public/build/manifest.json).

Copyright
© 2026 Furkan Adıgüzel
https://furkanadiguzel.com/
