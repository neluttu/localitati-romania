@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-16">
        {{-- Hero --}}
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-gray-900 tracking-tight mb-4">
                Exemple de implementare
            </h1>
            <p class="text-lg text-gray-600 mb-6 max-w-2xl">
                Exemple practice pentru integrarea API-ului SIRUTA în aplicația ta.
                Copiază codul și adaptează-l la nevoile tale.
            </p>
        </div>

        {{-- Examples Grid --}}
        <div class="space-y-16">

            {{-- Example 1: Basic Dropdowns --}}
            <section id="dropdown" class="scroll-mt-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Dropdown-uri Județ + Localitate</h2>
                </div>

                <p class="text-gray-600 mb-6">
                    Selectează un județ și localitățile se încarcă automat. Cel mai comun caz de utilizare.
                </p>

                {{-- Live Demo --}}
                <div class="bg-gray-50 rounded-2xl p-8 mb-6">
                    <p class="text-sm text-gray-500 mb-4">Demo live</p>
                    <div class="grid md:grid-cols-3 gap-4 max-w-2xl" x-data="dropdownExample()">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Județ</label>
                            <select x-model="selectedCounty" @change="loadLocalities()"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20">
                                <option value="">Alege județul</option>
                                <template x-for="county in counties" :key="county.abbr">
                                    <option :value="county.abbr" x-text="county.name"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Localitate</label>
                            <select x-model="selectedLocality" @change="setPostalCode()"
                                :disabled="!localities.length"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 disabled:bg-gray-100">
                                <option value="">Alege localitatea</option>
                                <template x-for="loc in localities" :key="loc.siruta_code">
                                    <option :value="loc.siruta_code" :data-postal="loc.postal_code" x-text="loc.name"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cod poștal</label>
                            <input type="text" x-model="postalCode" readonly
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50"
                                placeholder="Se completează automat">
                        </div>
                    </div>
                </div>

                {{-- Code --}}
                <div class="border border-gray-200 rounded-2xl overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-700">JavaScript</span>
                        <button onclick="copyCode('code-dropdown')" class="text-xs text-purple-600 hover:text-purple-700">Copiază</button>
                    </div>
                    <pre id="code-dropdown" class="p-5 overflow-x-auto text-sm bg-gray-950"><code class="text-gray-300"><span class="text-gray-500">// Alpine.js component</span>
