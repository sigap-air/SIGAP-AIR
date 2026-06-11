<?php

namespace App\Http\Requests\Supervisor;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssignmentRequest extends FormRequest
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
            'petugas_id'        => ['required', 'exists:petugas,id'],
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
            'petugas_id.required'        => 'Petugas wajib dipilih.',
            'petugas_id.exists'          => 'Petugas tidak valid.',
            'instruksi.max'              => 'Catatan assignment maksimal 2000 karakter.',
            'jadwal_penanganan.required' => 'Jadwal penanganan wajib diisi.',
            'jadwal_penanganan.date'     => 'Format jadwal penanganan tidak valid.',
            'jadwal_penanganan.after'    => 'Jadwal penanganan harus setelah waktu sekarang.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'petugas_id'        => 'Petugas',
            'instruksi'         => 'Catatan Assignment',
            'jadwal_penanganan' => 'Jadwal Penanganan',
        ];
    }
}
