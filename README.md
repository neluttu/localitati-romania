# 🇷🇴 Localități România — API + Bază de date (SIRUTA 2025)

Acesta este un proiect Laravel care oferă o bază de date completă a localităților din România,
construită pe baza dataset-ului **SIRUTA 2025** (INS), îmbogățită cu coordonate geografice
(latitudine / longitudine) din surse GEOJSON oficiale.

Proiectul include:

-   🟦 Lista județelor (cu coduri SIRUTA și abrevieri oficiale — AB, MS, CJ etc.)
-   🟩 Lista localităților din România (municipii, orașe, comune, sate)
-   📍 Coordonate geografice pentru majoritatea localităților (lat/lng)
-   🚀 API public pentru extragerea județelor și localităților
-   🔎 Căutare rapidă (ASCII normalized)
-   📦 Structură optimizată pentru utilizare în magazine online, formulare de adresă,
    livrări, validări sau aplicații GIS.

## 🛠 Platformă și tehnologii

Acest proiect este construit pe:

-   **Laravel 12.x**
-   **PHP 8.2+**
-   **MySQL 8+** (sau MariaDB)
-   **TailwindCSS** (pentru vizualizarea datelor în frontend)
-   **CLI Artisan Commands** pentru importul SIRUTA și GEOJSON

## 🎯 Obiectivul proiectului

Scopul este să ofere o bază standardizată de localități din România,
ușor de integrat în proiecte precum:

-   magazine online (checkout / formulare adresă)
-   aplicații medicale sau administrative
-   sisteme de ticketing și livrare
-   aplicații GIS sau hărți interactive
-   validarea adreselor introduse de utilizatori

## 📂 Ce conține repo-ul?

-   `migrations/` – structura completă a tabelelor pentru județe și localități
-   `app/Console/Commands/` – importere SIRUTA + GEOJSON
-   `app/Models/` – modele Eloquent optimizate (inclusiv sorting logic)
-   `app/Http/Controllers/Api/` – API pentru județe + localități
-   `resources/views/api/` – vizualizări tabelare pentru explorarea datelor
-   `storage/` – locația default pentru fișierele SIRUTA/GEOJSON

## 🚦 Status proiect

🔧 **În dezvoltare activă.**  
Importerele sunt funcționale, API-ul este stabil, iar view-urile sunt în curs de extindere.

Documentația completă (instalare, endpoint-uri, exemple de răspuns) va fi adăugată ulterior.

## © Licență

Urmează să fie definită (MIT recomandat pentru open-source).

---

Dacă ai sugestii, contribuții sau vrei să folosești baza în proiectul tău,
poți deschide un issue în acest repository.
