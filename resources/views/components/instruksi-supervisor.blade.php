@props([
    'assignment' => null,
    'instruksi' => null,
    'supervisorName' => null,
    'jadwalPenanganan' => null,
    'compact' => false,
])

@php
    $teks = $instruksi ?? $assignment?->instruksi;
    $supervisor = $supervisorName ?? $assignment?->supervisor?->name;
    $jadwal = $jadwalPenanganan ?? $assignment?->jadwal_penanganan;
@endphp

@if ($teks)
    <div {{ $attributes->merge(['class' => 'rounded-2xl border border-amber-200 bg-amber-50 ' . ($compact ? 'p-4' : 'p-5')]) }}>
        <div class="mb-3 flex items-start gap-3">
            <span class="material-symbols-outlined text-amber-600 {{ $compact ? 'text-xl' : 'text-2xl' }}">edit_note</span>
            <div class="min-w-0 flex-1">
                <h3 class="font-bold text-amber-900 {{ $compact ? 'text-sm' : 'text-base' }}">Instruksi Perbaikan Supervisor</h3>
                @if ($supervisor)
                    <p class="mt-0.5 text-xs text-amber-700/80">Oleh {{ $supervisor }}</p>
                @endif
            </div>
        </div>
        <p class="whitespace-pre-wrap text-sm leading-relaxed text-amber-950">{{ $teks }}</p>
        @if ($jadwal)
            <p class="mt-3 border-t border-amber-200/80 pt-3 text-xs text-amber-800">
                <span class="font-semibold">Jadwal penanganan:</span>
                {{ $jadwal instanceof \Illuminate\Support\Carbon ? $jadwal->translatedFormat('d F Y, H:i') . ' WIB' : $jadwal }}
            </p>
        @endif
    </div>
@endif
