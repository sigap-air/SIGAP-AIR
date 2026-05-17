<?php

namespace App\Services\Testing;

use App\Models\Assignment;
use App\Models\Kategori;
use App\Models\Pengaduan;
use App\Models\Petugas;
use App\Models\Sla;
use App\Models\User;
use App\Models\Zona;

/**
 * Data fixture untuk uji Dusk filter pengaduan admin (PBI-13).
 * Mengandalkan user & master data dari {@see \Database\Seeders\DatabaseSeeder}.
 */
final class PengaduanFilterScenarioService
{
    public const EMAIL_ADMIN = 'admin@sigapair.test';

    public const EMAIL_SUPERVISOR = 'supervisor@sigapair.test';

    public const EMAIL_PETUGAS = 'petugas@sigapair.test';

    public const EMAIL_MASYARAKAT = 'masyarakat@sigapair.test';

    public function admin(): User
    {
        return User::where('email', self::EMAIL_ADMIN)->firstOrFail();
    }

    public function pelapor(): User
    {
        return User::where('email', self::EMAIL_MASYARAKAT)->firstOrFail();
    }

    public function supervisor(): User
    {
        return User::where('email', self::EMAIL_SUPERVISOR)->firstOrFail();
    }

    public function petugasTap(): Petugas
    {
        return Petugas::query()
            ->with('user')
            ->whereHas('user', fn ($q) => $q->where('email', self::EMAIL_PETUGAS))
            ->firstOrFail();
    }

    /** @return array{admin: User, tiketMatch: string, tiketOther: string} */
    public function buildNomorTiketFilterFixtures(): array
    {
        $admin = $this->admin();
        $pelapor = $this->pelapor();
        $kategori = $this->kategoriAirBerbau();
        $zona = $this->zonaBandungBarat();

        $tiketMatch = 'SIGAP-P13-TKT-0001-A';
        $tiketOther = 'SIGAP-P13-TKT-9999-B';

        foreach ([$tiketMatch, $tiketOther] as $tiket) {
            $this->buatPengaduanDenganSlaBerjalan([
                'nomor_tiket'       => $tiket,
                'user_id'           => $pelapor->id,
                'kategori_id'       => $kategori->id,
                'zona_id'           => $zona->id,
                'lokasi'            => 'Lokasi uji tiket',
                'deskripsi'         => 'Deskripsi',
                'status'            => 'menunggu_verifikasi',
                'tanggal_pengajuan' => now(),
            ]);
        }

        return compact('admin', 'tiketMatch', 'tiketOther');
    }

    /** @return array{admin: User, nomorMenunggu: string, nomorDisetujui: string} */
    public function buildStatusFilterFixtures(): array
    {
        $admin = $this->admin();
        $pelapor = $this->pelapor();
        $kategori = $this->kategoriAirMacet();
        $zona = $this->zonaBandungTimur();

        $nomorMenunggu = 'SIGAP-P13-ST-MV';
        $nomorDisetujui = 'SIGAP-P13-ST-DS';

        $this->buatPengaduanDenganSlaBerjalan([
            'nomor_tiket'       => $nomorMenunggu,
            'user_id'           => $pelapor->id,
            'kategori_id'       => $kategori->id,
            'zona_id'           => $zona->id,
            'lokasi'            => 'A',
            'deskripsi'         => 'A',
            'status'            => 'menunggu_verifikasi',
            'tanggal_pengajuan' => now(),
        ]);

        $this->buatPengaduanDenganSlaBerjalan([
            'nomor_tiket'       => $nomorDisetujui,
            'user_id'           => $pelapor->id,
            'kategori_id'       => $kategori->id,
            'zona_id'           => $zona->id,
            'lokasi'            => 'B',
            'deskripsi'         => 'B',
            'status'            => 'disetujui',
            'tanggal_pengajuan' => now(),
        ]);

        return compact('admin', 'nomorMenunggu', 'nomorDisetujui');
    }

    /** @return array{admin: User, zonaUtara: Zona, nomorSelatan: string} */
    public function buildZonaFilterFixtures(): array
    {
        $admin = $this->admin();
        $pelapor = $this->pelapor();
        $kategori = $this->kategoriAirKeruh();

        $zonaUtara = Zona::where('nama_zona', 'Bandung Utara')->firstOrFail();
        $zonaSelatan = Zona::where('nama_zona', 'Bandung Selatan')->firstOrFail();

        $nomorUtara = 'SIGAP-P13-ZN-UTR';
        $nomorSelatan = 'SIGAP-P13-ZN-STN';

        $this->buatPengaduanDenganSlaBerjalan([
            'nomor_tiket'       => $nomorUtara,
            'user_id'           => $pelapor->id,
            'kategori_id'       => $kategori->id,
            'zona_id'           => $zonaUtara->id,
            'lokasi'            => 'Lokasi',
            'deskripsi'         => 'Zona test',
            'status'            => 'menunggu_verifikasi',
            'tanggal_pengajuan' => now(),
        ]);

        $this->buatPengaduanDenganSlaBerjalan([
            'nomor_tiket'       => $nomorSelatan,
            'user_id'           => $pelapor->id,
            'kategori_id'       => $kategori->id,
            'zona_id'           => $zonaSelatan->id,
            'lokasi'            => 'Lokasi',
            'deskripsi'         => 'Zona test',
            'status'            => 'menunggu_verifikasi',
            'tanggal_pengajuan' => now(),
        ]);

        return compact('admin', 'zonaUtara', 'nomorSelatan');
    }