<span class="text-purple-400">function</span> <span class="text-blue-400">dropdownExample</span>() {
    <span class="text-purple-400">return</span> {
        counties: [],
        localities: [],
        selectedCounty: <span class="text-green-400">''</span>,
        selectedLocality: <span class="text-green-400">''</span>,
        postalCode: <span class="text-green-400">''</span>,

        <span class="text-purple-400">async</span> <span class="text-blue-400">init</span>() {
            <span class="text-gray-500">// Încarcă județele la start</span>
            <span class="text-purple-400">const</span> res = <span class="text-purple-400">await</span> <span class="text-blue-400">fetch</span>(<span class="text-green-400">'https://api.siruta.ro/v1/counties'</span>, {
                headers: { <span class="text-green-400">'X-Site-Token'</span>: <span class="text-green-400">'YOUR_TOKEN'</span> }
            });
            <span class="text-purple-400">const</span> data = <span class="text-purple-400">await</span> res.<span class="text-blue-400">json</span>();
            <span class="text-purple-400">this</span>.counties = data.data;
        },

        <span class="text-purple-400">async</span> <span class="text-blue-400">loadLocalities</span>() {
            <span class="text-purple-400">if</span> (!<span class="text-purple-400">this</span>.selectedCounty) <span class="text-purple-400">return</span>;

            <span class="text-purple-400">const</span> res = <span class="text-purple-400">await</span> <span class="text-blue-400">fetch</span>(
                <span class="text-green-400">`https://api.siruta.ro/v1/counties/</span><span class="text-amber-400">${this.selectedCounty}</span><span class="text-green-400">/localities/lite`</span>,
                { headers: { <span class="text-green-400">'X-Site-Token'</span>: <span class="text-green-400">'YOUR_TOKEN'</span> } }
            );
            <span class="text-purple-400">const</span> data = <span class="text-purple-400">await</span> res.<span class="text-blue-400">json</span>();
            <span class="text-purple-400">this</span>.localities = data.data;
            <span class="text-purple-400">this</span>.selectedLocality = <span class="text-green-400">''</span>;
            <span class="text-purple-400">this</span>.postalCode = <span class="text-green-400">''</span>;
        },

        <span class="text-blue-400">setPostalCode</span>() {
            <span class="text-purple-400">const</span> loc = <span class="text-purple-400">this</span>.localities.<span class="text-blue-400">find</span>(l => l.siruta_code == <span class="text-purple-400">this</span>.selectedLocality);
            <span class="text-purple-400">this</span>.postalCode = loc?.postal_code || <span class="text-green-400">''</span>;
        }
    }
}</code></pre>
                </div>
            </section>

            {{-- Example 2: Search Autocomplete --}}
            <section id="autocomplete" class="scroll-mt-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Căutare cu Autocomplete</h2>
                </div>

                <p class="text-gray-600 mb-6">
                    Caută localități în timp real pe măsură ce utilizatorul tastează.
                </p>

                {{-- Live Demo --}}
                <div class="bg-gray-50 rounded-2xl p-8 mb-6">
                    <p class="text-sm text-gray-500 mb-4">Demo live</p>
                    <div class="max-w-md relative" x-data="searchExample()">
                        <div class="relative">
                            <input type="text" x-model="query" @input.debounce.300ms="search()"
                                @focus="showResults = true"
                                @click.away="showResults = false"
                                class="w-full px-4 py-3 pl-12 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 bg-white"
                                placeholder="Caută o localitate (ex: Cluj, Brașov, Timișoara)...">
                            <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <div x-show="loading" class="absolute right-4 top-1/2 -translate-y-1/2">
                                <svg class="w-5 h-5 text-purple-500 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                            </div>
                        </div>
                        <div x-show="showResults && results.length > 0" x-transition
                            class="absolute left-0 right-0 mt-2 bg-white rounded-xl shadow-lg border border-gray-100 max-h-64 overflow-y-auto z-10">
                            <template x-for="result in results" :key="result.siruta_code">
                                <button @click="selectResult(result)"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 flex items-center justify-between border-b border-gray-50 last:border-0">
                                    <div>
                                        <span class="font-medium text-gray-900" x-text="result.name"></span>
                                        <span class="text-sm text-gray-500" x-text="', ' + result.county_name"></span>
                                    </div>
                                    <span class="text-xs text-gray-400" x-text="result.type_label"></span>
                                </button>
                            </template>
                        </div>
                        <div x-show="selected" x-transition class="mt-4 p-4 bg-white rounded-xl border border-gray-200">
                            <p class="text-sm text-gray-500 mb-1">Ai selectat:</p>
                            <p class="font-medium text-gray-900" x-text="selected?.name + ', ' + selected?.county_name"></p>
                            <p class="text-sm text-gray-600 mt-1">Cod poștal: <span class="font-medium" x-text="selected?.postal_code || 'N/A'"></span></p>
                        </div>
                    </div>
                </div>

                {{-- Code --}}
                <div class="border border-gray-200 rounded-2xl overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-700">JavaScript</span>
                        <button onclick="copyCode('code-search')" class="text-xs text-purple-600 hover:text-purple-700">Copiază</button>
                    </div>
                    <pre id="code-search" class="p-5 overflow-x-auto text-sm bg-gray-950"><code class="text-gray-300"><span class="text-purple-400">function</span> <span class="text-blue-400">searchExample</span>() {
    <span class="text-purple-400">return</span> {
        query: <span class="text-green-400">''</span>,
        results: [],
        selected: <span class="text-purple-400">null</span>,
        loading: <span class="text-purple-400">false</span>,
        showResults: <span class="text-purple-400">false</span>,

        <span class="text-purple-400">async</span> <span class="text-blue-400">search</span>() {
            <span class="text-purple-400">if</span> (<span class="text-purple-400">this</span>.query.length < <span class="text-amber-400">2</span>) {
                <span class="text-purple-400">this</span>.results = [];
                <span class="text-purple-400">return</span>;
            }

            <span class="text-purple-400">this</span>.loading = <span class="text-purple-400">true</span>;
            <span class="text-purple-400">const</span> res = <span class="text-purple-400">await</span> <span class="text-blue-400">fetch</span>(
                <span class="text-green-400">`https://api.siruta.ro/v1/localities?search=</span><span class="text-amber-400">${this.query}</span><span class="text-green-400">`</span>,
                { headers: { <span class="text-green-400">'X-Site-Token'</span>: <span class="text-green-400">'YOUR_TOKEN'</span> } }
            );
            <span class="text-purple-400">const</span> data = <span class="text-purple-400">await</span> res.<span class="text-blue-400">json</span>();
            <span class="text-purple-400">this</span>.results = data.data.<span class="text-blue-400">slice</span>(<span class="text-amber-400">0</span>, <span class="text-amber-400">10</span>);
            <span class="text-purple-400">this</span>.loading = <span class="text-purple-400">false</span>;
        },

        <span class="text-blue-400">selectResult</span>(result) {
            <span class="text-purple-400">this</span>.selected = result;
            <span class="text-purple-400">this</span>.query = result.name;
            <span class="text-purple-400">this</span>.showResults = <span class="text-purple-400">false</span>;
        }
    }
}</code></pre>
                </div>
            </section>

            {{-- Example 3: PHP/Laravel --}}
            <section id="laravel" class="scroll-mt-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M23.642 5.43a.364.364 0 01.014.1v5.149c0 .135-.073.26-.189.326l-4.323 2.49v4.934a.378.378 0 01-.188.326L9.93 23.949a.316.316 0 01-.066.027c-.008.002-.016.008-.024.01a.348.348 0 01-.192 0c-.011-.002-.02-.008-.03-.012-.02-.006-.043-.012-.063-.023L.533 18.755a.376.376 0 01-.189-.326V2.974c0-.033.005-.066.014-.098.003-.012.01-.02.014-.032a.369.369 0 01.023-.058c.004-.013.015-.022.023-.033l.033-.045c.012-.01.025-.018.037-.027.014-.012.027-.024.041-.034L4.792.044a.378.378 0 01.377 0L9.43 2.647a.326.326 0 01.04.035c.011.009.025.018.036.027l.032.045c.01.012.02.021.025.033a.38.38 0 01.022.058c.005.012.013.022.015.033.01.031.014.064.014.098v9.652l3.76-2.164V5.527c0-.033.004-.066.013-.098.003-.01.01-.02.013-.032a.487.487 0 01.024-.059c.007-.012.018-.02.025-.033.012-.015.021-.03.033-.043.012-.012.025-.02.037-.028.013-.012.027-.024.041-.035l4.26-2.6a.378.378 0 01.378 0l4.261 2.6c.015.011.028.023.042.034.012.01.025.018.036.028.012.014.023.028.034.044.008.012.019.021.024.033a.291.291 0 01.023.058c.006.012.012.021.015.033zm-.74 5.032V6.179l-1.578.908-2.182 1.256v4.283l3.76-2.164zm-4.26 7.317v-4.287l-2.147 1.225-6.158 3.514v4.327l8.305-4.779zM1.084 3.587v14.919l8.305 4.779v-4.327l-4.32-2.45-.002-.001c-.013-.008-.025-.018-.037-.027a.316.316 0 01-.033-.03l-.001-.002c-.01-.012-.021-.025-.03-.039-.008-.012-.017-.024-.023-.038l-.001-.003c-.007-.014-.012-.03-.017-.045-.004-.016-.01-.03-.013-.046v-.002c-.003-.017-.004-.033-.004-.05v-9.94L1.084 3.587zm3.96-2.812L1.285 2.974l3.76 2.164 3.759-2.164-3.76-2.2zm1.912 12.752l2.182-1.256V3.587l-1.579.91-2.182 1.255v8.684l1.579-.91zm10.568-9.749l-3.76 2.2 3.76 2.163 3.759-2.164-3.759-2.2zm-.189 4.973l-2.182-1.256-1.578-.909v4.283l2.182 1.256 1.578.908V8.751zm-8.494 9.27l5.54-3.159 2.762-1.574-3.756-2.162-4.323 2.49-3.986 2.296 3.763 2.109z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Laravel / PHP</h2>
                </div>

                <p class="text-gray-600 mb-6">
                    Integrare server-side cu Laravel HTTP Client sau cURL.
                </p>

                {{-- Code --}}
                <div class="border border-gray-200 rounded-2xl overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-700">PHP - Laravel Service</span>
                        <button onclick="copyCode('code-laravel')" class="text-xs text-purple-600 hover:text-purple-700">Copiază</button>
                    </div>
                    <pre id="code-laravel" class="p-5 overflow-x-auto text-sm bg-gray-950"><code class="text-gray-300"><span class="text-gray-500">// app/Services/SirutaService.php</span>

