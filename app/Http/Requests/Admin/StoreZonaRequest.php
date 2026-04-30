<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * PBI-03 — Validasi pembuatan zona wilayah baru
 */
class StoreZonaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Auth & role sudah dihandle middleware
    }

    public function rules(): array
    {
        return [
            'nama_zona' => ['required', 'string', 'max:100'],
            'kode_zona' => ['required', 'string', 'max:20', 'unique:zona_wilayah,kode_zona'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'is_active'  => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_zona.required' => 'Nama zona wajib diisi.',
            'nama_zona.max'      => 'Nama zona maksimal 100 karakter.',
            'kode_zona.required' => 'Kode zona wajib diisi.',
            'kode_zona.max'      => 'Kode zona maksimal 20 karakter.',
            'kode_zona.unique'   => 'Kode zona sudah digunakan, pilih kode lain.',
            'deskripsi.max'      => 'Deskripsi maksimal 1000 karakter.',
        ];
    }
}
