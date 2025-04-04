# Flexinform Laravel Alkalmazás

A feladat Laravel 12-ben íródott, Breeze bővítménnyel lett megvalósítva a bejelentkezés. Az alap rendszerbe Bootstrap és jQuery lett használva a frontend megjelenítésre, és adatok betöltésére. A JSON fájlok a laravel mappa database/seeders/data mappában vannak.
Az adatbázis és JSON fájlok szerkezeti eltérése a migrációban lett kezelve, se a JSON fájlok tartalma, se a specifikációban meghatározott adatbázis tábla szerkezetek nem módosultak.

---

**Komponensek verziói**
- Laravel (12.7.2)
- Breeze (2.3.6)
- Bootstrap (5.3)
- jQuery (3.6.4)
- Datatable (1.11.5)

**Telepítés lépései**

* git projekt klónozása (git clone https://github.com/todi1979/flexinform.git)
* a gyökér könyvtárban lévő .env fájljban a PROJECT nevű változóhoz írd be a projekt nevét, figyelve arra, hogy ezután az url-ben is így fog szerepelni (pl. flexinform => flexinform.loc)
* Docker indítása parancssorból (**docker-compose up -d --build**)
  * Az alábbi docker image-ek kerülnek telepítésre:
    * nginx (latest) /1.27.4/
    * phpmyadmin (latest) /5.2.2/
    * php (8.4)
    * mysql (latest) /9.2.0/
* docker-ben a php image termináljában / vagy laravel mappán belül
  * **.env.example** fájlban módosítsd a **PROJECT** nevű változót, és nevezd át a fájlt .env -re
  * belépési adatok az **AdminUserSeeder.php** fájlban található a database/seeders mappában
  * _parancsok futtatása_
    * composer install
    * generáld le az APP_KEY -t (**php artisan key:generate --force**)
    * npm install
    * npm run build
    * php artisan migrate --seed
    * php artisan storage:link
* állítsd be az url-t a hosts fájlodban, ha docker a futtatókörnyezeted
  (C:\Windows\System32\drivers\etc\hosts)
  127.0.0.1 **flexinform.loc**
* böngészőbe nyisd meg, ha docker a futtatókörnyezet: https://flexinform.loc/
* A PHPMyAdmin-t itt éred el: http://localhost:8080/