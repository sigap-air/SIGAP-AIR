<?php
/**
 * PBI-16 — Manajemen User & Role
 * TANGGUNG JAWAB: Farisha Huwaida Shofha
 */
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'nama'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'username'              => 'required|string|max:100|unique:users,username|regex:/^\S+$/',
            'role'                  => 'required|in:admin,supervisor,petugas,masyarakat',
            'no_telepon'            => 'nullable|string|max:20',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
            'zona_id'               => 'required_if:role,petugas|nullable|exists:zona_wilayah,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'               => 'Nama lengkap wajib diisi.',
            'email.required'              => 'Email wajib diisi.',
            'email.email'                 => 'Format email tidak valid.',
            'email.unique'                => 'Email sudah digunakan oleh pengguna lain.',
            'username.required'           => 'Username wajib diisi.',
            'username.unique'             => 'Username sudah digunakan.',
            'username.regex'              => 'Username tidak boleh mengandung spasi.',
            'username.max'                => 'Username maksimal 100 karakter.',
            'role.required'               => 'Role wajib dipilih.',
            'role.in'                     => 'Role tidak valid.',
            'password.required'           => 'Password wajib diisi.',
            'password.min'                => 'Password minimal 8 karakter.',
            'password.confirmed'          => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'zona_id.required_if'         => 'Zona wajib dipilih untuk petugas.',
            'zona_id.exists'              => 'Zona yang dipilih tidak valid.',
        ];
    }
}
