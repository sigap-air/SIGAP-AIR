<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * PBI #8 — Validasi Update Profil
 */
class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'email'       => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'no_telepon'  => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hapus_foto'  => ['nullable', 'boolean'],
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'name.required'       => 'Nama lengkap wajib diisi.',
            'name.max'            => 'Nama maksimal 255 karakter.',
            'email.required'      => 'Email wajib diisi.',
            'email.email'         => 'Format email tidak valid.',
            'email.unique'        => 'Email sudah digunakan oleh akun lain.',
            'no_telepon.max'      => 'Nomor telepon maksimal 20 karakter.',
            'no_telepon.regex'    => 'Format nomor telepon tidak valid.',
            'foto_profil.image'   => 'File harus berupa gambar.',
            'foto_profil.mimes'   => 'Format gambar harus JPG, PNG, atau WebP.',
            'foto_profil.max'     => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
