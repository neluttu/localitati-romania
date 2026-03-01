<div id="modal"
    class="fixed inset-0 items-center justify-center hidden transition-opacity duration-500 opacity-0 pointer-events-auto modal bg-black/50 backdrop-blur-md z-50">
    <div class="w-full max-w-md p-8 mx-auto bg-white rounded-md shadow-md pointer-events-auto modal-content">
        <div class="space-y-2 gap-5">
            <x-heroicon-o-chat-bubble-bottom-center-text class="w-8 h-8 text-red-600 shrink-0" />
            <h1 class="text-lg font-semibold"></h1>
            <p class="text-base -mt-2"></p>
        </div>
        <div class="flex justify-start gap-4 mt-5">
            <button type="button" class="px-6 py-1.5 cursor-pointer text-white bg-red-500 rounded hover:bg-red-600"
                data-modal-confirm>
                Confirmă
            </button>
            <button data-modal-cancel type="button"
                class="grow px-6 py-1.5 text-black bg-gray-100 border cursor-pointer border-gray-300 rounded modal-close hover:bg-gray-200">
                Anulează
            </button>
        </div>
    </div>
</div>
