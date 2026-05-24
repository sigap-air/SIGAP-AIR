<?php

namespace App\Http\Requests\Supervisor;

use Illuminate\Foundation\Http\FormRequest;

class AssignPetugasToPengaduanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'pengaduan_id'      => ['required', 'exists:pengaduan,id'],
            'instruksi'         => ['nullable', 'string', 'max:2000'],
            'jadwal_penanganan' => ['required', 'date', 'after:now'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'pengaduan_id.required'      => 'Pengaduan wajib dipilih.',
            'pengaduan_id.exists'        => 'Pengaduan tidak valid.',
            'instruksi.max'              => 'Catatan assignment maksimal 2000 karakter.',
            'jadwal_penanganan.required' => 'Jadwal penanganan wajib diisi.',
            'jadwal_penanganan.after'    => 'Jadwal penanganan harus setelah waktu sekarang.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'pengaduan_id'      => 'Pengaduan',
            'instruksi'         => 'Catatan Assignment',
            'jadwal_penanganan' => 'Jadwal Penanganan',
        ];
    }
}
