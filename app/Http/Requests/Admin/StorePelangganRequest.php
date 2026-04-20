<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePelangganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'zona_id'         => ['required', 'exists:zona_wilayah,id'],
            'nama_pelanggan'  => ['required', 'string', 'max:255'],
            'alamat'          => ['required', 'string', 'max:1000'],
            'nomor_sambungan' => ['required', 'string', 'max:50', 'unique:pelanggan,nomor_sambungan'],
            'no_telepon'      => ['nullable', 'digits_between:8,15'],
            'is_active'       => ['boolean'],
        ];
    }
}
