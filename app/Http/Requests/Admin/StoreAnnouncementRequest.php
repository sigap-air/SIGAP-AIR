<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'      => ['required', 'string', 'max:255'],
            'content'    => ['required', 'string'],
            'type'       => ['required', 'in:disruption,maintenance,info'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'is_active'  => ['sometimes', 'boolean'],
            'zone_ids'   => ['nullable', 'array'],
            'zone_ids.*' => ['exists:zona_wilayah,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'end_date.after_or_equal' => 'Tanggal berakhir harus sama atau setelah tanggal mulai.',
            'zone_ids.*.exists'       => 'Salah satu zona yang dipilih tidak valid.',
        ];
    }
}
