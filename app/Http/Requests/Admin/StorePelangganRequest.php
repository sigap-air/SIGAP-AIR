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
            'zona_id'         => ['required', 'exists:zonas,id'],
            'nama_pelanggan'  => ['required', 'string', 'max:255'],
            'alamat'          => ['required', 'string'],
            'nomor_sambungan' => ['required', 'string', 'max:50', 'unique:pelanggans,nomor_sambungan'],
            'no_telepon'      => ['nullable', 'string', 'max:20'],
            'is_active'       => ['boolean'],
        ];
    }
}
