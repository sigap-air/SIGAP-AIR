<?php
/**
 * Validasi Form Pengajuan Pengaduan
 * TANGGUNG JAWAB: Sanitra Savitri (PBI-04)
 */
namespace App\Http\Requests\Pengaduan;

use Illuminate\Foundation\Http\FormRequest;

class StorePengaduanRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'kategori_id' => 'required|exists:kategoris,id',
            'zona_id'     => 'required|exists:zonas,id',
            'lokasi'      => 'required|string|max:500',
            'deskripsi'   => 'required|string|min:20|max:2000',
            'foto_bukti'  => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // max 5MB
        ];
    }

    public function messages(): array
    {
        return [
            'kategori_id.required' => 'Kategori pengaduan wajib dipilih.',
            'zona_id.required'     => 'Zona wilayah wajib dipilih.',
            'lokasi.required'      => 'Lokasi pengaduan wajib diisi.',
            'deskripsi.required'   => 'Deskripsi masalah wajib diisi.',
            'deskripsi.min'        => 'Deskripsi minimal 20 karakter.',
            'foto_bukti.image'     => 'File yang diunggah harus berupa gambar.',
            'foto_bukti.max'       => 'Ukuran foto maksimal 5MB.',
        ];
    }
}
