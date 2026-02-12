# Insider One – Champions League Simulation

Bu proje, küçük ölçekli bir lig ortamında haftalık maç simülasyonu yapan, puan tablosunu hesaplayan ve son 3 haftada Monte Carlo yöntemi ile şampiyonluk olasılıklarını tahmin eden bir uygulamadır.

Backend tarafı Laravel ile, frontend tarafı Vue 3 ile geliştirilmiştir. Mimari olarak Service Pattern + DTO yaklaşımı kullanılmıştır. Amaç yalnızca çalışır bir simülasyon değil, aynı zamanda sürdürülebilir, test edilebilir ve genişletilebilir bir yapı kurmaktır.

---

## 🚀 Tech Stack

- Laravel
- Vue 3
- Service Pattern
- DTO (Data Transfer Object)
- Monte Carlo Simulation
- PHPUnit
- Docker

---

## 🏗️ Mimari Yaklaşım

Proje katmanlı bir yapı ile geliştirilmiştir:

- Controller → sadece request/response yönetir.
- Service Layer → tüm business logic burada yer alır.
- DTO → veri transferi ve response formatı için kullanılır.
- Prediction & Simulation → ayrı servisler olarak tasarlanmıştır.
- Controller içinde doğrudan DB query yapılmaz.

Bu yapı sayesinde:
- Kod okunabilir ve test edilebilir kalır.
- Business logic UI’dan tamamen ayrıdır.
- Prediction algoritması kolayca değiştirilebilir.

---

## ⚽ Özellikler

- Takım ekleme / düzenleme / silme
- Fixture üretme (round-robin)
- Haftalık simülasyon
- Tüm haftaları oynatma
- Skor düzenleme
- Skor değişiminde puan tablosunun yeniden hesaplanması
- Son 3 haftada şampiyonluk olasılığı (Monte Carlo)

---

## 🧠 Simülasyon Mantığı

### Standings (Deterministic)

- Oynanmış maç skorlarından hesaplanır.
- 3 puan galibiyet, 1 puan beraberlik.
- Sıralama kriteri:
  1. Puan
  2. Averaj
  3. Atılan gol

Standings verisi DB’ye kalıcı yazılmaz, her seferinde hesaplanır.

---

### Match Simulation

- Takımların power değeri dikkate alınır.
- Güç oranına göre probabilistic skor üretilir.
- Ev sahibi küçük avantaj içerir.
- Simülasyon deterministic değildir (randomized).

---

### Prediction (Monte Carlo)

- Sadece son 3 haftada aktif olur.
- Kalan maçlar binlerce kez simüle edilir.
- Her iterasyonda şampiyon belirlenir.
- Sonuç olarak her takım için yüzde bazlı şampiyonluk ihtimali hesaplanır.

Bu yaklaşım brute force kombinasyon yerine istatistiksel örnekleme kullanır.

---

## 🛠️ Kurulum (Local)

### 1. Repo Klonla

git clone <repo-url>
cd insider-one-league

---

### 2. Backend Kurulumu

composer install
cp .env.example .env
php artisan key:generate

Database ayarlarını .env içinde yap.

SQLite kullanmak için:

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

Ardından:

touch database/database.sqlite
php artisan migrate

---

### 3. Frontend Kurulumu

npm install
npm run dev

---

### 4. Uygulamayı Başlat

php artisan serve

Frontend arayüz:
http://localhost:8000/ui

---

## 🐳 Docker ile Çalıştırma

docker compose up -d --build

Migration:

docker compose exec app php artisan migrate

---

## 🧪 Test Çalıştırma

php artisan test

Test kapsamı:

- Full simulation flow
- Score edit sonrası recalculation
- Prediction (son 3 hafta) kontrolü
- API endpoint doğrulamaları

---

## 🔌 API Endpoints

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

---

## 📌 Tasarım Kararları

- Controller içinde business logic yoktur.
- Prediction Service ayrı tutulmuştur.
- Match simulation soyutlanmıştır (gelecekte farklı algoritmalar eklenebilir).
- Standings DB’ye persist edilmez.
- DTO ile response yapısı sabit tutulur.
- Prediction yalnızca son 3 haftada çalışır (gereksiz hesaplama yapılmaz).

---

## 📎 Notlar

- Varsayılan lig 6 haftalık yapı üzerinden çalışır.
- Iteration sayısı performans ve doğruluk dengesi gözetilerek belirlenmiştir.
- UI sade tutulmuştur; mimari önceliklidir.

---

## 🎯 Sonuç

Bu proje:

- Katmanlı mimari,
- Test edilebilir servis yapısı,
- Ayrılmış business logic,
- Genişletilebilir prediction algoritması

gibi yazılım prensiplerini göstermek amacıyla geliştirilmiştir.
