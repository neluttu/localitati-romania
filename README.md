# 🇷🇴 Localități România — API & Bază de date (SIRUTA 2025)

Proiect **Laravel** care oferă o bază de date completă și un **API public** pentru județele și localitățile din România, construit pe baza dataset-ului oficial **SIRUTA 2025 (INS)** și îmbogățit cu coordonate geografice (latitudine / longitudine) din surse **GEOJSON oficiale**.

Gândit pentru aplicații reale: formulare de adresă, e-commerce, validări, livrări și aplicații administrative.

---

## 📊 Date incluse

-   🟦 Județe din România (coduri SIRUTA + abrevieri oficiale: AB, MS, CJ etc.)
-   🟩 Localități: municipii, orașe, comune, sate
-   📍 Coordonate geografice (lat / lng) pentru majoritatea localităților
-   🔎 Căutare rapidă (nume normalizate ASCII)
-   📦 Structură optimizată pentru utilizare în producție

---

## 🌐 API public

### 1️⃣ Toate județele

`GET /v1/counties`

```json
{
    "data": [
        { "code": "MS", "name": "Mureș" },
        { "code": "CJ", "name": "Cluj" }
    ]
}
```

---

### 2️⃣ Localități dintr-un județ (light – pentru formulare)

`GET /v1/counties/{county}/localities/light`

Exemplu:
`/v1/counties/MS/localities/light`

```json
{
    "data": [
        {
            "siruta_code": "114818",
            "name": "Reghin",
            "type": "municipiu",
            "postal_code": "545300"
        }
    ]
}
```

---

### 3️⃣ Detalii complete pentru o localitate

`GET /v1/counties/{county}/localities/{siruta}`

Exemplu:
`/v1/counties/MS/localities/114818`

```json
{
    "data": {
        "siruta_code": "114818",
        "name": "Reghin",
        "type": "municipiu",
        "parent": "Mureș",
        "postal_code": "545300",
        "lat": 46.7749,
        "lng": 24.7023
    }
}
```

---

## 🛠 Platformă & tehnologii

-   **Laravel 12.x**
-   **PHP 8.2+**
-   **MySQL 8+ / MariaDB**
-   **TailwindCSS** (pentru vizualizare frontend)
-   **Artisan CLI Commands** pentru import SIRUTA & GEOJSON

---

## 📂 Structura proiectului

-   `database/migrations/` – tabele județe & localități
-   `app/Console/Commands/` – importere SIRUTA + GEOJSON
-   `app/Models/` – modele Eloquent optimizate
-   `app/Http/Controllers/Api/` – endpoint-uri API
-   `resources/views/api/` – explorare date în browser
-   `storage/` – fișiere sursă CSV / GEOJSON

---

## 🎯 Scop

O bază **standardizată, actualizată și ușor de integrat** pentru:

-   magazine online (checkout / adresă)
-   aplicații medicale sau administrative
-   sisteme de livrare și ticketing
-   aplicații GIS sau hărți interactive
-   validarea adreselor introduse de utilizatori

---

## 🚦 Status

🔧 **În dezvoltare activă**  
Importerele sunt funcționale, API-ul este stabil, iar documentația se extinde constant.

---

## © Licență

Urmează să fie definită (MIT recomandat pentru open-source).

---

Build once. Use everywhere. 🇷🇴
