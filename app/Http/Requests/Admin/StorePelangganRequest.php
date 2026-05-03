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
            'no_telepon'      => ['required', 'digits_between:8,15'],
            'kategori_id'     => ['required', 'exists:kategori_pengaduan,id'],
            'deskripsi'       => ['required', 'string', 'min:20', 'max:1000'],
            'foto_bukti'      => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:10240'],
            'is_active'       => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'kategori_id.required' => 'Kategori pengaduan wajib dipilih.',
            'deskripsi.required'   => 'Deskripsi masalah wajib diisi.',
            'deskripsi.min'        => 'Deskripsi minimal 20 karakter.',
            'foto_bukti.required'  => 'Bukti foto wajib diunggah.',
            'foto_bukti.max'        => 'Ukuran foto maksimal 10MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nomor_sambungan' => 'No Tiket',
        ];
    }
}
