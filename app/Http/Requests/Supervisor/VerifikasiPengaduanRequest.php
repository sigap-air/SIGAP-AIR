<?php

namespace App\Http\Requests\Supervisor;

use Illuminate\Foundation\Http\FormRequest;

class VerifikasiPengaduanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isSupervisor();
    }

    public function rules(): array
    {
        return [
            'keputusan' => ['required', 'in:disetujui,ditolak'],
            'alasan_penolakan' => ['required_if:keputusan,ditolak', 'nullable', 'string', 'min:10', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'keputusan.required' => 'Keputusan verifikasi wajib dipilih.',
            'keputusan.in' => 'Pilihan keputusan tidak valid.',
            'alasan_penolakan.required_if' => 'Alasan penolakan wajib diisi jika pengaduan ditolak.',
            'alasan_penolakan.min' => 'Alasan penolakan minimal 10 karakter.',
            'alasan_penolakan.max' => 'Alasan penolakan maksimal 1000 karakter.',
        ];
    }
}
