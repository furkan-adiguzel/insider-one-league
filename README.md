# Insider One – Champions League Simulation

Bu proje, küçük ölçekli bir lig ortamında haftalık maç simülasyonu yapan, puan tablosunu hesaplayan ve son **3 haftada** Monte Carlo yöntemi ile şampiyonluk olasılıklarını tahmin eden bir uygulamadır.

Backend Laravel, frontend Vue 3 ile geliştirilmiştir. Mimari olarak Service Pattern + DTO yaklaşımı kullanılmıştır. Controller katmanında business logic tutulmaz; iş mantığı servis katmanındadır.

---

## Tech Stack

- Laravel
- Vue 3
- Service Pattern
- DTO (Data Transfer Object)
- Monte Carlo Simulation
- PHPUnit

---

## Mimari Yaklaşım (Kısa)

- Controller → sadece request/response yönetir.
- Service Layer → business logic burada.
- DTO → response formatı ve veri transferi için.
- Standings (deterministic) ve Prediction (probabilistic) ayrı servislerdir.
- Controller içinde doğrudan DB query yapılmaz.

---

## Özellikler

- Takım ekleme / düzenleme / silme
- Fixture üretme (round-robin)
- Haftalık simülasyon
- Tüm haftaları oynatma
- Skor düzenleme ve puan tablosunu yeniden hesaplama
- Son 3 haftada Monte Carlo ile şampiyonluk olasılığı

---

## Simülasyon Mantığı

### Standings (Deterministic)
- Sadece oynanmış maç skorlarından hesaplanır.
- 3 puan galibiyet, 1 puan beraberlik.
- Sıralama: Puan → Averaj → Atılan gol.

> Not: Standings DB’ye “hazır tablo” olarak yazılmaz; her istek anında yeniden hesaplanır.

### Match Simulation
- Takımların `power` değeri dikkate alınır.
- Güç oranına göre probabilistic skor üretilir (random).
- Ev sahibi küçük avantaj içerir.

### Prediction (Monte Carlo)
- Prediction yalnızca **son 3 haftada** aktif olur.
- Kalan maçlar binlerce kez simüle edilir.
- Her iterasyonda şampiyon belirlenir ve sayaç tutulur.
- Sonuç olarak yüzde bazlı şampiyonluk ihtimali hesaplanır.

---

## Kurulum (Local)

### 1) Repo’yu klonla

```bash
git clone https://github.com/furkan-adiguzel/insider-one-league
cd insider-one-league
2) Backend bağımlılıkları
composer install
cp .env.example .env
php artisan key:generate
3) Database ayarı (XAMPP / MySQL / başka DB olabilir)
Bu proje bir veritabanı ister. XAMPP (MySQL/MariaDB) kullanabilirsin veya kendi DB’n neyse onu kullanabilirsin.

DB oluştur (örnek: insider_one_league)

.env dosyasında DB bilgilerini doldur:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=insider_one_league
DB_USERNAME=root
DB_PASSWORD=
Eğer başka bir DB kullanıyorsan (pgsql vs), DB_CONNECTION ve ilgili ayarları ona göre düzenle.

4) Migration çalıştır
php artisan migrate
5) Frontend bağımlılıkları
npm install
6) Development server’ları çalıştır
Bir terminal:

php artisan serve
İkinci terminal:

npm run dev
UI:

http://localhost:8000/ui
Test Çalıştırma
php artisan test
Testlerde SQLite memory kullanmak istersen .env.testing şu şekilde olabilir:

APP_ENV=testing
APP_DEBUG=false

DB_CONNECTION=sqlite
DB_DATABASE=:memory:

CACHE_STORE=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
Not: Windows’ta SQLite driver yoksa (pdo_sqlite/sqlite3), test DB’sini MySQL test DB’ye yönlendirebilirsin.

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
Notlar
Varsayılan lig 6 haftalık format üzerinden ilerler.

Prediction (Monte Carlo) yalnızca son 3 haftada çalışır (performans için).

UI sade ama akışın tamamını kapsar (takım → fixture → simülasyon → edit → standings/prediction).
