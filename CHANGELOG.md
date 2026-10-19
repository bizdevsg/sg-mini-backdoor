## [Unreleased]

### Added
- Menambahkan service phpMyAdmin pada stack Docker di port lokal `8081` untuk mengelola database MySQL container melalui network internal Compose.
- Menambahkan container Docker berbasis Nginx dan PHP 8.4 FPM untuk local runtime Laravel beserta MySQL, queue worker, scheduler, migration otomatis, healthcheck, dukungan konversi gambar AVIF/WebP, asset Vite terkompilasi, serta volume persisten.
- Menambahkan toggle Trade Pilot Development dan Production pada System Settings, penyimpanan status per environment, activity log, serta field `trade_pilot_dev` dan `trade_pilot_prod` pada endpoint `/api/v1/client-area`.
- Menambahkan menu Produk Akun Prime beserta route CRUD admin dan endpoint public API list/detail pada `/api/v1/produk/prime`, termasuk cache JSON, dokumentasi API, dan validasi kategori `PRIME`.
- Menambahkan konfigurasi izin command lokal Claude untuk mendukung inspeksi dan workflow pengembangan project.
- Menambahkan Bruno collection `bruno/sg-admin-api` untuk seluruh endpoint public API v1, lengkap dengan environment production, autentikasi API key, parameter contoh, body form kontak, dan dokumentasi penggunaan.
- Menambahkan halaman Tambah Sekaligus pada Wakil Pialang Berjangka untuk menambahkan hingga 100 wakil pialang dalam satu kategori, lengkap dengan baris formulir dinamis dan validasi nomor identitas unik.
- Menambahkan metadata pagination JSON pada endpoint daftar kategori Wakil Pialang Berjangka agar client dapat membaca halaman aktif, jumlah item per halaman, total data, rentang data, dan halaman terakhir.
- Menambahkan toast pemberitahuan di pojok kanan bawah dengan aksi Undo selama 10 detik untuk perubahan edit dan hapus pada seluruh modul CRUD admin.
- Menambahkan snapshot Undo terenkripsi, token sekali pakai yang terikat ke user, pemeriksaan konflik perubahan, serta pencadangan privat untuk gambar dan file yang dihapus atau diganti.
- Menambahkan retensi snapshot Undo selama 24 jam dan command terjadwal `crud-undo:prune` untuk menghapus permanen snapshot serta backup file yang sudah melewati retensi.

### Changed
- Deskripsi Produk kini mendukung input Bahasa Indonesia dan English; respons API menambahkan `deskripsi_produk_id` dan `deskripsi_produk_en` sambil mempertahankan `deskripsi_produk` untuk kompatibilitas client lama.
- Menambahkan `deploy.sh` root project ke `.gitignore` agar skrip deployment lokal tidak ikut ter-commit ke repository.
- Endpoint detail publik Signal kini menggunakan UUID melalui `GET /api/v1/signal/{uuid}`; field/kolom slug Signal dihapus, data lama dibackfill melalui migration, dan cache lama tanpa UUID diperbarui otomatis.
- Autentikasi public API kini menyimpan hash SHA-256 melalui `API_KEY_HASH` dan membandingkan hash request secara constant-time; dokumentasi HTML/PDF tidak lagi menampilkan API key asli.
- Endpoint `GET /api/v1/wakil-pialang-berjangka/categories` kini mendukung parameter `page` dan `per_page` serta menyertakan informasi tautan request.
- Flash message admin kini tampil sebagai toast responsif dan dapat ditutup tanpa menggeser konten halaman.
- Scheduler cleanup Undo dijadwalkan setiap hari pukul 03:15 dan dilindungi `withoutOverlapping()`.

### Fixed
- Memulihkan autentikasi akun superadmin pada database Docker lokal dengan memastikan password tersimpan menggunakan hash bcrypt yang didukung Laravel.
- Memperbaiki race condition `image already exists` saat `docker compose build` dengan menetapkan service `app` sebagai satu-satunya pemilik build image Laravel; queue, scheduler, dan migration memakai ulang image tersebut.
- Menyamakan field `confident` Signal sebagai input wajib pada form tambah/edit dan validasi backend agar nilai kosong ditolak sebelum mencapai kolom database yang tidak menerima `NULL`.
- Memperbaiki CRUD admin Signal dengan explicit UUID route binding untuk halaman detail, edit, update, dan hapus tanpa mengubah route key global model; URL numerik lama kini ditolak.
- Memperbaiki penyimpanan `retention_until` pada snapshot Undo agar tidak dibuang oleh mass assignment dan tidak menyebabkan error MySQL pada insert aksi Undo.

### Removed
- Menghapus field gabungan `trade_pilot` dari respons API System Settings agar status Trade Pilot hanya dibaca secara eksplisit melalui `trade_pilot_dev` dan `trade_pilot_prod`.
- Menghapus alur tambah satuan Wakil Pialang Berjangka (tombol, halaman, route, controller, dan request) sehingga penambahan data baru dilakukan melalui Tambah Sekaligus.
