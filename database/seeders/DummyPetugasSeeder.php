<?php
use App\Models\User;
use App\Models\Petugas;
use App\Models\ZonaWilayah;
use Illuminate\Support\Facades\Hash;

// Data petugas dummy realistis
$petugasData = [
    ['name' => 'Budi Santoso',       'nip' => 'NIP-2024-001', 'status' => 'tersedia'],
    ['name' => 'Ahmad Fauzi',        'nip' => 'NIP-2024-002', 'status' => 'sibuk'],
    ['name' => 'Dedi Kurniawan',     'nip' => 'NIP-2024-003', 'status' => 'tersedia'],
    ['name' => 'Eko Prasetyo',       'nip' => 'NIP-2024-004', 'status' => 'tidak_aktif'],
    ['name' => 'Hendra Gunawan',     'nip' => 'NIP-2024-005', 'status' => 'tersedia'],
    ['name' => 'Iwan Setiawan',      'nip' => 'NIP-2024-006', 'status' => 'sibuk'],
    ['name' => 'Joko Widodo',        'nip' => 'NIP-2024-007', 'status' => 'tersedia'],
    ['name' => 'Lukman Hakim',       'nip' => 'NIP-2024-008', 'status' => 'tersedia'],
    ['name' => 'Muhamad Rizky',      'nip' => 'NIP-2024-009', 'status' => 'sibuk'],
    ['name' => 'Nanang Supriyadi',   'nip' => 'NIP-2024-010', 'status' => 'tidak_aktif'],
    ['name' => 'Oki Dermawan',       'nip' => 'NIP-2024-011', 'status' => 'tersedia'],
    ['name' => 'Prabowo Santoso',    'nip' => 'NIP-2024-012', 'status' => 'tersedia'],
];

// Ambil zona aktif, buat jika belum ada
$zonas = ZonaWilayah::where('is_active', true)->get();
if ($zonas->count() === 0) {
    ZonaWilayah::create(['kode_zona' => 'ZN-01', 'nama_zona' => 'Zona Utara',   'is_active' => true]);
    ZonaWilayah::create(['kode_zona' => 'ZN-02', 'nama_zona' => 'Zona Selatan', 'is_active' => true]);
    ZonaWilayah::create(['kode_zona' => 'ZN-03', 'nama_zona' => 'Zona Barat',   'is_active' => true]);
    ZonaWilayah::create(['kode_zona' => 'ZN-04', 'nama_zona' => 'Zona Timur',   'is_active' => true]);
    $zonas = ZonaWilayah::where('is_active', true)->get();
}

$zonaList  = $zonas->values();
$zonaCount = $zonaList->count();

$created = 0;
foreach ($petugasData as $index => $data) {
    $email = 'petugas.' . strtolower(str_replace(' ', '.', $data['name'])) . '@sigap-air.com';

    // Skip jika email sudah ada
    if (User::where('email', $email)->exists()) {
        echo "Skip (email sudah ada): {$data['name']}\n";
        continue;
    }

    $username = 'ptg_' . strtolower(str_replace(' ', '_', $data['name']));
    $username = preg_replace('/[^a-z0-9_]/', '', $username);

    $user = User::create([
        'name'       => $data['name'],
        'email'      => $email,
        'username'   => $username,
        'password'   => Hash::make('password123'),
        'role'       => 'petugas',
        'no_telepon' => '08123456' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
    ]);

    // Assign zona: 2 terakhir sengaja tanpa zona untuk testing filter "Tanpa Zona"
    $zona_id = $index < count($petugasData) - 2
        ? $zonaList[$index % $zonaCount]->id
        : null;

    Petugas::create([
        'user_id'         => $user->id,
        'nip'             => $data['nip'],
        'zona_id'         => $zona_id,
        'status_tersedia' => $data['status'],
    ]);

    $zonaName = $zona_id ? $zonaList[$index % $zonaCount]->nama_zona : 'Tanpa Zona';
    echo "✅ {$data['name']} — {$data['nip']} — {$data['status']} — {$zonaName}\n";
    $created++;
}

echo "\n===============================\n";
echo "Berhasil dibuat: {$created} petugas\n";
echo "Total zona: {$zonaCount}\n";
echo "Password default: password123\n";
echo "===============================\n";
