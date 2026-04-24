<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePelangganRequest extends FormRequest
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
            'nomor_sambungan' => [
                'required',
                'string',
                'max:50',
                Rule::unique('pelanggan', 'nomor_sambungan')->ignore($this->route('pelanggan')),
            ],
            'no_telepon'      => ['required', 'string', 'max:20'],
            'is_active'       => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nomor_sambungan' => 'No Tiket',
        ];
    }
}
