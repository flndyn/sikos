<?php

namespace Database\Seeders;

use App\Models\Dokumentasi;
use App\Models\Kegiatan;
use App\Models\LaporanKegiatan;
use App\Models\Organisasi;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SikosDemoSeeder extends Seeder
{
    private const TRANSPARENT_PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9Wl6nS8AAAAASUVORK5CYII=';

    public function run(): void
    {
        DB::transaction(function (): void {
            $this->seedUsersAndOrganisasi();
        });
    }

    private function seedUsersAndOrganisasi(): void
    {
        $admin = $this->upsertUser(
            name: 'Admin SIKOS',
            email: 'admin@sikos.test',
            role: 'admin',
            photoPath: 'profile-photos/admin-sikos.png'
        );

        $this->storePng('profile-photos/admin-sikos.png');

        $organisasiData = [
            [
                'nama' => 'Jurnalistik',
                'ketua' => 'Alya Putri Ramadhani',
                'pembina' => 'Rizki Maulana',
                'kegiatan' => 'Lomba Jurnalistik FLS3N 2026',
                'status' => 'disetujui admin',
                'tanggal' => now()->addDays(3),
                'tempat' => 'Ruang Multimedia',
                'deskripsi' => 'Persiapan lomba jurnalistik, penulisan berita, dan pelatihan liputan.',
            ],
            [
                'nama' => 'MMA',
                'ketua' => 'Farhan Dwi Saputra',
                'pembina' => 'Siti Nur Aisyah',
                'kegiatan' => 'Ekshibisi MMA 2026',
                'status' => 'disetujui pembina',
                'tanggal' => now()->addDays(6),
                'tempat' => 'Lapangan Serbaguna',
                'deskripsi' => 'Program latihan fisik, teknik dasar, dan simulasi pertandingan.',
            ],
            [
                'nama' => 'Hadrah',
                'ketua' => 'Nabila Khairunnisa',
                'pembina' => 'Hendra Saputra',
                'kegiatan' => 'Penampilan Hadrah FLS3N 2026',
                'status' => 'disetujui admin',
                'tanggal' => now()->addDays(9),
                'tempat' => 'Aula Sekolah',
                'deskripsi' => 'Latihan irama, vokal, dan aransemen untuk penampilan hadrah.',
            ],
            [
                'nama' => 'Tahfidz',
                'ketua' => 'Muhammad Rizal',
                'pembina' => 'Ustadzah Laila',
                'kegiatan' => 'Musabaqah Tahfidz 2026',
                'status' => 'disetujui pembina',
                'tanggal' => now()->addDays(12),
                'tempat' => 'Mushola',
                'deskripsi' => 'Pembinaan hafalan, murojaah, dan persiapan lomba tahfidz.',
            ],
            [
                'nama' => 'Futsal',
                'ketua' => 'Dimas Pratama',
                'pembina' => 'Budi Santoso',
                'kegiatan' => 'Turnamen Futsal Antar Sekolah 2026',
                'status' => 'disetujui admin',
                'tanggal' => now()->addDays(15),
                'tempat' => 'Gor Olahraga',
                'deskripsi' => 'Seleksi pemain, strategi permainan, dan latihan stamina.',
            ],
            [
                'nama' => 'Basket',
                'ketua' => 'Kevin Prasetya',
                'pembina' => 'Maya Wulandari',
                'kegiatan' => 'Kejuaraan Basket 3x3 2026',
                'status' => 'disetujui pembina',
                'tanggal' => now()->addDays(18),
                'tempat' => 'Lapangan Basket',
                'deskripsi' => 'Latihan dribbling, shooting, dan formasi tim basket.',
            ],
            [
                'nama' => 'Tari',
                'ketua' => 'Citra Aulia',
                'pembina' => 'Sri Handayani',
                'kegiatan' => 'Lomba Tari Tradisional FLS3N 2026',
                'status' => 'disetujui admin',
                'tanggal' => now()->addDays(21),
                'tempat' => 'Studio Seni',
                'deskripsi' => 'Latihan koreografi, kostum, dan ekspresi panggung tari.',
            ],
            [
                'nama' => 'Band',
                'ketua' => 'Rafi Alvian',
                'pembina' => 'Dewi Kartika',
                'kegiatan' => 'Lomba Band FLS3N 2026',
                'status' => 'disetujui pembina',
                'tanggal' => now()->addDays(24),
                'tempat' => 'Studio Musik',
                'deskripsi' => 'Rehearsal lagu, mixing sederhana, dan persiapan penampilan band.',
            ],
            [
                'nama' => 'Voli',
                'ketua' => 'Salsa Nabila',
                'pembina' => 'Andi Firmansyah',
                'kegiatan' => 'Turnamen Voli Antar Pelajar 2026',
                'status' => 'disetujui admin',
                'tanggal' => now()->addDays(27),
                'tempat' => 'Lapangan Voli',
                'deskripsi' => 'Latihan servis, smash, dan koordinasi antar pemain voli.',
            ],
        ];

        foreach ($organisasiData as $index => $data) {
            $orgSlug = Str::slug($data['nama']);

            $pembina = $this->upsertUser(
                name: $data['pembina'],
                email: 'pembina.' . $orgSlug . '@sikos.test',
                role: 'pembina',
                photoPath: 'profile-photos/pembina-' . $orgSlug . '.png'
            );

            $ketua = $this->upsertUser(
                name: $data['ketua'],
                email: 'ketua.' . $orgSlug . '@sikos.test',
                role: 'ketua',
                photoPath: 'profile-photos/ketua-' . $orgSlug . '.png'
            );

            $this->storePng('profile-photos/pembina-' . $orgSlug . '.png');
            $this->storePng('profile-photos/ketua-' . $orgSlug . '.png');

            $organisasi = Organisasi::updateOrCreate(
                ['nama_organisasi' => $data['nama']],
                [
                    'deskripsi' => 'Organisasi ' . $data['nama'] . ' untuk kegiatan sekolah dan pengembangan minat bakat siswa.',
                    'pembina_id' => $pembina->id,
                    'ketua_id' => $ketua->id,
                ]
            );

            $proposalPath = 'proposal-kegiatan/' . $orgSlug . '/proposal_lomba_FLS3N2026.pdf';
            $laporanPath = 'laporan-kegiatan/' . $orgSlug . '/LPJ_lomba_FLS3N2026.pdf';

            $this->storePdf($proposalPath, 'Proposal Lomba FLS3N 2026 - ' . $data['nama']);
            $this->storePdf($laporanPath, 'LPJ Lomba FLS3N 2026 - ' . $data['nama']);

            $kegiatan = Kegiatan::updateOrCreate(
                [
                    'organisasi_id' => $organisasi->id,
                    'nama_kegiatan' => $data['kegiatan'],
                ],
                [
                    'deskripsi' => $data['deskripsi'],
                    'tanggal_mulai' => $data['tanggal']->toDateString(),
                    'tempat' => $data['tempat'],
                    'proposal' => $proposalPath,
                    'status' => $data['status'],
                    'keterangan' => null,
                ]
            );

            LaporanKegiatan::updateOrCreate(
                ['kegiatan_id' => $kegiatan->id],
                [
                    'isi_laporan' => 'Laporan pelaksanaan ' . $data['kegiatan'] . ' untuk organisasi ' . $data['nama'] . '. Kegiatan berjalan sesuai jadwal, dokumentasi lengkap, dan peserta aktif mengikuti rangkaian acara.',
                    'file_laporan' => $laporanPath,
                ]
            );

            $this->seedDokumentasi($kegiatan->id, $orgSlug, $data['nama'], $index);
        }

        $admin->update([
            'profile_photo_path' => 'profile-photos/admin-sikos.png',
        ]);
    }

    private function upsertUser(string $name, string $email, string $role, string $photoPath): User
    {
        return User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => 'password',
                'role' => $role,
                'profile_photo_path' => $photoPath,
            ]
        );
    }

    private function seedDokumentasi(int $kegiatanId, string $orgSlug, string $namaOrganisasi, int $index): void
    {
        $dokumentasiItems = [
            ['suffix' => '01', 'keterangan' => 'Dokumentasi pembukaan ' . $namaOrganisasi],
            ['suffix' => '02', 'keterangan' => 'Dokumentasi sesi inti ' . $namaOrganisasi],
            ['suffix' => '03', 'keterangan' => 'Dokumentasi penutupan ' . $namaOrganisasi],
        ];

        foreach ($dokumentasiItems as $item) {
            $path = 'dokumentasi-kegiatan/' . $orgSlug . '/' . $item['suffix'] . '.png';

            $this->storePng($path);

            Dokumentasi::updateOrCreate(
                [
                    'kegiatan_id' => $kegiatanId,
                    'file_dokumentasi' => $path,
                ],
                [
                    'keterangan' => $item['keterangan'] . ' #' . ($index + 1),
                ]
            );
        }
    }

    private function storePdf(string $path, string $title): void
    {
        $content = Pdf::loadHTML(
            '<html><head><meta charset="utf-8"></head><body style="font-family: DejaVu Sans, sans-serif;">'
            . '<h1>' . e($title) . '</h1>'
            . '<p>File seed otomatis untuk repository SIKOS.</p>'
            . '</body></html>'
        )->output();

        Storage::disk('public')->put($path, $content);
        Storage::disk('local')->put($path, $content);
    }

    private function storePng(string $path): void
    {
        $binary = base64_decode(self::TRANSPARENT_PNG, true);

        if ($binary === false) {
            return;
        }

        Storage::disk('public')->put($path, $binary);
        Storage::disk('local')->put($path, $binary);
    }
}