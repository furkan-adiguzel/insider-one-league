Insider One – Champions League Simulation

Bu proje, küçük bir lig ortamında haftalık maç simülasyonu yapan, puan tablosunu hesaplayan ve son 3 haftada Monte Carlo yöntemi ile şampiyonluk olasılıklarını tahmin eden bir uygulamadır.

Backend Laravel ile, frontend Vue 3 ile geliştirilmiştir. Mimari olarak Service Pattern + DTO yaklaşımı kullanılmıştır. Amaç yalnızca çalışır bir simülasyon değil, aynı zamanda sürdürülebilir ve test edilebilir bir yapı kurmaktır.

Tech Stack

Laravel

Vue 3

Service Pattern

DTO

Monte Carlo Simulation

SQLite (test için memory)

Docker

Genel Mimari

Controller katmanında iş mantığı bulunmaz.
Controller → Service → Repository/Model akışı izlenir.

Controller yalnızca request alır ve response döner.

Business logic Service katmanındadır.

Data transfer için DTO kullanılır.

Prediction, Standings ve Match simulation ayrı servislerdir.

SOLID prensiplerine dikkat edilmiştir.

Özellikle:

Standings hesaplaması deterministic (gerçek maçlara göre)

Prediction hesaplaması probabilistic (Monte Carlo)

Özellikler

Takım ekleme / düzenleme / silme

Fixture üretme

Haftalık simülasyon

Tüm haftaları oynatma

Skor düzenleme ve puan tablosunun yeniden hesaplanması

Son 3 haftada şampiyonluk olasılığı hesaplama

Simülasyon Mantığı
Standings

Oynanmış maç skorlarından puan tablosu oluşturulur.

3 puan galibiyet, 1 puan beraberlik.

Sıralama: Puan → Averaj → Atılan gol.

Match Simulation

Takım power değerleri dikkate alınır.

Güç oranına göre probabilistic skor üretilir.

Ev sahibi küçük avantaj içerir.

Prediction (Monte Carlo)

Son 3 haftada aktif olur.

Kalan maçlar binlerce kez simüle edilir.

Her iterasyonda şampiyon belirlenir.

Sonuç olarak her takım için % şampiyonluk olasılığı hesaplanır.

Kurulum
1. Repo klonla
git clone <repo-url>
cd insider-one-league

2. Backend kurulumu
composer install
cp .env.example .env
php artisan key:generate


Database ayarını .env içinde yap.

SQLite kullanmak istersen:

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite


Sonra:

touch database/database.sqlite
php artisan migrate

3. Frontend kurulumu
npm install
npm run dev

4. Uygulamayı çalıştır
php artisan serve


Frontend:

http://localhost:8000/ui

Docker ile Çalıştırma

Projede Dockerfile ve docker-compose mevcuttur.

docker compose up -d --build


Migration:

docker compose exec app php artisan migrate

Test Çalıştırma

Testler SQLite memory üzerinde çalışır.

php artisan test


Test kapsamı:

Full simulation flow

Edit sonrası recalculation

Prediction last 3 weeks gate

API endpoint doğrulamaları

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

Önemli Tasarım Kararları

Controller içinde DB query yapılmaz.

Prediction Service ayrı tutulmuştur.

Match simulation soyutlanmıştır (ileride farklı algoritma eklenebilir).

Standings DB’ye persist edilmez; her seferinde hesaplanır.

DTO ile response yapısı sabit tutulur.

Prediction yalnızca son 3 haftada çalışır (gereksiz hesaplama yapılmaz).

Notlar

Varsayılan lig 6 haftalık round-robin mantığı ile çalışır.

Iteration sayısı performans/güvenilirlik dengesi gözetilerek belirlenmiştir.

UI basit ama fonksiyonel tutulmuştur; mimari önceliklidir.

Sonuç

Bu proje yalnızca bir maç simülasyonu değil;

Katmanlı mimari,

Test edilebilir servis yapısı,

Ayrılmış business logic,

Genişletilebilir prediction algoritması

gibi yazılım prensiplerini göstermek amacıyla geliştirilmiştir.
