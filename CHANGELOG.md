## [Unreleased]

### Added
- Menambahkan metadata pagination JSON pada endpoint daftar kategori Wakil Pialang Berjangka agar client dapat membaca halaman aktif, jumlah item per halaman, total data, rentang data, dan halaman terakhir.
- Menambahkan toast pemberitahuan di pojok kanan bawah dengan aksi Undo selama 10 detik untuk perubahan edit dan hapus pada seluruh modul CRUD admin.
- Menambahkan snapshot Undo terenkripsi, token sekali pakai yang terikat ke user, pemeriksaan konflik perubahan, serta pencadangan privat untuk gambar dan file yang dihapus atau diganti.
- Menambahkan retensi snapshot Undo selama 24 jam dan command terjadwal `crud-undo:prune` untuk menghapus permanen snapshot serta backup file yang sudah melewati retensi.

### Changed
- Endpoint `GET /api/v1/wakil-pialang-berjangka/categories` kini mendukung parameter `page` dan `per_page` serta menyertakan informasi tautan request.
- Flash message admin kini tampil sebagai toast responsif dan dapat ditutup tanpa menggeser konten halaman.
- Scheduler cleanup Undo dijadwalkan setiap hari pukul 03:15 dan dilindungi `withoutOverlapping()`.

### Fixed
- Memperbaiki penyimpanan `retention_until` pada snapshot Undo agar tidak dibuang oleh mass assignment dan tidak menyebabkan error MySQL pada insert aksi Undo.
