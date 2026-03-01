<div class="api-accordion w-full font-mono bg-zinc-800 text-zinc-100 rounded-xl shadow-lg overflow-hidden">
    <div type="button" class="w-full text-left p-6 flex items-start justify-between gap-4">

        <div>
            <div class="flex items-center gap-2 mb-4 text-xs text-zinc-400">
                <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                <span class="w-3 h-3 bg-yellow-400 rounded-full "></span>
                <span class="w-3 h-3 bg-green-500 rounded-full"></span>
            </div>
            <div class="mb-3 text-sm">
                <span class="text-green-400">$</span>
                <span class="text-blue-400">{{ strtoupper($method) }}</span>
                <span class="text-zinc-200">{{ $url }}</span>
            </div>
            @isset($description)
                <p class="text-zinc-400 text-sm">
                    {{ $description }}
                </p>
            @endisset
        </div>
        <svg class="chevron accordion-toggle mt-1 shrink-0 cursor-pointer transition-transform duration-300"
            width="20" height="20" viewBox="0 0 24 24" fill="none">
            <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
    </div>

    {{-- response --}}
    <div class="accordion-content overflow-hidden transition-all duration-300" style="max-height: 0; opacity: 0;">
        <pre class="bg-purple-600 rounded-lg p-4 text-sm overflow-x-auto m-4 mt-0 text-white">{{ trim($response ?? '') }}</pre>
    </div>
</div>
