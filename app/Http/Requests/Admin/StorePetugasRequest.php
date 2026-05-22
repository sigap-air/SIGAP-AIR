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
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email'],
            'username'    => ['required', 'string', 'max:100', 'unique:users,username'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'no_telepon'  => ['nullable', 'numeric'],
            // Foto profil opsional
            'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            // Data Petugas — NIP auto-generated, tidak perlu diisi manual
            'nip'             => ['nullable', 'string', 'max:50'],
            'zona_id'         => ['nullable', 'exists:zona_wilayah,id'],
            'status_tersedia' => ['required', 'in:tersedia,sibuk,tidak_aktif'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'Nama lengkap wajib diisi.',
            'email.required'         => 'Email wajib diisi.',
            'email.unique'           => 'Email sudah digunakan oleh akun lain.',
            'username.required'      => 'Username wajib diisi.',
            'username.unique'        => 'Username sudah digunakan.',
            'password.required'      => 'Password wajib diisi.',
            'password.min'           => 'Password minimal 8 karakter.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
            'nip.unique'             => 'NIP sudah terdaftar untuk petugas lain.',
            'zona_id.exists'         => 'Zona wilayah yang dipilih tidak valid.',
            'status_tersedia.required' => 'Status ketersediaan wajib dipilih.',
            'status_tersedia.in'     => 'Status tidak valid.',
            'no_telepon.numeric'     => 'Nomor telepon hanya boleh berisi angka.',
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