<span class="text-purple-400">namespace</span> App\Services;

<span class="text-purple-400">use</span> Illuminate\Support\Facades\<span class="text-blue-400">Http</span>;
<span class="text-purple-400">use</span> Illuminate\Support\Facades\<span class="text-blue-400">Cache</span>;

<span class="text-purple-400">class</span> <span class="text-blue-400">SirutaService</span>
{
    <span class="text-purple-400">private string</span> $baseUrl = <span class="text-green-400">'https://api.siruta.ro/v1'</span>;
    <span class="text-purple-400">private string</span> $token;

    <span class="text-purple-400">public function</span> <span class="text-blue-400">__construct</span>()
    {
        <span class="text-purple-400">$this</span>->token = <span class="text-blue-400">config</span>(<span class="text-green-400">'services.siruta.token'</span>);
    }

    <span class="text-purple-400">public function</span> <span class="text-blue-400">getCounties</span>(): <span class="text-blue-400">array</span>
    {
        <span class="text-purple-400">return</span> <span class="text-blue-400">Cache</span>::<span class="text-blue-400">remember</span>(<span class="text-green-400">'siruta_counties'</span>, <span class="text-amber-400">86400</span>, <span class="text-purple-400">function</span> () {
            <span class="text-purple-400">$response</span> = <span class="text-blue-400">Http</span>::<span class="text-blue-400">withHeaders</span>([
                <span class="text-green-400">'X-Site-Token'</span> => <span class="text-purple-400">$this</span>->token,
            ])-><span class="text-blue-400">get</span>(<span class="text-green-400">"{$this->baseUrl}/counties"</span>);

            <span class="text-purple-400">return</span> <span class="text-purple-400">$response</span>-><span class="text-blue-400">json</span>(<span class="text-green-400">'data'</span>);
        });
    }

    <span class="text-purple-400">public function</span> <span class="text-blue-400">getLocalities</span>(<span class="text-blue-400">string</span> <span class="text-purple-400">$countyAbbr</span>): <span class="text-blue-400">array</span>
    {
        <span class="text-purple-400">return</span> <span class="text-blue-400">Cache</span>::<span class="text-blue-400">remember</span>(<span class="text-green-400">"siruta_localities_{$countyAbbr}"</span>, <span class="text-amber-400">3600</span>, <span class="text-purple-400">function</span> () <span class="text-purple-400">use</span> (<span class="text-purple-400">$countyAbbr</span>) {
            <span class="text-purple-400">$response</span> = <span class="text-blue-400">Http</span>::<span class="text-blue-400">withHeaders</span>([
                <span class="text-green-400">'X-Site-Token'</span> => <span class="text-purple-400">$this</span>->token,
            ])-><span class="text-blue-400">get</span>(<span class="text-green-400">"{$this->baseUrl}/counties/{$countyAbbr}/localities"</span>);

            <span class="text-purple-400">return</span> <span class="text-purple-400">$response</span>-><span class="text-blue-400">json</span>(<span class="text-green-400">'data'</span>);
        });
    }
}</code></pre>
                </div>
            </section>

            {{-- Example 4: React/Vue --}}
            <section id="react" class="scroll-mt-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-cyan-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-cyan-600" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M14.23 12.004a2.236 2.236 0 0 1-2.235 2.236 2.236 2.236 0 0 1-2.236-2.236 2.236 2.236 0 0 1 2.235-2.236 2.236 2.236 0 0 1 2.236 2.236zm2.648-10.69c-1.346 0-3.107.96-4.888 2.622-1.78-1.653-3.542-2.602-4.887-2.602-.41 0-.783.093-1.106.278-1.375.793-1.683 3.264-.973 6.365C1.98 8.917 0 10.42 0 12.004c0 1.59 1.99 3.097 5.043 4.03-.704 3.113-.39 5.588.988 6.38.32.187.69.275 1.102.275 1.345 0 3.107-.96 4.888-2.624 1.78 1.654 3.542 2.603 4.887 2.603.41 0 .783-.09 1.106-.275 1.374-.792 1.683-3.263.973-6.365C22.02 15.096 24 13.59 24 12.004c0-1.59-1.99-3.097-5.043-4.032.704-3.11.39-5.587-.988-6.38-.318-.184-.688-.277-1.092-.278zm-.005 1.09v.006c.225 0 .406.044.558.127.666.382.955 1.835.73 3.704-.054.46-.142.945-.25 1.44-.96-.236-2.006-.417-3.107-.534-.66-.905-1.345-1.727-2.035-2.447 1.592-1.48 3.087-2.292 4.105-2.295zm-9.77.02c1.012 0 2.514.808 4.11 2.28-.686.72-1.37 1.537-2.02 2.442-1.107.117-2.154.298-3.113.538-.112-.49-.195-.964-.254-1.42-.23-1.868.054-3.32.714-3.707.19-.09.4-.127.563-.132zm4.882 3.05c.455.468.91.992 1.36 1.564-.44-.02-.89-.034-1.345-.034-.46 0-.915.01-1.36.034.44-.572.895-1.096 1.345-1.565zM12 8.1c.74 0 1.477.034 2.202.093.406.582.802 1.203 1.183 1.86.372.64.71 1.29 1.018 1.946-.308.655-.646 1.31-1.013 1.95-.38.66-.773 1.288-1.18 1.87-.728.063-1.466.098-2.21.098-.74 0-1.477-.035-2.202-.093-.406-.582-.802-1.204-1.183-1.86-.372-.64-.71-1.29-1.018-1.946.303-.657.646-1.313 1.013-1.954.38-.66.773-1.286 1.18-1.868.728-.064 1.466-.098 2.21-.098zm-3.635.254c-.24.377-.48.763-.704 1.16-.225.39-.435.782-.635 1.174-.265-.656-.49-1.31-.676-1.947.64-.15 1.315-.283 2.015-.386zm7.26 0c.695.103 1.365.23 2.006.387-.18.632-.405 1.282-.66 1.933-.2-.39-.41-.783-.64-1.174-.225-.392-.465-.774-.705-1.146zm3.063.675c.484.15.944.317 1.375.498 1.732.74 2.852 1.708 2.852 2.476-.005.768-1.125 1.74-2.857 2.475-.42.18-.88.342-1.355.493-.28-.958-.646-1.956-1.1-2.98.45-1.017.81-2.01 1.085-2.964zm-13.395.004c.278.96.645 1.957 1.1 2.98-.45 1.017-.812 2.01-1.086 2.964-.484-.15-.944-.318-1.37-.5-1.732-.737-2.852-1.706-2.852-2.474 0-.768 1.12-1.742 2.852-2.476.42-.18.88-.342 1.356-.494zm11.678 4.28c.265.657.49 1.312.676 1.948-.64.157-1.316.29-2.016.39.24-.375.48-.762.705-1.158.225-.39.435-.788.636-1.18zm-9.945.02c.2.392.41.783.64 1.175.23.39.465.772.705 1.143-.695-.102-1.365-.23-2.006-.386.18-.63.406-1.282.66-1.933zM17.92 16.32c.112.493.2.968.254 1.423.23 1.868-.054 3.32-.714 3.708-.147.09-.338.128-.563.128-1.012 0-2.514-.807-4.11-2.28.686-.72 1.37-1.536 2.02-2.44 1.107-.118 2.154-.3 3.113-.54zm-11.83.01c.96.234 2.006.415 3.107.532.66.905 1.345 1.727 2.035 2.446-1.595 1.483-3.092 2.295-4.11 2.295-.22-.005-.406-.05-.553-.132-.666-.38-.955-1.834-.73-3.703.054-.46.142-.944.25-1.438zm4.56.64c.44.02.89.034 1.345.034.46 0 .915-.01 1.36-.034-.44.572-.895 1.095-1.345 1.565-.455-.47-.91-.993-1.36-1.565z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">React Hook</h2>
                </div>

                <p class="text-gray-600 mb-6">
                    Custom hook pentru React cu loading state și error handling.
                </p>

                {{-- Code --}}
                <div class="border border-gray-200 rounded-2xl overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-700">React - useSiruta Hook</span>
                        <button onclick="copyCode('code-react')" class="text-xs text-purple-600 hover:text-purple-700">Copiază</button>
                    </div>
                    <pre id="code-react" class="p-5 overflow-x-auto text-sm bg-gray-950"><code class="text-gray-300"><span class="text-gray-500">// hooks/useSiruta.js</span>

