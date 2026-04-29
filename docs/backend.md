# Dokumentasi Backend 

## Ringkasan

- Framework: Laravel (PHP)
- Struktur: MVC (Models, Views, Controllers) dengan routes di `routes/web.php`.
- Backend ini memisahkan akses berdasarkan peran pengguna, jadi tiap role hanya melihat fitur yang memang menjadi tanggung jawabnya.

## Arsitektur Utama

- Namespace utama: `App\\Http\\Controllers` untuk controller, `App\\Models` untuk model.
- Shared middleware: `auth`, `role` untuk membatasi akses (admin, ketua, pembina).
- `auth` memastikan pengguna sudah login sebelum masuk ke area internal.
- `role` membedakan hak akses berdasarkan jabatan, misalnya admin untuk pengelolaan data utama, ketua untuk operasional organisasi, dan pembina untuk pengawasan serta validasi.

## Routes & Grup

- Guest routes: login, register (`routes/web.php`).
- Authenticated routes: grouped di bawah `auth` middleware dengan redirect dashboard dan notifikasi.
- Role-based groups: `admin`, `ketua`, `pembina` — masing-masing punya route resource untuk `organisasi`, `kegiatan`, `dokumentasi`, `laporan`, validasi, dsb.

Secara sederhana, alurnya seperti ini: pengguna masuk lewat login, sistem menentukan perannya, lalu pengguna diarahkan ke dashboard yang sesuai. Setelah itu route yang tersedia juga menyesuaikan role yang dimiliki.

Contoh file route: [routes/web.php](routes/web.php)

## Models (ringkasan)

- `User` — model pengguna; lokasi: [app/Models/User.php](app/Models/User.php).
- `Organisasi` — data organisasi; lokasi: [app/Models/Organisasi.php](app/Models/Organisasi.php).
- `Kegiatan` — data kegiatan/agenda; lokasi: [app/Models/Kegiatan.php](app/Models/Kegiatan.php).
- `Dokumentasi` — unggahan foto/dokumentasi kegiatan; lokasi: [app/Models/Dokumentasi.php](app/Models/Dokumentasi.php).
- `LaporanKegiatan` — laporan kegiatan dan attachment; lokasi: [app/Models/LaporanKegiatan.php](app/Models/LaporanKegiatan.php).
- `Session` — sesi/timeline kegiatan; lokasi: [app/Models/Session.php](app/Models/Session.php).

Untuk detail kolom, lihat migrations di `database/migrations/`.

Relasi penting yang terlihat di model adalah `Kegiatan` yang terhubung ke `Organisasi`, lalu memiliki relasi ke `Dokumentasi` dan `LaporanKegiatan`. Artinya satu kegiatan bisa punya banyak dokumentasi dan beberapa laporan pendukung.

Contoh isi model `Kegiatan` juga menunjukkan field yang sering dipakai backend, seperti nama kegiatan, tanggal mulai, tempat, proposal, status, dan keterangan.

## Migrations & Skema

- Semua migration ada di `database/migrations/` (contoh: `create_users_table`, `create_organisasi_table`, `create_kegiatan_table`, `create_dokumentasi_table`, `create_laporan_kegiatan_table`, `create_sessions_table`, `create_notifications_table`).
- Jalankan: `php artisan migrate` untuk membuat tabel.
- Migration di project ini menjadi sumber utama untuk memahami struktur data karena dari sanalah nama tabel, kolom, tipe data, dan relasi dasar disusun.

## Controllers & Alur Kerja

- Controllers dikelompokkan menurut role:
    - `App\\Http\\Controllers\\Admin\\*` — manajemen pengguna, organisasi, validasi, laporan, dokumentasi.
    - `App\\Http\\Controllers\\Ketua\\*` — fitur yang diakses ketua organisasi (CRUD kegiatan, dokumentasi, laporan, jadwal).
    - `App\\Http\\Controllers\\Pembina\\*` — fitur untuk pembina, termasuk validasi dan pemantauan.
- Controller otentikasi: `App\\Http\\Controllers\\AuthController.php` (login, register, logout, redirect dashboard).
- Notifikasi: `App\\Http\\Controllers\\NotificationController.php` — baca notifikasi (read-all, read-one).

Mayoritas controller di sini mengikuti pola CRUD: menampilkan daftar data, menyimpan data baru, memperbarui data lama, dan menghapus data. Beberapa controller juga punya aksi tambahan seperti validasi approve/reject dan ekspor PDF untuk laporan.

Lihat folder controller untuk implementasi: [app/Http/Controllers](app/Http/Controllers)

## Notifikasi & Workflow

- Notifikasi disimpan di tabel `notifications` (migration tersedia) dan dikirim via kelas notifikasi di `app/Notifications`.
- Ada workflow validasi kegiatan: route `approve`/`reject` pada controller `ValidasiController` untuk Admin/Pembina.
- Workflow validasi ini penting karena status kegiatan bisa berubah berdasarkan keputusan pihak yang berwenang, sehingga data yang tampil ke pengguna lain ikut menyesuaikan.

## File Storage

- File upload (dokumentasi, laporan) disimpan di `storage/` dan diakses via symbolic link `public/storage` jika diperlukan. 
- Bagian ini biasanya dipakai untuk proposal, dokumentasi foto, dan file laporan yang diunduh kembali oleh pengguna.
