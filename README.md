# ⚽ Insider One – Champions League Simulation

A league simulation engine that plays weekly matches, calculates standings dynamically, and estimates championship probabilities using Monte Carlo simulation.

🌐 **Live Demo:**  
https://insider-one-league.furkanadiguzel.com/

📊 **Dashboard:**  
https://insider-one-league.furkanadiguzel.com/dashboard

---

## 📖 About The Project

Insider One is built to demonstrate **clean architecture and testable business logic**, not just match simulation.

- 🧠 Clean Service-Oriented Architecture  
- 🔄 Dynamic standings calculation  
- 🎲 Probabilistic match engine  
- 📈 Monte Carlo championship prediction  

---

## 🛠 Tech Stack

- 🧱 Laravel  
- ⚡ Vue 3  
- 🎛 Vite  
- 🧩 Service Pattern  
- 📦 DTO (Data Transfer Objects)  
- 🧪 PHPUnit  
- 🐳 Docker  

---

## 🏗 Architecture

The project follows a layered design:

- 🎯 **Controllers** → Handle only HTTP request/response  
- 🧠 **Service Layer** → Contains all business logic  
- 📦 **DTO Layer** → Standardizes API responses  
- 🎲 **Simulation Service** → Match engine  
- 📊 **Prediction Service** → Monte Carlo engine  

⚠️ No business logic exists inside controllers.  
📈 Standings are calculated dynamically (never persisted).

---

## ⚙️ Features

- ➕ Team CRUD  
- 📅 Round-robin fixture generation  
- ▶️ Play next week  
- ⏩ Play full season  
- ✏️ Edit match scores  
- 🔁 Automatic standings recalculation  
- 🏆 Championship probability (last 3 weeks)

---

## 🧠 Simulation Logic

### 📊 Standings (Deterministic)

- 3 points for win  
- 1 point for draw  
- Ranking priority:
  1. Points
  2. Goal Difference
  3. Goals Scored  

---

### 🎲 Match Simulation

- Uses team **power rating**
- Generates scores probabilistically
- Small home advantage
- Randomized outcomes

---

### 🔮 Monte Carlo Prediction

- Active only during the **last 3 weeks**
- Remaining matches simulated thousands of times
- Champion determined each iteration
- Final output: percentage chance per team

Statistical sampling is used instead of brute-force combinations for performance efficiency.

---
```bash

## 🚀 Local Setup

1️⃣ Clone
git clone <repo-url>
cd insider-one-league

2️⃣ Backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate

SQLite (optional)

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

touch database/database.sqlite
php artisan migrate

3️⃣ Frontend
npm install
npm run dev

Production build:
npm run build

4️⃣ Run
php artisan serve
Open:

http://localhost:8000/dashboard

🐳 Docker
docker compose up -d --build
docker compose exec app php artisan migrate

🧪 Tests
php artisan test
✔ Full simulation flow
✔ Score edit recalculation
✔ Prediction validation
✔ API endpoint verification

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
📌 Notes
Default season length: 6 weeks

Monte Carlo iteration count optimized for performance

UI intentionally minimal

Production requires Vite build (public/build/manifest.json)

👨‍💻 Author
© 2026 Furkan Adıgüzel
https://furkanadiguzel.com/