<span class="text-purple-400">import</span> { useState, useEffect } <span class="text-purple-400">from</span> <span class="text-green-400">'react'</span>;

<span class="text-purple-400">const</span> API_URL = <span class="text-green-400">'https://api.siruta.ro/v1'</span>;
<span class="text-purple-400">const</span> TOKEN = <span class="text-green-400">'YOUR_TOKEN'</span>;

<span class="text-purple-400">export function</span> <span class="text-blue-400">useSiruta</span>() {
    <span class="text-purple-400">const</span> [counties, setCounties] = <span class="text-blue-400">useState</span>([]);
    <span class="text-purple-400">const</span> [localities, setLocalities] = <span class="text-blue-400">useState</span>([]);
    <span class="text-purple-400">const</span> [loading, setLoading] = <span class="text-blue-400">useState</span>(<span class="text-purple-400">false</span>);
    <span class="text-purple-400">const</span> [error, setError] = <span class="text-blue-400">useState</span>(<span class="text-purple-400">null</span>);

    <span class="text-purple-400">const</span> headers = { <span class="text-green-400">'X-Site-Token'</span>: TOKEN };

    <span class="text-blue-400">useEffect</span>(() => {
        <span class="text-blue-400">fetch</span>(<span class="text-green-400">`</span><span class="text-amber-400">${API_URL}</span><span class="text-green-400">/counties`</span>, { headers })
            .<span class="text-blue-400">then</span>(res => res.<span class="text-blue-400">json</span>())
            .<span class="text-blue-400">then</span>(data => <span class="text-blue-400">setCounties</span>(data.data))
            .<span class="text-blue-400">catch</span>(err => <span class="text-blue-400">setError</span>(err.message));
    }, []);

    <span class="text-purple-400">const</span> <span class="text-blue-400">loadLocalities</span> = <span class="text-purple-400">async</span> (countyAbbr) => {
        <span class="text-blue-400">setLoading</span>(<span class="text-purple-400">true</span>);
        <span class="text-purple-400">try</span> {
            <span class="text-purple-400">const</span> res = <span class="text-purple-400">await</span> <span class="text-blue-400">fetch</span>(
                <span class="text-green-400">`</span><span class="text-amber-400">${API_URL}</span><span class="text-green-400">/counties/</span><span class="text-amber-400">${countyAbbr}</span><span class="text-green-400">/localities`</span>,
                { headers }
            );
            <span class="text-purple-400">const</span> data = <span class="text-purple-400">await</span> res.<span class="text-blue-400">json</span>();
            <span class="text-blue-400">setLocalities</span>(data.data);
        } <span class="text-purple-400">catch</span> (err) {
            <span class="text-blue-400">setError</span>(err.message);
        } <span class="text-purple-400">finally</span> {
            <span class="text-blue-400">setLoading</span>(<span class="text-purple-400">false</span>);
        }
    };

    <span class="text-purple-400">return</span> { counties, localities, loading, error, loadLocalities };
}</code></pre>
                </div>
            </section>

            {{-- CTA --}}
            <div class="bg-gradient-to-r from-purple-500 to-blue-500 rounded-2xl p-8 text-center">
                <h2 class="text-2xl font-bold text-white mb-3">Ai nevoie de ajutor?</h2>
                <p class="text-white/80 mb-6">Consultă documentația completă sau contactează-ne.</p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('docs') }}" class="px-6 py-2.5 bg-white text-purple-600 font-medium rounded-full hover:bg-gray-100 transition-colors">
                        Vezi documentația
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyCode(id) {
            const code = document.getElementById(id).innerText;
            navigator.clipboard.writeText(code);
        }

        // Alpine components for demos - defined as global functions
        function dropdownExample() {
            return {
                counties: [
                    { abbr: 'AB', name: 'Alba' },
                    { abbr: 'AR', name: 'Arad' },
                    { abbr: 'B', name: 'București' },
                    { abbr: 'CJ', name: 'Cluj' },
                    { abbr: 'MS', name: 'Mureș' },
                    { abbr: 'TM', name: 'Timiș' },
                ],
                localities: [],
                selectedCounty: '',
                selectedLocality: '',
                postalCode: '',

                loadLocalities() {
                    if (!this.selectedCounty) return;
                    // Demo data based on selected county
                    const demoData = {
                        'AB': [
                            { siruta_code: 1001, name: 'Alba Iulia', postal_code: '510000' },
                            { siruta_code: 1002, name: 'Sebeș', postal_code: '515800' },
                            { siruta_code: 1003, name: 'Aiud', postal_code: '515200' },
                        ],
                        'AR': [
                            { siruta_code: 2001, name: 'Arad', postal_code: '310000' },
                            { siruta_code: 2002, name: 'Ineu', postal_code: '315300' },
                            { siruta_code: 2003, name: 'Lipova', postal_code: '315400' },
                        ],
                        'B': [
                            { siruta_code: 3001, name: 'Sector 1', postal_code: '010000' },
                            { siruta_code: 3002, name: 'Sector 2', postal_code: '020000' },
                            { siruta_code: 3003, name: 'Sector 3', postal_code: '030000' },
                        ],
                        'CJ': [
                            { siruta_code: 4001, name: 'Cluj-Napoca', postal_code: '400000' },
                            { siruta_code: 4002, name: 'Turda', postal_code: '401100' },
                            { siruta_code: 4003, name: 'Dej', postal_code: '405200' },
                        ],
                        'MS': [
                            { siruta_code: 5001, name: 'Târgu Mureș', postal_code: '540000' },
                            { siruta_code: 5002, name: 'Reghin', postal_code: '545300' },
                            { siruta_code: 5003, name: 'Sighișoara', postal_code: '545400' },
                        ],
                        'TM': [
                            { siruta_code: 6001, name: 'Timișoara', postal_code: '300000' },
                            { siruta_code: 6002, name: 'Lugoj', postal_code: '305500' },
                            { siruta_code: 6003, name: 'Sânnicolau Mare', postal_code: '305600' },
                        ],
                    };
                    this.localities = demoData[this.selectedCounty] || [];
                    this.selectedLocality = '';
                    this.postalCode = '';
                },

                setPostalCode() {
                    const loc = this.localities.find(l => l.siruta_code == this.selectedLocality);
                    this.postalCode = loc?.postal_code || '';
                }
            };
        }

        function searchExample() {
            return {
                query: '',
                results: [],
                selected: null,
                loading: false,
                showResults: false,

                // Demo localities for search
                demoLocalities: [
                    { siruta_code: 1, name: 'Cluj-Napoca', county_name: 'Cluj', type_label: 'Municipiu', postal_code: '400000' },
                    { siruta_code: 2, name: 'Constanța', county_name: 'Constanța', type_label: 'Municipiu', postal_code: '900000' },
                    { siruta_code: 3, name: 'Craiova', county_name: 'Dolj', type_label: 'Municipiu', postal_code: '200000' },
                    { siruta_code: 4, name: 'Călărași', county_name: 'Călărași', type_label: 'Municipiu', postal_code: '910000' },
                    { siruta_code: 5, name: 'Timișoara', county_name: 'Timiș', type_label: 'Municipiu', postal_code: '300000' },
                    { siruta_code: 6, name: 'Târgu Mureș', county_name: 'Mureș', type_label: 'Municipiu', postal_code: '540000' },
                    { siruta_code: 7, name: 'București', county_name: 'București', type_label: 'Capitală', postal_code: '010000' },
                    { siruta_code: 8, name: 'Brașov', county_name: 'Brașov', type_label: 'Municipiu', postal_code: '500000' },
                    { siruta_code: 9, name: 'Brăila', county_name: 'Brăila', type_label: 'Municipiu', postal_code: '810000' },
                    { siruta_code: 10, name: 'Buzău', county_name: 'Buzău', type_label: 'Municipiu', postal_code: '120000' },
                ],

                async search() {
                    if (this.query.length < 2) {
                        this.results = [];
                        return;
                    }
                    this.loading = true;
                    this.showResults = true;
                    // Simulate API delay
                    await new Promise(r => setTimeout(r, 300));
                    this.results = this.demoLocalities.filter(r =>
                        r.name.toLowerCase().includes(this.query.toLowerCase())
                    ).slice(0, 5);
                    this.loading = false;
                },

                selectResult(result) {
                    this.selected = result;
                    this.query = result.name;
                    this.showResults = false;
                }
            };
        }
    </script>
</div>
@endsection
