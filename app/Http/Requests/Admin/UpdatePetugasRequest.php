<?php

/**
 * PBI-16 — Form Request: Update Data Petugas Teknis
 * TANGGUNG JAWAB: Farisha Huwaida Shofha
 */

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePetugasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $petugas = $this->route('petugas');
        if (is_numeric($petugas)) {
            $petugas = \App\Models\Petugas::find($petugas);
        }
        
        $petugasId = $petugas ? $petugas->id : null;
        $userId  = $petugas ? $petugas->user_id : null;

        return [
            // Data User
            'name'       => [
                'required', 'string', 'max:255',
                \Illuminate\Validation\Rule::unique('users', 'name')
                    ->where(fn ($q) => $q->where('role', 'petugas'))
                    ->ignore($userId),
            ],
            'email'      => ['required', 'email', 'max:255', "unique:users,email,{$userId}", 'regex:/@pdam\.go\.id$/'],
            'username'   => ['required', 'string', 'max:100', "unique:users,username,{$userId}"],
            'password'   => ['nullable', 'string', 'min:8', 'confirmed'],
            'no_telepon' => ['required', 'numeric', "unique:users,no_telepon,{$userId}"],
            'foto_profil'=> ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'hapus_foto' => ['nullable', 'boolean'],

            // Data Petugas
            'nip'             => ['nullable', 'string', 'max:50', "unique:petugas,nip,{$petugasId}"],
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
            'password.min'             => 'Password minimal 8 karakter.',
            'password.confirmed'       => 'Konfirmasi password tidak cocok.',
            'nip.unique'               => 'NIP sudah terdaftar untuk petugas lain.',
            'zona_id.exists'           => 'Zona wilayah yang dipilih tidak valid.',
            'status_tersedia.required' => 'Status ketersediaan wajib dipilih.',
            'status_tersedia.in'       => 'Status tidak valid.',
            'no_telepon.required'      => 'Nomor telepon wajib diisi.',
            'no_telepon.numeric'       => 'Nomor telepon hanya boleh berisi angka.',
            'no_telepon.unique'        => 'Nomor telepon sudah terdaftar untuk akun lain.',
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
