<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekap SIGAP-AIR</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; text-align: center; margin-bottom: 4px; }
        .subtitle { text-align: center; color: #666; font-size: 11px; margin-bottom: 20px; }
        .summary { display: flex; gap: 20px; margin-bottom: 20px; }
        .card { border: 1px solid #ddd; border-radius: 6px; padding: 10px 20px; text-align: center; min-width: 120px; }
        .card .val { font-size: 22px; font-weight: bold; }
        .card .lbl { font-size: 10px; color: #888; }
        table { width: 100%; border-collapse: collapse; font-size: 11px; }
        th { background: #1e40af; color: white; padding: 8px; text-align: left; }
        td { padding: 6px 8px; border-bottom: 1px solid #eee; }
        tr:nth-child(even) td { background: #f9fafb; }
        .overdue { background: #fef2f2 !important; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body>
<div class="no-print" style="text-align:center;margin:20px 0;">
    <button onclick="window.print()" style="background:#1e40af;color:white;padding:10px 30px;border:none;border-radius:6px;cursor:pointer;font-size:14px;">
        🖨️ Print / Save as PDF
    </button>
</div>

<h1>LAPORAN REKAP PENGADUAN</h1>
<p class="subtitle">SIGAP-AIR — Sistem Informasi Gerak Cepat Pengaduan Air<br>
Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>

<div class="summary">
    <div class="card"><div class="val">{{ $data['total'] }}</div><div class="lbl">Total</div></div>
    <div class="card"><div class="val">{{ $data['per_status']['selesai'] ?? 0 }}</div><div class="lbl">Selesai</div></div>
    <div class="card"><div class="val">{{ $data['total_overdue'] }}</div><div class="lbl">Overdue</div></div>
    <div class="card"><div class="val">{{ $data['rata_waktu_jam'] ?? '—' }}</div><div class="lbl">Rata-rata Jam</div></div>
</div>

<table>
    <thead>
        <tr>
            <th>No. Tiket</th>
            <th>Kategori</th>
            <th>Zona</th>
            <th>Status</th>
            <th>SLA</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data['pengaduans'] as $p)
        <tr class="{{ $p->sla?->is_overdue ? 'overdue':'' }}">
            <td>{{ $p->nomor_tiket }}</td>
            <td>{{ $p->kategori->nama_kategori }}</td>
            <td>{{ $p->zona->nama_zona }}</td>
            <td>{{ ucwords(str_replace('_',' ',$p->status)) }}</td>
            <td>{{ $p->sla?->is_overdue ? 'Overdue' : ($p->sla?->is_fulfilled ? 'Terpenuhi' : 'Aktif') }}</td>
            <td>{{ $p->tanggal_pengajuan->format('d/m/Y') }}</td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:20px;">Tidak ada data</td></tr>
        @endforelse
    </tbody>
</table>
</body>
</html>
