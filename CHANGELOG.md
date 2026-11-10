## [Unreleased]

### Fixed
- Mengembalikan API banner lama untuk Mini Micro, menambahkan API khusus banner reguler, serta menyiapkan pemindahan banner yang sudah ada ke Mini Micro dan pembaruan cache.
- Memperbaiki pengiriman push notification agar error dalam `headers.statusCode` pada respons Mini Micro tidak ditandai berhasil meskipun HTTP 200, serta memperbaiki migration signals pada SQLite agar pengujian dapat dijalankan.
- Memperbaiki pesan respons 404 pengiriman notifikasi Mini Micro agar tidak langsung menyimpulkan device token hilang dan mengarahkan admin memeriksa alamat serta endpoint UAT, sambil mempertahankan pesan dari server.

### Changed
- Melengkapi koleksi Postman dengan seluruh endpoint HTTP API publik SG Admin, sehingga pengujian lintas modul tersedia dalam satu koleksi.
- Mengabaikan folder koleksi Bruno dan Postman yang belum dilacak Git agar koleksi lokal tidak ikut ditambahkan ke repository.

### Added
- Menambahkan koleksi Postman untuk daftar dan detail API banner reguler serta Mini Micro, dengan variabel URL dan API key untuk pengujian.
- Memisahkan menu dan API banner reguler dan Mini Micro agar masing-masing kanal mengelola serta menampilkan banner sendiri; menu Banner memiliki submenu seperti Produk.
- Menambahkan dokumentasi tindak lanjut integrasi notifikasi Mini Micro UAT, termasuk implementasi SG Admin, hasil pengujian dan pertanyaan untuk tim API.
- Menambahkan override Compose untuk menggunakan database MySQL bersama `global-mysql` dan menjalankan aplikasi di port 8082, sehingga phpMyAdmin bersama tetap tersedia di port 8080.
- Menambahkan menu superadmin Notifikasi Push untuk broadcast Announcement/Promotion ke seluruh pengguna aplikasi melalui endpoint Mini Micro `POST /system/private/send-manual-notification`, termasuk form manual dengan penghitung karakter (judul 40, isi 150) dan preview tampilan HP, tombol broadcast dari detail Berita (`news_id`) dan kartu Banner (`promotion_id`) yang ditandai untuk notifikasi, konfirmasi ketik `KIRIM`, riwayat pengiriman pada tabel `push_notifications`, kirim ulang notifikasi gagal, activity log, serta pengujian Pest.
- Menambahkan pengaman broadcast notifikasi: mode dry run aktif secara default (`MINI_MICRO_DRY_RUN=true`, request dikirim dengan `?logOnly=true` sehingga FCM tidak dipanggil), jeda antar broadcast live (`MINI_MICRO_BROADCAST_COOLDOWN_MINUTES`), penolakan broadcast ulang konten yang sama tanpa centang Kirim ulang, pengiriman lewat queue dengan retry otomatis untuk error 5xx, dan API key yang hanya dibaca dari `.env`.
- Menambahkan field boolean `is_notification` pada berita dan banner sebagai penanda kebutuhan notifikasi, termasuk migration, input form admin, validasi dan cast model, serta output pada respons API dengan nilai awal `false`.
- Menambahkan environment Bruno `Local` dengan base URL `http://sg-admin.test/api/v1` serta memperjelas petunjuk pemilihan environment agar endpoint yang belum di-deploy dapat diuji terpisah dari Production.
- Menambahkan endpoint publik daftar Video TikTok aktif pada `/api/v1/video-tiktok`, cache JSON, dokumentasi API, pengujian Pest, serta request Bruno untuk pencarian dan pagination.
- Menambahkan tombol dan fungsi tampil/sembunyikan password yang aksesibel pada form login, dimuat langsung tanpa bergantung pada build Vite, lengkap dengan perubahan ikon, label bantuan, dan status tombol.
- Menambahkan menu superadmin Video TikTok untuk mengelola URL embed, judul, dan status tampil pada tabel `client_area_tiktok_videos`, termasuk ekstraksi otomatis video ID, preview, validasi URL TikTok, activity log, Undo edit/hapus, serta pengujian CRUD.
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
- Memperbarui keterangan penanda notifikasi pada form Berita dan Banner agar menjelaskan bahwa broadcast dilakukan oleh Superadmin dari tombol Notifikasi.
- Mengunci runtime Docker dari PHP 8.4 generik ke PHP 8.4.27 FPM untuk menutup dua celah remote code execution kritis CVE-2026-109618 dan CVE-2026-109619; seluruh service Laravel berbasis image aplikasi perlu dibangun dan dijalankan ulang agar memakai versi aman.
- Deskripsi Produk kini mendukung input Bahasa Indonesia dan English; respons API menambahkan `deskripsi_produk_id` dan `deskripsi_produk_en` sambil mempertahankan `deskripsi_produk` untuk kompatibilitas client lama.
- Menambahkan `deploy.sh` root project ke `.gitignore` agar skrip deployment lokal tidak ikut ter-commit ke repository.
- Endpoint detail publik Signal kini menggunakan UUID melalui `GET /api/v1/signal/{uuid}`; field/kolom slug Signal dihapus, data lama dibackfill melalui migration, dan cache lama tanpa UUID diperbarui otomatis.
- Autentikasi public API kini menyimpan hash SHA-256 melalui `API_KEY_HASH` dan membandingkan hash request secara constant-time; dokumentasi HTML/PDF tidak lagi menampilkan API key asli.
- Endpoint `GET /api/v1/wakil-pialang-berjangka/categories` kini mendukung parameter `page` dan `per_page` serta menyertakan informasi tautan request.
- Flash message admin kini tampil sebagai toast responsif dan dapat ditutup tanpa menggeser konten halaman.
- Scheduler cleanup Undo dijadwalkan setiap hari pukul 03:15 dan dilindungi `withoutOverlapping()`.

