Insider One – Champions League Simulation

Insider One, küçük ölçekli bir lig ortamında haftalık maç simülasyonu yapan, puan tablosunu dinamik olarak hesaplayan ve son 3 haftada Monte Carlo yöntemi ile şampiyonluk olasılıklarını tahmin eden bir uygulamadır.

Backend tarafı Laravel, frontend tarafı Vue 3 ile geliştirilmiştir. Mimari olarak Service Pattern + DTO yaklaşımı kullanılmıştır.

Amaç yalnızca çalışan bir simülasyon üretmek değil; aynı zamanda sürdürülebilir, test edilebilir ve genişletilebilir bir yazılım mimarisi kurmaktır.

🌐 Canlı Demo

Uygulamanın canlı versiyonunu aşağıdaki adresten inceleyebilirsiniz:

👉 https://insider-one-league.furkanadiguzel.com/

Dashboard’a doğrudan erişim:

👉 https://insider-one-league.furkanadiguzel.com/dashboard

🚀 Tech Stack

Laravel

Vue 3

Service Pattern

DTO (Data Transfer Object)

Monte Carlo Simulation

PHPUnit

Docker

Vite

🏗️ Mimari Yaklaşım

Proje katmanlı bir yapı ile geliştirilmiştir:

Controller → Sadece request/response yönetir.

Service Layer → Tüm business logic burada yer alır.

DTO → Veri transferi ve response formatı için kullanılır.

Simulation & Prediction Services → Ayrı servisler olarak tasarlanmıştır.

Controller içinde doğrudan DB query yapılmaz.

Bu yapı sayesinde:

Kod okunabilir ve test edilebilir kalır.

Business logic UI’dan tamamen ayrıdır.

Prediction algoritması kolayca değiştirilebilir.

Genişletilebilir ve sürdürülebilir bir yapı elde edilir.

⚽ Özellikler

Takım ekleme / düzenleme / silme

Round-robin fixture üretimi

Haftalık simülasyon

Tüm haftaları otomatik oynatma

Skor düzenleme

Skor değişiminde otomatik puan tablosu yeniden hesaplama

Son 3 haftada Monte Carlo ile şampiyonluk olasılığı hesaplama

Dashboard üzerinden tüm süreci yönetebilme

🧠 Simülasyon Mantığı
📊 Standings (Deterministic)

Oynanmış maç skorlarından hesaplanır.

3 puan galibiyet, 1 puan beraberlik.

Sıralama kriterleri:

Puan

Averaj

Atılan gol

Standings verisi DB’ye kalıcı olarak yazılmaz; her istek sırasında hesaplanır.

🎲 Match Simulation

Takımların power değeri dikkate alınır.

Güç oranına göre probabilistic skor üretilir.

Ev sahibi takıma küçük avantaj tanımlanmıştır.

Simülasyon deterministic değildir (randomized).

🔮 Prediction (Monte Carlo)

Sadece son 3 haftada aktif olur.

Kalan maçlar binlerce kez simüle edilir.

Her iterasyonda şampiyon belirlenir.

Sonuç olarak her takım için yüzde bazlı şampiyonluk ihtimali hesaplanır.

Bu yaklaşım brute force kombinasyon yerine istatistiksel örnekleme kullanır ve performans açısından optimize edilmiştir.

🛠️ Kurulum (Local)
1️⃣ Repository Klonla
git clone <repo-url>
cd insider-one-league

2️⃣ Backend Kurulumu
composer install
cp .env.example .env
php artisan key:generate


Database ayarlarını .env içinde yapılandır.

SQLite kullanmak için:

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite


Ardından:

touch database/database.sqlite
php artisan migrate

3️⃣ Frontend Kurulumu
npm install
npm run dev


Production build için:

npm run build

4️⃣ Uygulamayı Başlat
php artisan serve


Local erişim:

http://localhost:8000/dashboard

🐳 Docker ile Çalıştırma
docker compose up -d --build


Migration:

docker compose exec app php artisan migrate

🧪 Test Çalıştırma
php artisan test


Test kapsamı:

Full simulation flow

Score edit sonrası recalculation

Prediction (son 3 hafta) doğrulaması

API endpoint doğrulamaları

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

📌 Tasarım Kararları

Controller içinde business logic bulunmaz.

Prediction Service ayrı tutulmuştur.

Match simulation soyutlanmıştır (farklı algoritmalar eklenebilir).

Standings DB’ye persist edilmez.

DTO ile response yapısı sabit tutulur.

Prediction yalnızca son 3 haftada çalışır (gereksiz hesaplama yapılmaz).

Mimari önceliklidir, UI minimal tutulmuştur.

📎 Notlar

Varsayılan lig yapısı 6 haftalık örnek üzerinden çalışır.

Monte Carlo iteration sayısı performans/doğruluk dengesi gözetilerek belirlenmiştir.

Production ortamında Vite build gereklidir (public/build/manifest.json).

🎯 Sonuç

Bu proje:

Katmanlı mimari

Test edilebilir servis yapısı

Ayrılmış business logic

Genişletilebilir prediction algoritması

Temiz separation of concerns

gibi yazılım prensiplerini göstermek amacıyla geliştirilmiştir.

© Furkan Adıgüzel
https://furkanadiguzel.com/
