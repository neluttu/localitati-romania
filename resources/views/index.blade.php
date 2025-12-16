<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'SIRUTA - Județe și orașe România pentru developeri') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="flex items-center  min-h-screen flex-col font-varela">
        <div class="flex items-center justify-center flex-col relative min-h-screen w-full">
            <div class="sticky top-8 flex items-center gap-3 z-20">
                @include('partials.nav')
            </div>
            <div class="flex-1 w-full flex items-center justify-center flex-col relative">
                <div class="w-full inset-0 absolute mx-auto -z-30"><x-svg.romania /></div>
                <div class="text-center text-5xl font-normal p-10 bg-white/60  backdrop-blur-md w-full"
                    data-localities-api>
                    <p>Date administrative reale pentru România</p>
                    <div class="grid grid-cols-3 gap-6 max-w-5xl mt-8 mx-auto">
                        <select name="county" id="county"
                            class="rounded-full border-none text-sm p-2 px-3 bg-[#002b7f] text-white">
                            <option>- Alege un județ -</option>
                            <option value="Alba" data-abbr="AB">
                                Alba
                            </option>
                            <option value="Arad" data-abbr="AR">
                                Arad
                            </option>
                            <option value="Argeș" data-abbr="AG">
                                Argeș
                            </option>
                            <option value="Bacău" data-abbr="BC">
                                Bacău
                            </option>
                            <option value="Bihor" data-abbr="BH">
                                Bihor
                            </option>
                            <option value="Bistrița-Năsăud" data-abbr="BN">
                                Bistrița-Năsăud
                            </option>
                            <option value="Botoșani" data-abbr="BT">
                                Botoșani
                            </option>
                            <option value="Brăila" data-abbr="BR">
                                Brăila
                            </option>
                            <option value="Brașov" data-abbr="BV">
                                Brașov
                            </option>
                            <option value="București" data-abbr="B">
                                București
                            </option>
                            <option value="Buzău" data-abbr="BZ">
                                Buzău
                            </option>
                            <option value="Călărași" data-abbr="CL">
                                Călărași
                            </option>
                            <option value="Caraș-Severin" data-abbr="CS">
                                Caraș-Severin
                            </option>
                            <option value="Cluj" data-abbr="CJ">
                                Cluj
                            </option>
                            <option value="Constanța" data-abbr="CT">
                                Constanța
                            </option>
                            <option value="Covasna" data-abbr="CV">
                                Covasna
                            </option>
                            <option value="Dâmbovița" data-abbr="DB">
                                Dâmbovița
                            </option>
                            <option value="Dolj" data-abbr="DJ">
                                Dolj
                            </option>
                            <option value="Galați" data-abbr="GL">
                                Galați
                            </option>
                            <option value="Giurgiu" data-abbr="GR">
                                Giurgiu
                            </option>
                            <option value="Gorj" data-abbr="GJ">
                                Gorj
                            </option>
                            <option value="Harghita" data-abbr="HR">
                                Harghita
                            </option>
                            <option value="Hunedoara" data-abbr="HD">
                                Hunedoara
                            </option>
                            <option value="Ialomița" data-abbr="IL">
                                Ialomița
                            </option>
                            <option value="Iași" data-abbr="IS">
                                Iași
                            </option>
                            <option value="Ilfov" data-abbr="IF">
                                Ilfov
                            </option>
                            <option value="Maramureș" data-abbr="MM">
                                Maramureș
                            </option>
                            <option value="Mehedinți" data-abbr="MH">
                                Mehedinți
                            </option>
                            <option value="Mureș" data-abbr="MS">
                                Mureș
                            </option>
                            <option value="Neamț" data-abbr="NT">
                                Neamț
                            </option>
                            <option value="Olt" data-abbr="OT">
                                Olt
                            </option>
                            <option value="Prahova" data-abbr="PH">
                                Prahova
                            </option>
                            <option value="Sălaj" data-abbr="SJ">
                                Sălaj
                            </option>
                            <option value="Satu Mare" data-abbr="SM">
                                Satu Mare
                            </option>
                            <option value="Sibiu" data-abbr="SB">
                                Sibiu
                            </option>
                            <option value="Suceava" data-abbr="SV">
                                Suceava
                            </option>
                            <option value="Teleorman" data-abbr="TR">
                                Teleorman
                            </option>
                            <option value="Timiș" data-abbr="TM">
                                Timiș
                            </option>
                            <option value="Tulcea" data-abbr="TL">
                                Tulcea
                            </option>
                            <option value="Vâlcea" data-abbr="VL">
                                Vâlcea
                            </option>
                            <option value="Vaslui" data-abbr="VS">
                                Vaslui
                            </option>
                            <option value="Vrancea" data-abbr="VN">
                                Vrancea
                            </option>

                        </select>
                        </select>
                        <select name="city" id="city"
                            class="rounded-full border-none bg-[#fcd116] text-yellow-800 text-sm p-2 px-3">
                            <option>- Alege orașul -</option>
                        </select>
                        <input name="postal_code" id="postal_code"
                            class="rounded-full border-none bg-[#ce1126] text-white text-sm p-2 border-gray-300 px-3 placeholder:text-gray-200"
                            placeholder="Cod poștal">
                    </div>
                    <small class="text-sm pt-4 text-black/40">Versiune beta !! APP_ENV = local !!</small>
                </div>
            </div>
        </div>
        <div class="space-y-3 my-10 container">
            <h1 class="text-xl">Set de date</h1>
            <p class="text-gray-500">
                Acest site utilizează date publice furnizate de Institutul Național de Statistică (INS), prin Sistemul
                Informatic al Registrului Unităților Teritorial-Administrative (SIRUTA), disponibile pe data.gov.ro.
            </p>
            <p class="text-gray-500">
                Datele sunt utilizate conform licenței pentru date deschise, fiind prelucrate și adaptate pentru scopuri
                informatice.
            </p>
            <p class="text-gray-500">
                Informațiile geospațiale (latitudine și longitudine) sunt obținute din seturi de date GeoJSON realizate
                de <a href="geo-spatial.org" target="_blank" class="underline">geo-spatial.org</a>
            </p>
        </div>
        <div class="my-10 w-full container">
            <h2 class="text-xl pb-6">Endpoints</h2>
            <div class="bg-zinc-900 text-zinc-100 rounded-xl p-6 font-mono text-sm shadow-lg">
                <div class="flex items-center gap-2 mb-4 text-xs text-zinc-400">
                    <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                    <span class="w-3 h-3 bg-yellow-400 rounded-full"></span>
                    <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                </div>
                <div class="mb-3">
                    <span class="text-green-400">$</span>
                    <span class="text-blue-400">GET</span>
                    <span class="text-zinc-200">https://api.siruta.ro/v1/counties/{abbr}/localities</span>
                </div>
                <p class="text-zinc-400">
                    Returnează lista completă de localități pentru un județ,
                    incluzând municipii, orașe, sate și coordonate geografice.
                </p>
            </div>
            <pre class="bg-black/5 rounded-lg p-4 overflow-x-auto text-sm -mt-4 pt-10">
{
  "data": [
    {
      "id": 10761,
      "siruta_code": 114328,
      "name": "Târgu Mureș",
      "name_ascii": "targu mures",
      "type": 9,
      "type_label": "Reședință municipiu",
      "type_group": "localitati",
      "postal_code": "540146",
      "lat": 46.540227,
      "lng": 24.558206,
      "parent": {
        "siruta_code": 114319,
        "name": "Târgu Mureș",
        "type": 1
      }
    }
  ],
  "meta": {
    "county": {
      "code": "MS",
      "name": "Mureș"
    },
    "total": 518
  }
}
            </pre>
            <div class="bg-zinc-900 text-zinc-100 rounded-xl p-6 font-mono text-sm shadow-lg mt-16">
                <div class="flex items-center gap-2 mb-4 text-xs text-zinc-400">
                    <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                    <span class="w-3 h-3 bg-yellow-400 rounded-full"></span>
                    <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                </div>
                <div class="mb-3">
                    <span class="text-green-400">$</span>
                    <span class="text-blue-400">GET</span>
                    <span class="text-zinc-200">https://api.siruta.ro/v1/counties/{abbr}/localities-lite</span>
                </div>
                <p class="text-zinc-400">
                    Returnează lista completă de localități pentru un județ,
                    incluzând municipii, orașe, sate și coordonate geografice.
                </p>
            </div>
            <pre class="bg-black/5 rounded-lg p-4 overflow-x-auto text-sm -mt-4 pt-10">
{
  "data": [
    {
      "siruta_code": 114328,
      "name": "Târgu Mureș",
      "type_label": "Municipii",
      "postal_code": "540146",
      "lat": 46.540227,
      "lng": 24.558206
    },
    {
      "siruta_code": 114827,
      "name": "Apalina",
      "type_label": "Sate",
      "parent": {
        "name": "Reghin"
      }
    }
  ],
  "meta": {
    "county": {
      "code": "MS",
      "name": "Mureș"
    },
    "total": 620
  }
}
            </pre>
            <div class="bg-zinc-900 text-zinc-100 rounded-xl p-6 font-mono text-sm shadow-lg mt-16">
                <div class="flex items-center gap-2 mb-4 text-xs text-zinc-400">
                    <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                    <span class="w-3 h-3 bg-yellow-400 rounded-full"></span>
                    <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                </div>
                <div class="mb-3">
                    <span class="text-green-400">$</span>
                    <span class="text-blue-400">GET</span>
                    <span class="text-zinc-200">https://api.siruta.ro/v1/counties</span>
                </div>
                <p class="text-zinc-400">
                    Returnează lista completă a județelor din România, inclusiv codul SIRUTA și abrevierea oficială.
                </p>
            </div>
            <pre class="bg-black/5 rounded-lg p-4 overflow-x-auto text-sm -mt-4 pt-10">
{
  "data": [
    {
      "id": 1,
      "name": "Alba",
      "abbr": "AB",
      "code": 10,
      "region": "Centru"
    },
    {
      "id": 28,
      "name": "Mureș",
      "abbr": "MS",
      "code": 26,
      "region": "Centru"
    },
    {
      "id": 40,
      "name": "Timiș",
      "abbr": "TM",
      "code": 35,
      "region": "Vest"
    }
  ],
  "meta": {
    "total": 42
  }
}        
            </pre>
            <div class="my-10 w-full container">
                <h2 class="text-xl pb-6">APP_ENV = local</h2>
                <p class="text-gray-500">
                    Întrucât proiectul este în stadiul de dezvoltare și contractele nu sunt definitive, doar testati
                    API-ul, nu îl folosiți în producție.
                </p>
            </div>
        </div>
        <footer class="container mx-auto my-16 text-center flex items-center justify-center gap-10">
            <a href="https://x.com/neluttu" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                    stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-x">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 4l11.733 16h4.267l-11.733 -16z" />
                    <path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772" />
                </svg>
            </a>
            <a href="https://github.com/neluttu">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                    stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-github">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M9 19c-4.3 1.4 -4.3 -2.5 -6 -3m12 5v-3.5c0 -1 .1 -1.4 -.5 -2c2.8 -.3 5.5 -1.4 5.5 -6a4.6 4.6 0 0 0 -1.3 -3.2a4.2 4.2 0 0 0 -.1 -3.2s-1.1 -.3 -3.5 1.3a12.3 12.3 0 0 0 -6.2 0c-2.4 -1.6 -3.5 -1.3 -3.5 -1.3a4.2 4.2 0 0 0 -.1 3.2a4.6 4.6 0 0 0 -1.3 3.2c0 4.6 2.7 5.7 5.5 6c-.6 .6 -.6 1.2 -.5 2v3.5" />
                </svg>
            </a>
            <a href="https://discord.com/users/541003702400450580">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                    stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-discord">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M8 12a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" />
                    <path d="M14 12a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" />
                    <path
                        d="M15.5 17c0 1 1.5 3 2 3c1.5 0 2.833 -1.667 3.5 -3c.667 -1.667 .5 -5.833 -1.5 -11.5c-1.457 -1.015 -3 -1.34 -4.5 -1.5l-.972 1.923a11.913 11.913 0 0 0 -4.053 0l-.975 -1.923c-1.5 .16 -3.043 .485 -4.5 1.5c-2 5.667 -2.167 9.833 -1.5 11.5c.667 1.333 2 3 3.5 3c.5 0 2 -2 2 -3" />
                    <path d="M7 16.5c3.5 1 6.5 1 10 0" />
                </svg>
            </a>
        </footer>
    </body>

</html>
