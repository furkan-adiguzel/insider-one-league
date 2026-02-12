<p align="center"> <h1 align="center">Insider One – Champions League Simulation</h1> <p align="center"> Monte Carlo powered league simulation built with Laravel & Vue 3 </p> </p> <p align="center"> <a href="https://insider-one-league.furkanadiguzel.com/" target="_blank"> <img src="https://img.shields.io/badge/Live-Demo-success?style=for-the-badge" alt="Live Demo"> </a> <img src="https://img.shields.io/badge/Laravel-10-red?style=for-the-badge" alt="Laravel"> <img src="https://img.shields.io/badge/Vue-3-brightgreen?style=for-the-badge" alt="Vue 3"> <img src="https://img.shields.io/badge/Docker-Enabled-blue?style=for-the-badge" alt="Docker"> <img src="https://img.shields.io/badge/Tests-PHPUnit-informational?style=for-the-badge" alt="PHPUnit"> </p>
🌐 Live Demo

You can explore the running application here:

Main Site:
https://insider-one-league.furkanadiguzel.com/

Dashboard:
https://insider-one-league.furkanadiguzel.com/dashboard

📖 About The Project

Insider One is a league simulation engine that:

Simulates weekly football matches

Calculates standings dynamically

Recalculates rankings after score edits

Runs Monte Carlo prediction during the last 3 weeks

Displays championship probability percentages

The goal of this project is not only to simulate matches, but to demonstrate:

Clean architecture

Separation of concerns

Testable service layer

Extendable simulation logic

🏗️ Architecture

This project follows a layered architecture:

Controller Layer → Handles only HTTP requests & responses

Service Layer → Contains all business logic

DTO Layer → Standardizes API response structures

Simulation Service → Match logic abstraction

Prediction Service → Monte Carlo engine

No direct business logic exists inside controllers.

Standings are always calculated dynamically — never persisted.

⚙️ Features

Team CRUD

Round-robin fixture generation

Play next week

Play full season

Score editing

Automatic standings recalculation

Monte Carlo championship prediction (last 3 weeks only)

Full API support

Unit & feature tests

🧠 Simulation Logic
Deterministic Standings

3 points for win

1 point for draw

Ranking priority:

Points

Goal difference

Goals scored

Match Simulation

Based on team power rating

Probabilistic scoring

Slight home advantage

Randomized outcomes

Monte Carlo Prediction

Activated only during last 3 weeks

Remaining matches simulated thousands of times

Champion calculated for each iteration

Probability percentage returned per team

Statistical sampling is used instead of brute-force combinations for performance efficiency.

🚀 Local Installation
1. Clone Repository
git clone <repo-url>
cd insider-one-league

2. Install Backend
composer install
cp .env.example .env
php artisan key:generate


Configure database inside .env.

Run migrations:

php artisan migrate

3. Install Frontend
npm install
npm run dev


For production build:

npm run build

4. Run Application
php artisan serve


Open:

http://localhost:8000/dashboard

🐳 Docker
docker compose up -d --build
docker compose exec app php artisan migrate

🧪 Run Tests
php artisan test


Test coverage includes:

Full simulation flow

Score edit recalculation

Prediction logic validation

API endpoint verification

🔌 API Endpoints
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

📌 Design Decisions

Business logic fully separated from controllers

Prediction logic isolated in its own service

DTO-based response structure

Standings calculated dynamically

Monte Carlo only active during last 3 weeks

UI intentionally minimal — architecture prioritized

🎯 Purpose

This project demonstrates:

Clean layered architecture

Service-oriented backend structure

Monte Carlo statistical modeling

Maintainable and testable Laravel application design

<p align="center"> © 2026 <strong>Furkan Adıgüzel</strong><br> <a href="https://furkanadiguzel.com/" target="_blank">https://furkanadiguzel.com/</a> </p>
