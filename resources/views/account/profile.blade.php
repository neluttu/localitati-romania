@extends('layouts.app')

@section('content')
    <div class="w-full max-w-3xl mx-auto flex items-start justify-start flex-col md:flex-row gap-4 md:gap-10 mt-20"
        data-load-modal>
        <form id="avatar_delete" method="post" action="{{ route('account.profile.avatar.delete') }}" class="hidden">
            @method('DELETE')
            @csrf
        </form>
        <form action="{{ route('account.profile.update') }}" method="POST" enctype="multipart/form-data"
            class="space-y-8 w-full">
            @csrf
            @method('PUT')

            {{-- Avatar --}}
            <div class="flex items-start gap-6 w-full">
                <div class="max-w-56 w-full">
                    <div class="aspect-square w-full mx-auto rounded-lg overflow-hidden relative">
                        @if ($profile->avatar)
                            <button type="button"
                                class="p-3 bg-white group flex items-center text-purple-600 transition-all w-auto duration-300 ease-in-out cursor-pointer absolute top-0 right-0 rounded-bl-lg rounded-tr-lg"
                                data-modal-target="modal" data-title="Confirmă acțiunea"
                                data-message="Ești sigur că vrei să ștergi imaginea de avatar?" data-confirm="Da, șterge"
                                data-cancel="Anulează" data-form-id="avatar_delete">
                                <x-heroicon-o-trash class="w-5 h-5 inline-block shrink-0" />
                            </button>
                        @endif
                        <img src="{{ $profile->avatar_url }}" alt="Avatar"
                            class="w-full h-full object-cover overflow-hidden rounded-xl">
                    </div>
                    <label class="block mt-4">
                        <input type="file" name="avatar" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-700 
                                  file:mr-4 file:py-2 file:px-4 file:rounded-full
                                  file:border-0 file:text-sm file:font-normal
                                  file:bg-pink-50 file:text-purple-600
                                  hover:file:bg-purple-100 cursor-pointer">
                    </label>
                </div>
                <div class="flex-1 space-y-6">

                    <div class="w-full">
                        <label for="first_name" class="block text-sm font-medium text-gray-700">Prenume</label>
                        <x-form-input name="first_name" type="text" label="Prenume" :value="old('first_name', $profile->first_name ?? '')" />
                    </div>
                    <div class="w-full">
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Nume</label>
                        <x-form-input name="last_name" type="text" label="Nume" :value="old('last_name', $profile->last_name ?? '')" />
                    </div>
                    <div class="w-full">
                        <label for="email" class="block text-sm font-medium text-gray-700">Adresă email</label>
                        <x-form-input name="email" type="text" label="Email" :value="$user->email" readonly
                            class="bg-gray-100 text-gray-500 border-gray-200 cursor-not-allowed" />
                    </div>
                    <div class="w-full">
                        <label for="phone" class="block text-sm font-medium text-gray-700">Telefon</label>
                        <x-form-input name="phone" type="text" id="phone" :value="old('phone', $profile->phone ?? '')" />
                    </div>
                    <button type="submit"
                        class="px-6 py-1.5 rounded-lg bg-purple-500 hover:bg-purple-600 text-white font-normal cursor-pointer">
                        Salvează modificările
                    </button>
                </div>
            </div>

        </form>
        <x-modal />
    </div>
@endsection
