@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center flex-col relative min-h-screen w-full">
        <div class="flex-1 w-full flex items-center justify-center flex-col relative">
            <div class="w-full inset-0 absolute mx-auto -z-30"><x-svg.romania /></div>
            <div class="text-center text-5xl font-normal p-10 bg-white/80  backdrop-blur-md w-full  shadow-[0px_0px_48px_5px_rgba(204,204,204,0.35)]"
                data-localities-api>
                <p class="tracking-tight">Date administrative oficiale pentru România</p>
                <div class="grid md:grid-cols-3 grid-cols-1 gap-6 max-w-5xl mt-8 mx-auto">
                    <select name="county" id="county"
                        class="rounded-full border-none text-base p-2 px-3 bg-[#002b7f] text-white">
                        <option>- Alege un județ -</option>
                        <option value="Alba" data-abbr="AB">Alba</option>
                        <option value="Arad" data-abbr="AR">Arad</option>
                        <option value="Argeș" data-abbr="AG">Argeș</option>
                        <option value="Bacău" data-abbr="BC">Bacău</option>
                        <option value="Bihor" data-abbr="BH">Bihor</option>
                        <option value="Bistrița-Năsăud" data-abbr="BN">Bistrița-Năsăud</option>
                        <option value="Botoșani" data-abbr="BT">Botoșani</option>
                        <option value="Brăila" data-abbr="BR">Brăila</option>
                        <option value="Brașov" data-abbr="BV">Brașov</option>
                        <option value="București" data-abbr="B">București</option>
                        <option value="Buzău" data-abbr="BZ">Buzău</option>
                        <option value="Călărași" data-abbr="CL">Călărași</option>
                        <option value="Caraș-Severin" data-abbr="CS">Caraș-Severin</option>
                        <option value="Cluj" data-abbr="CJ">Cluj</option>
                        <option value="Constanța" data-abbr="CT">Constanța</option>
                        <option value="Covasna" data-abbr="CV">Covasna</option>
                        <option value="Dâmbovița" data-abbr="DB">Dâmbovița</option>
                        <option value="Dolj" data-abbr="DJ">Dolj</option>
                        <option value="Galați" data-abbr="GL">Galați</option>
                        <option value="Giurgiu" data-abbr="GR">Giurgiu</option>
                        <option value="Gorj" data-abbr="GJ">Gorj</option>
                        <option value="Harghita" data-abbr="HR">Harghita</option>
                        <option value="Hunedoara" data-abbr="HD">Hunedoara</option>
                        <option value="Ialomița" data-abbr="IL">Ialomița</option>
                        <option value="Iași" data-abbr="IS">Iași</option>
                        <option value="Ilfov" data-abbr="IF">Ilfov</option>
                        <option value="Maramureș" data-abbr="MM">Maramureș</option>
                        <option value="Mehedinți" data-abbr="MH">Mehedinți</option>
                        <option value="Mureș" data-abbr="MS">Mureș</option>
                        <option value="Neamț" data-abbr="NT">Neamț</option>
                        <option value="Olt" data-abbr="OT">Olt</option>
                        <option value="Prahova" data-abbr="PH">Prahova</option>
                        <option value="Sălaj" data-abbr="SJ">Sălaj</option>
                        <option value="Satu Mare" data-abbr="SM">Satu Mare</option>
                        <option value="Sibiu" data-abbr="SB">Sibiu</option>
                        <option value="Suceava" data-abbr="SV">Suceava</option>
                        <option value="Teleorman" data-abbr="TR">Teleorman</option>
                        <option value="Timiș" data-abbr="TM">Timiș</option>
                        <option value="Tulcea" data-abbr="TL">Tulcea</option>
                        <option value="Vâlcea" data-abbr="VL">Vâlcea</option>
                        <option value="Vaslui" data-abbr="VS">Vaslui</option>
                        <option value="Vrancea" data-abbr="VN">Vrancea</option>
                    </select>
                    <select name="city" id="city"
                        class="rounded-full border-none bg-[#fcd116] text-yellow-800 text-base p-2 px-3">
                        <option>- Alege orașul -</option>
                    </select>
                    <input name="postal_code" id="postal_code"
                        class="rounded-full border-none bg-[#ce1126] text-white text-base p-2 border-gray-300 px-3 placeholder:text-gray-200"
                        placeholder="Cod poștal">
                </div>
            </div>
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-4 flex items-center justify-start gap-10 mt-10 flex-col md:flex-row">
        <div class="text-lg grow font-light">
            <h2 class="text-4xl pb-3 tracking-tight text-primary-blue">
                Alege rapid
            </h2>
            <p>Select-uri pentru județ și localitate. Cu auto-complete sau fără, depinde de tine.</p>
            <p class="pt-4">Date verificate pentru toate județele din România.</p>
            <a href="{{ route('docs') }}"
                class="inline-block rounded-full px-4 mt-10 text-base py-2 text-white bg-purple-500 cursor-pointer hover:bg-purple-600 transition-all duration-500 ease-in-out">
                Vezi documentația
            </a>
        </div>
        <img src="{{ asset('storage/images/form.jpg') }}" class="max-w-4xl w-full md:w-1/2">
    </div>
    <div class="space-y-3 my-10 max-w-7xl mx-auto px-4">
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
            de <a href="https://geo-spatial.org" target="_blank" class="underline">geo-spatial.org</a>
        </p>
    </div>
    <div class="my-10 w-full max-w-7xl mx-auto px-4" data-accordion>
        <h2 class="text-xl pb-6">Endpoints</h2>
        <div class="flex items-start justify-start gap-10 flex-col w-full">
            <x-api-accordion method="GET" url="https://api.siruta.ro/v1/counties/MS/localities">
                <x-slot:description>
                    Returnează lista completă de localități pentru un județ, incluzând municipii, orașe, sate și coordonate
                    geografice.
                </x-slot:description>

                <x-slot:response>
                    {{-- prettier-ignore-start --}}
{
  "data": [
    {
      "id": 10762,
      "siruta_code": 114337,
      "name": "Mureșeni",
      "name_ascii": "mureseni",
      "type": 10,
      "type_label": "Componentă municipiu",
      "type_group": "localități",
      "postal_code": "540001",
      "lat": 46.522996,
      "lng": 24.520033,
      "parent": {
        "siruta_code": 114319,
        "name": "Târgu Mureș",
        "type": 1,
        "type_label": "Municipiu reședință de județ"
      }
    }
  ],
  "meta": {
    "county": {
      "id": 28,
      "siruta_code": 261,
      "name": "Mureș",
      "name_ascii": "mures",
      "abbr": "MS"
      "region": {
        "id": 7,
        "label": "Centru"
      },
    },
    "total": 518
  }
}{{-- prettier-ignore-end --}}</x-slot:response>
            </x-api-accordion>

            <x-api-accordion method="GET" url=" https://api.siruta.ro/v1/counties/{abbr}/localities/lite">
                <x-slot:description>
                    Returnează lista completă de localități pentru un județ, incluzând municipii, orașe, sate și coordonate
                    geografice.
                </x-slot:description>

                <x-slot:response>
                    {{-- prettier-ignore-start --}}
{
  "data": [
    {
      "id": 10762,
      "siruta_code": 114337,
      "name": "Mureșeni",
      "name_ascii": "mureseni",
      "postal_code": "540001"
    }
  ],
  "meta": {
    "county": {
      "id": 28,
      "siruta_code": 261,
      "name": "Mureș",
      "name_ascii": "mures",
      "abbr": "MS"
      "region": {
        "id": 7,
        "label": "Centru"
      },
    },
    "total": 518
  }
}{{-- prettier-ignore-end --}}</x-slot:response>
            </x-api-accordion>

            <x-api-accordion method="GET" url=" https://api.siruta.ro/v1/counties">
                <x-slot:description>
                    Returnează lista completă a județelor din România, inclusiv codul SIRUTA și abrevierea oficială.
                </x-slot:description>

                <x-slot:response>
                    {{-- prettier-ignore-start --}}
{
  "data": [
    {
      "id": 1,
      "siruta_code": 10,
      "name": "Alba",
      "name_ascii": "alba",
      "abbr": "AB",
      "region": {
        "id": 7,
        "label": "Centru"
      }
    },
  ],
  "meta": {
    "total": 42
  }
}{{-- prettier-ignore-end --}}</x-slot:response>
            </x-api-accordion>
        </div>
    </div>
@endsection
