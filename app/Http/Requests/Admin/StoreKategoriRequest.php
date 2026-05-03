<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreKategoriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_kategori' => ['required', 'string', 'max:100'],
            'kode_kategori' => [
                'required',
                'string',
                'max:20',
                'unique:kategori_pengaduan,kode_kategori',
                'regex:/^[A-Z0-9\-]+$/',
            ],
            'deskripsi'     => ['nullable', 'string', 'max:1000'],
            'sla_jam'       => ['required', 'integer', 'min:1', 'max:720'],
            'is_active'     => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_kategori.regex' => 'Kode kategori hanya boleh mengandung huruf kapital, angka, dan tanda hubung (-).',
            'sla_jam.max'         => 'Batas SLA maksimal 720 jam (30 hari).',
        ];
    }
}
