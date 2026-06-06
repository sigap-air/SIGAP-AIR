<?php

/**
 * PBI-16 — Form Request: Tambah Petugas Teknis Baru
 * TANGGUNG JAWAB: Farisha Huwaida Shofha
 */

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePetugasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            // Data User
            'name'        => [
                'required', 'string', 'max:255',
                // Nama tidak boleh sama dengan petugas lain yang sudah terdaftar
                \Illuminate\Validation\Rule::unique('users', 'name')->where(fn ($q) => $q->where('role', 'petugas')),
            ],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email', 'regex:/@pdam\.go\.id$/'],
            'username'    => ['required', 'string', 'max:100', 'unique:users,username'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'no_telepon'  => ['required', 'numeric', 'unique:users,no_telepon'],
            // Foto profil wajib saat tambah petugas baru
            'foto_profil' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],

            // Data Petugas — NIP auto-generated, tidak perlu diisi manual
            'nip'             => ['nullable', 'string', 'max:50'],
            'zona_id'         => ['nullable', 'exists:zona_wilayah,id'],
            'status_tersedia' => ['required', 'in:tersedia,sibuk,tidak_aktif'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'            => 'Nama lengkap wajib diisi.',
            'name.unique'              => 'Nama petugas ini sudah terdaftar di sistem.',
            'email.required'           => 'Email wajib diisi.',
            'email.unique'             => 'Email sudah digunakan oleh akun lain.',
            'email.regex'              => 'Email harus menggunakan domain @pdam.go.id.',
            'username.required'        => 'Username wajib diisi.',
            'username.unique'          => 'Username sudah digunakan.',
            'password.required'        => 'Password wajib diisi.',
            'password.min'             => 'Password minimal 8 karakter.',
            'password.confirmed'       => 'Konfirmasi password tidak cocok.',
            'nip.unique'               => 'NIP sudah terdaftar untuk petugas lain.',
            'zona_id.exists'           => 'Zona wilayah yang dipilih tidak valid.',
            'status_tersedia.required' => 'Status ketersediaan wajib dipilih.',
            'status_tersedia.in'       => 'Status tidak valid.',
            'no_telepon.required'      => 'Nomor telepon wajib diisi.',
            'no_telepon.numeric'       => 'Nomor telepon hanya boleh berisi angka.',
            'no_telepon.unique'        => 'Nomor telepon sudah terdaftar untuk akun lain.',
            'foto_profil.required'     => 'Foto profil wajib diunggah.',
            'foto_profil.image'        => 'File harus berupa gambar.',
            'foto_profil.mimes'        => 'Format foto harus JPG, PNG, atau WebP.',
            'foto_profil.max'          => 'Ukuran foto maksimal 10 MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'            => 'Nama Lengkap',
            'email'           => 'Email',
            'username'        => 'Username',
            'password'        => 'Password',
            'no_telepon'      => 'No. Telepon',
            'nip'             => 'NIP',
            'zona_id'         => 'Zona Wilayah',
            'status_tersedia' => 'Status Ketersediaan',
        ];
    }
}
