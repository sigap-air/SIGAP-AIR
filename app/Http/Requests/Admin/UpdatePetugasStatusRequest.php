<?php

/**
 * PBI-17 — Ubah Status Ketersediaan Petugas
 * Admin dan Supervisor dapat mengubah status ketersediaan petugas.
 */

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePetugasStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user && ($user->isAdmin() || $user->isSupervisor());
    }

    public function rules(): array
    {
        return [
            'status_tersedia' => ['required', 'in:tersedia,sibuk,tidak_aktif'],
        ];
    }

    public function messages(): array
    {
        return [
            'status_tersedia.required' => 'Status ketersediaan wajib dipilih.',
            'status_tersedia.in'       => 'Status tidak valid.',
        ];
    }
}
