<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * PBI-03 — Validasi update zona wilayah (kecualikan ID sendiri di unique check)
 */
class UpdateZonaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ambil ID zona dari route parameter 'id' (route model binding via integer)
        $zonaId = $this->route('id');

        return [
            'nama_zona' => ['required', 'string', 'max:100'],
            'kode_zona' => [
                'required',
                'string',
                'max:20',
                Rule::unique('zona_wilayah', 'kode_zona')->ignore($zonaId),
            ],
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
            'kode_zona.unique'   => 'Kode zona sudah digunakan oleh zona lain.',
            'deskripsi.max'      => 'Deskripsi maksimal 1000 karakter.',
        ];
    }
}