### Fixed
- Memperbaiki request Bruno Video TikTok dengan menjadikan environment `Local` sebagai default, mendefinisikan header autentikasi langsung pada request, memperbaiki deklarasi variabel API key lokal, dan mengabaikan file environment lokal yang menyimpan API key agar tidak ikut ter-commit.
- Memperbaiki tombol visibilitas password pada form login agar satu ikon berganti antara mata dan mata dicoret, serta menambahkan versi asset otomatis agar browser tidak memakai fungsi toggle lama dari cache.
- Memulihkan autentikasi akun superadmin pada database Docker lokal dengan memastikan password tersimpan menggunakan hash bcrypt yang didukung Laravel.
- Memperbaiki race condition `image already exists` saat `docker compose build` dengan menetapkan service `app` sebagai satu-satunya pemilik build image Laravel; queue, scheduler, dan migration memakai ulang image tersebut.
- Menyamakan field `confident` Signal sebagai input wajib pada form tambah/edit dan validasi backend agar nilai kosong ditolak sebelum mencapai kolom database yang tidak menerima `NULL`.
- Memperbaiki CRUD admin Signal dengan explicit UUID route binding untuk halaman detail, edit, update, dan hapus tanpa mengubah route key global model; URL numerik lama kini ditolak.
- Memperbaiki penyimpanan `retention_until` pada snapshot Undo agar tidak dibuang oleh mass assignment dan tidak menyebabkan error MySQL pada insert aksi Undo.

### Removed
- Menghapus service phpMyAdmin terpisah di port 8081; pengelolaan database bersama menggunakan phpMyAdmin yang tersedia di port 8080.
- Menghapus field gabungan `trade_pilot` dari respons API System Settings agar status Trade Pilot hanya dibaca secara eksplisit melalui `trade_pilot_dev` dan `trade_pilot_prod`.
- Menghapus alur tambah satuan Wakil Pialang Berjangka (tombol, halaman, route, controller, dan request) sehingga penambahan data baru dilakukan melalui Tambah Sekaligus.