    /** @return array{admin: User, kategoriAirKeruh: Kategori, nomorKategoriLain: string} */
    public function buildKategoriFilterFixtures(): array
    {
        $admin = $this->admin();
        $pelapor = $this->pelapor();
        $zona = $this->zonaBandungBarat();

        $kategoriAirKeruh = $this->kategoriAirKeruh();
        $kategoriLain = $this->kategoriAirMacet();

        $nomorKeruh = 'SIGAP-P13-KAT-KRH';
        $nomorKategoriLain = 'SIGAP-P13-KAT-LAIN';

        $this->buatPengaduanDenganSlaBerjalan([
            'nomor_tiket'       => $nomorKeruh,
            'user_id'           => $pelapor->id,
            'kategori_id'       => $kategoriAirKeruh->id,
            'zona_id'           => $zona->id,
            'lokasi'            => 'A',
            'deskripsi'         => 'A',
            'status'            => 'menunggu_verifikasi',
            'tanggal_pengajuan' => now(),
        ]);

        $this->buatPengaduanDenganSlaBerjalan([
            'nomor_tiket'       => $nomorKategoriLain,
            'user_id'           => $pelapor->id,
            'kategori_id'       => $kategoriLain->id,
            'zona_id'           => $zona->id,
            'lokasi'            => 'B',
            'deskripsi'         => 'B',
            'status'            => 'menunggu_verifikasi',
            'tanggal_pengajuan' => now(),
        ]);

        return compact('admin', 'kategoriAirKeruh', 'nomorKategoriLain');
    }

    /** @return array{admin: User, petugas: Petugas, namaPetugas: string, nomorDitugaskan: string, nomorTanpaAssignment: string} */
    public function buildPetugasFilterFixtures(): array
    {
        $admin = $this->admin();
        $pelapor = $this->pelapor();
        $supervisor = $this->supervisor();
        $petugas = $this->petugasTap();
        $kategori = $this->kategoriAirBerbau();
        $zona = Zona::findOrFail($petugas->zona_id);

        $namaPetugas = $petugas->user?->name ?? 'Petugas Lapangan';

        $nomorDitugaskan = 'SIGAP-P13-ASG-YES';
        $nomorTanpaAssignment = 'SIGAP-P13-ASG-NO';

        $p1 = Pengaduan::create([
            'nomor_tiket'       => $nomorDitugaskan,
            'user_id'           => $pelapor->id,
            'kategori_id'       => $kategori->id,
            'zona_id'           => $zona->id,
            'lokasi'            => 'A',
            'deskripsi'         => 'A',
            'status'            => 'ditugaskan',
            'tanggal_pengajuan' => now(),
        ]);
        Sla::create([
            'pengaduan_id' => $p1->id,
            'batas_waktu'  => now()->addDay(),
            'status_sla'   => 'berjalan',
        ]);

        Assignment::create([
            'pengaduan_id'      => $p1->id,
            'petugas_id'        => $petugas->id,
            'supervisor_id'     => $supervisor->id,
            'jadwal_penanganan' => now()->addHour(),
            'instruksi'         => 'Instruksi uji',
            'status_assignment' => 'ditugaskan',
        ]);

        $this->buatPengaduanDenganSlaBerjalan([
            'nomor_tiket'       => $nomorTanpaAssignment,
            'user_id'           => $pelapor->id,
            'kategori_id'       => $kategori->id,
            'zona_id'           => $zona->id,
            'lokasi'            => 'B',
            'deskripsi'         => 'B',
            'status'            => 'menunggu_verifikasi',
            'tanggal_pengajuan' => now(),
        ]);

        return compact('admin', 'petugas', 'namaPetugas', 'nomorDitugaskan', 'nomorTanpaAssignment');
    }

