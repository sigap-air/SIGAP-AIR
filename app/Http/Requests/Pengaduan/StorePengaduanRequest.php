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

            // FIX ERR-2: nama tabel sesuai migration (bukan konvensi plural Laravel)
            'kategori_id' => 'required|exists:kategori_pengaduan,id',
            'zona_id'     => 'required|exists:zona_wilayah,id',
            'lokasi'      => 'required|string|max:500',
            'no_telepon'  => 'required|string|max:20',
            'deskripsi'   => 'required|string|min:20|max:2000',
            'foto_bukti'  => 'required|image|mimes:jpg,jpeg,png|max:10240', // max 10MB
        ];
    }

    public function messages(): array
    {
        return [
            'kategori_id.required' => 'Kategori pengaduan wajib dipilih.',
            'zona_id.required'     => 'Zona wilayah wajib dipilih.',
            'lokasi.required'      => 'Lokasi pengaduan wajib diisi.',
            'no_telepon.required'  => 'Nomor telepon wajib diisi.',
            'deskripsi.required'   => 'Deskripsi masalah wajib diisi.',
            'deskripsi.min'        => 'Deskripsi minimal 20 karakter.',
            'foto_bukti.required'  => 'Foto bukti wajib diunggah.',
            'foto_bukti.image'     => 'File yang diunggah harus berupa gambar.',
            'foto_bukti.max'       => 'Ukuran foto maksimal 10MB.',
        ];
    }
}
