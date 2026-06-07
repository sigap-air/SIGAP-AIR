<?php

namespace App\Http\Requests\Pengaduan;

use Illuminate\Foundation\Http\FormRequest;

class RevisiPengaduanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'kategori_id' => 'required|exists:kategori_pengaduan,id',
            'zona_id'     => 'required|exists:zona_wilayah,id',
            'lokasi'      => 'required|string|max:500',
            'latitude'    => 'nullable|numeric|between:-90,90',
            'longitude'   => 'nullable|numeric|between:-180,180',
            'no_telepon'  => 'required|string|regex:/^[0-9]+$/|max:20',
            'deskripsi'   => 'required|string|min:20|max:2000',
            'foto_bukti'  => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'kategori_id.required' => 'Kategori pengaduan wajib dipilih.',
            'zona_id.required'     => 'Zona wilayah wajib dipilih.',
            'lokasi.required'      => 'Lokasi pengaduan wajib diisi.',
            'no_telepon.required'  => 'Nomor telepon wajib diisi.',
            'no_telepon.regex'     => 'Nomor telepon hanya boleh berisi angka.',
            'deskripsi.required'   => 'Deskripsi masalah wajib diisi.',
            'deskripsi.min'        => 'Deskripsi minimal 20 karakter.',
            'foto_bukti.image'     => 'File yang diunggah harus berupa gambar.',
            'foto_bukti.max'       => 'Ukuran foto maksimal 10MB.',
        ];
    }
}
