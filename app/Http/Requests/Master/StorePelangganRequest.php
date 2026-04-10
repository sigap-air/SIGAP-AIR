<?php
/**
 * Validasi Form Data Pelanggan PDAM
 * TANGGUNG JAWAB: Arthur Budi Maharesi (PBI-01)
 */
namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class StorePelangganRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $pelangganId = $this->route('pelanggan')?->id;
        return [
            'user_id'         => 'required|exists:users,id',
            'nomor_sambungan' => "required|string|unique:pelanggans,nomor_sambungan,{$pelangganId}",
            'alamat'          => 'required|string|max:500',
            'zona_id'         => 'required|exists:zonas,id',
            'is_active'       => 'boolean',
        ];
    }
}
