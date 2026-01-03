<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-text-primary mb-4">Foto de Perfil</h3>

    {{-- Success Message --}}
    @if($successMessage)
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
            <p class="text-sm">{{ $successMessage }}</p>
        </div>
    @endif

    {{-- Error Message --}}
    @if($errorMessage)
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
            <p class="text-sm">{{ $errorMessage }}</p>
        </div>
    @endif

    <div class="flex items-start gap-6">
        {{-- Current Avatar --}}
        <div class="flex-shrink-0">
            <img 
                src="{{ auth()->user()->avatar_url }}" 
                alt="{{ auth()->user()->full_name }}"
                class="w-24 h-24 rounded-full object-cover border-2 border-gray-200"
            >
        </div>

        {{-- Upload Form --}}
        <div class="flex-1">
            <form wire:submit.prevent="uploadAvatar" class="space-y-4">
                <div>
                    <label for="avatar" class="block text-sm font-medium text-text-primary mb-2">
                        Elegir nueva imagen
                    </label>
                    <input 
                        type="file" 
                        id="avatar"
                        wire:model="avatar"
                        accept="image/*"
                        class="w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-lg file:border-0
                            file:text-sm file:font-semibold
                            file:bg-primary-50 file:text-primary-700
                            hover:file:bg-primary-100
                            cursor-pointer"
                    >
                    <p class="mt-1 text-xs text-text-secondary">
                        JPG, PNG o GIF. Máximo 2MB.
                    </p>
                    @error('avatar') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                    @enderror
                </div>

                {{-- Preview --}}
                @if ($avatar)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <p class="text-sm font-medium text-text-primary mb-2">Vista previa:</p>
                        <img 
                            src="{{ $avatar->temporaryUrl() }}" 
                            class="w-32 h-32 rounded-full object-cover"
                        >
                    </div>
                @endif

                <div class="flex gap-3">
                    <button 
                        type="submit"
                        class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2.5 rounded-lg font-medium transition-colors disabled:opacity-50"
                        wire:loading.attr="disabled"
                        wire:target="avatar"
                    >
                        <span wire:loading.remove wire:target="uploadAvatar">Subir Avatar</span>
                        <span wire:loading wire:target="uploadAvatar">Subiendo...</span>
                    </button>

                    @if(auth()->user()->avatar_path)
                        <button 
                            type="button"
                            wire:click="removeAvatar"
                            wire:confirm="¿Estás seguro de que quieres eliminar tu avatar?"
                            class="px-6 py-2.5 border border-red-300 text-red-600 rounded-lg font-medium hover:bg-red-50 transition-colors"
                        >
                            Eliminar Avatar
                        </button>
                    @endif
                </div>
            </form>

            <div wire:loading wire:target="avatar" class="mt-2">
                <p class="text-sm text-primary-600">Cargando imagen...</p>
            </div>
        </div>
    </div>
</div>
