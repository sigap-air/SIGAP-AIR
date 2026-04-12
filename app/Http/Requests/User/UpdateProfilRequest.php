<?php
/**
 * Validasi Form Edit Profil Pengguna
 * TANGGUNG JAWAB: Falah Adhi Chandra (PBI-08)
 */
namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:100',
            'no_telepon'    => 'nullable|string|max:20',
            'foto_profil'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password_lama' => 'nullable|string',
            'password_baru' => 'nullable|string|min:8|confirmed',
        ];
    }
}