    /** @return array{admin: User, nomorLama: string, nomorBaru: string, dari: string, sampai: string} */
    public function buildTanggalFilterFixtures(): array
    {
        $admin = $this->admin();
        $pelapor = $this->pelapor();
        $kategori = $this->kategoriAirMacet();
        $zona = $this->zonaBandungTimur();

        $nomorLama = 'SIGAP-P13-DT-LAMA';
        $nomorBaru = 'SIGAP-P13-DT-BARU';
        $tLama = now()->subYears(2);
        $tBaru = now();

        $this->buatPengaduanDenganSlaBerjalan([
            'nomor_tiket'       => $nomorLama,
            'user_id'           => $pelapor->id,
            'kategori_id'       => $kategori->id,
            'zona_id'           => $zona->id,
            'lokasi'            => 'A',
            'deskripsi'         => 'A',
            'status'            => 'menunggu_verifikasi',
            'tanggal_pengajuan' => $tLama,
        ]);

        $this->buatPengaduanDenganSlaBerjalan([
            'nomor_tiket'       => $nomorBaru,
            'user_id'           => $pelapor->id,
            'kategori_id'       => $kategori->id,
            'zona_id'           => $zona->id,
            'lokasi'            => 'B',
            'deskripsi'         => 'B',
            'status'            => 'menunggu_verifikasi',
            'tanggal_pengajuan' => $tBaru,
        ]);

        $dari = $tLama->copy()->subDay()->format('Y-m-d');
        $sampai = $tLama->copy()->addDay()->format('Y-m-d');

        return compact('admin', 'nomorLama', 'nomorBaru', 'dari', 'sampai');
    }

    /** @return array{admin: User, nomorOverdue: string, nomorTidakOverdue: string} */
    public function buildOverdueFilterFixtures(): array
    {
        $admin = $this->admin();
        $pelapor = $this->pelapor();
        $kategori = $this->kategoriAirKeruh();
        $zona = $this->zonaBandungBarat();

        $nomorOverdue = 'SIGAP-P13-SLA-OD';
        $nomorTidakOverdue = 'SIGAP-P13-SLA-OK';

        $p1 = Pengaduan::create([
            'nomor_tiket'       => $nomorOverdue,
            'user_id'           => $pelapor->id,
            'kategori_id'       => $kategori->id,
            'zona_id'           => $zona->id,
            'lokasi'            => 'A',
            'deskripsi'         => 'A',
            'status'            => 'menunggu_verifikasi',
            'tanggal_pengajuan' => now(),
        ]);
        Sla::create([
            'pengaduan_id' => $p1->id,
            'batas_waktu'  => now()->subDay(),
            'status_sla'   => 'overdue',
        ]);

        $this->buatPengaduanDenganSlaBerjalan([
            'nomor_tiket'       => $nomorTidakOverdue,
            'user_id'           => $pelapor->id,
            'kategori_id'       => $kategori->id,
            'zona_id'           => $zona->id,
            'lokasi'            => 'B',
            'deskripsi'         => 'B',
            'status'            => 'menunggu_verifikasi',
            'tanggal_pengajuan' => now(),
        ]);

        return compact('admin', 'nomorOverdue', 'nomorTidakOverdue');
    }

    /** Satu pengaduan untuk uji export CSV. */
    public function buildExportCsvFixtures(): User
    {
        $admin = $this->admin();
        $pelapor = $this->pelapor();
        $kategori = $this->kategoriAirKeruh();
        $zona = $this->zonaBandungUtara();

        $this->buatPengaduanDenganSlaBerjalan([
            'nomor_tiket'       => 'SIGAP-P13-CSV-0001',
            'user_id'           => $pelapor->id,
            'kategori_id'       => $kategori->id,
            'zona_id'           => $zona->id,
            'lokasi'            => 'Jl. Export Test',
            'deskripsi'         => 'Data untuk uji export CSV.',
            'status'            => 'menunggu_verifikasi',
            'tanggal_pengajuan' => now(),
        ]);

        return $admin;
    }

    private function kategoriAirKeruh(): Kategori
    {
        return Kategori::where('nama_kategori', 'Air Keruh')->firstOrFail();
    }

    private function kategoriAirMacet(): Kategori
    {
        return Kategori::where('nama_kategori', 'Air Macet')->firstOrFail();
    }

    private function kategoriAirBerbau(): Kategori
    {
        return Kategori::where('nama_kategori', 'Air Berbau')->firstOrFail();
    }

    private function zonaBandungUtara(): Zona
    {
        return Zona::where('nama_zona', 'Bandung Utara')->firstOrFail();
    }

    private function zonaBandungBarat(): Zona
    {
        return Zona::where('nama_zona', 'Bandung Barat')->firstOrFail();
    }

    private function zonaBandungTimur(): Zona
    {
        return Zona::where('nama_zona', 'Bandung Timur')->firstOrFail();
    }

    /** @param  array<string, mixed>  $data */
    private function buatPengaduanDenganSlaBerjalan(array $data): Pengaduan
    {
        $p = Pengaduan::create($data);
        Sla::create([
            'pengaduan_id' => $p->id,
            'batas_waktu'  => now()->addDay(),
            'status_sla'   => 'berjalan',
        ]);

        return $p;
    }
}
