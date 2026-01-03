<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;

class UploadAvatar extends Component
{
    use WithFileUploads;

    public $avatar;
    public ?string $successMessage = null;
    public ?string$errorMessage = null;

    protected $rules = [
        'avatar' => 'required|image|max:2048', // 2MB max
    ];

    protected $messages = [
        'avatar.required' => 'Debes seleccionar una imagen',
        'avatar.image' => 'El archivo debe ser una imagen',
        'avatar.max' => 'La imagen no debe superar 2MB',
    ];

    public function uploadAvatar()
    {
        $this->validate();
        
        $this->reset(['successMessage', 'errorMessage']);

        try {
            $user = auth()->user();

            // Delete old avatar if exists
            if ($user->avatar_path && Storage::exists($user->avatar_path)) {
                Storage::delete($user->avatar_path);
            }

            // Store new avatar
            $path = $this->avatar->store('avatars', 'public');

            // Update user
            $user->update(['avatar_path' => $path]);

            $this->successMessage = 'Avatar actualizado correctamente';
            $this->reset('avatar');

            // Refresh component to show new avatar
            $this->dispatch('avatar-updated');

        } catch (\Exception $e) {
            \Log::error('[UPLOAD_AVATAR] Error al subir avatar', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            $this->errorMessage = 'Error al subir el avatar';
        }
    }

    public function removeAvatar()
    {
        try {
            $user = auth()->user();

            if ($user->avatar_path && Storage::exists($user->avatar_path)) {
                Storage::delete($user->avatar_path);
            }

            $user->update(['avatar_path' => null]);

            $this->successMessage = 'Avatar eliminado correctamente';
            $this->dispatch('avatar-updated');

        } catch (\Exception $e) {
            \Log::error('[UPLOAD_AVATAR] Error al eliminar avatar', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            $this->errorMessage = 'Error al eliminar el avatar';
        }
    }

    public function render()
    {
        return view('livewire.profile.upload-avatar');
    }
}
