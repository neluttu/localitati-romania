@php
    $types = ['success', 'error', 'warning', 'info'];

    $colors = [
        'success' => 'bg-green-100 text-green-800 border-green-300',
        'error' => 'bg-red-100 text-red-800 border-red-300',
        'warning' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
        'info' => 'bg-blue-100 text-blue-800 border-blue-300',
    ];

    $icons = [
        'success' => 'check-circle',
        'error' => 'x-circle',
        'warning' => 'exclamation-triangle',
        'info' => 'information-circle',
    ];
@endphp


<div class="w-full">

    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())
        <div class="mb-4 px-4 py-3 rounded-lg border bg-red-100 text-red-800 border-red-300">
            <div class="flex items-center gap-2">
                @svg('heroicon-s-x-circle', 'w-6 h-6 text-red-700')
                <strong>Au apărut erori:</strong>
            </div>

            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button onclick="this.parentElement.remove()"
                class="font-bold text-xl leading-none opacity-60 hover:opacity-100">
                @svg('heroicon-m-x-circle', 'w-6 h-6')
            </button>
        </div>
    @endif


    {{-- FLASH MESSAGES --}}
    @foreach ($types as $type)
        @if (session($type))
            <div
                class="mb-4 flex items-center max-w-7xl mx-auto justify-between px-4 py-3 rounded-lg border {{ $colors[$type] }}">
                <div class="flex items-center gap-2">
                    @svg('heroicon-o-' . $icons[$type], 'w-6 h-6')
                    <span class="font-medium">{{ session($type) }}</span>
                </div>

                <button onclick="this.parentElement.remove()"
                    class="font-bold text-3xl cursor-pointer leading-none opacity-60 hover:opacity-100">
                    @svg('heroicon-m-x-circle', 'w-6 h-6')
                </button>
            </div>
        @endif
    @endforeach

</div>
