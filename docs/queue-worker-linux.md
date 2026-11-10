# Queue worker SG Admin di server Linux

Notifikasi dengan `QUEUE_CONNECTION=database` masuk ke tabel `jobs` dan membutuhkan worker yang terus berjalan. Untuk Linux tanpa Docker, contoh konfigurasi Supervisor tersedia di `deploy/supervisor/sg-admin-queue.conf.example`.

## Sebelum menyalakan worker

- Pastikan konfigurasi Supervisor memakai database dan `.env` SG Admin UAT yang dimaksud. Worker memproses **semua job yang tersedia pada antrean database default**, bukan hanya notifikasi tertentu.
- Periksa job yang sudah menunggu dan kolom `dry_run` pada setiap notifikasi yang terkait. Mode tersimpan saat notifikasi dibuat; nilai `.env` sekarang tidak mengubah job lama. Jika job lama memiliki `dry_run=0`, menyalakan worker dapat mengirim broadcast sungguhan ke server tujuan yang dikonfigurasi.
- Siapkan user Linux yang memiliki akses ke project serta izin menulis ke `storage` dan `bootstrap/cache`. Pastikan binary PHP CLI memiliki ekstensi yang dibutuhkan aplikasi.
- Contoh memakai timeout 60 detik. Pastikan `DB_QUEUE_RETRY_AFTER` lebih besar dari timeout tersebut (default project: 90 detik).

## Pemasangan oleh administrator server

1. Pastikan Supervisor terpasang dan aktif pada server.
2. Salin contoh konfigurasi ke direktori konfigurasi Supervisor server (umumnya `/etc/supervisor/conf.d/sg-admin-queue.conf`). Ganti `/PATH/KE/SG-ADMIN`, `/PATH/KE/PHP`, dan `USER_APLIKASI` dengan nilai yang benar. Path dan direktori konfigurasi bergantung pada server.
3. Setelah job yang menunggu diperiksa, jalankan `sudo supervisorctl reread`, lalu `sudo supervisorctl update`. Dengan `autostart=true`, Supervisor akan memulai worker dan memproses job yang tersedia.
4. Cek `sudo supervisorctl status sg-admin-queue` dan log di `storage/logs/queue-worker.log`. Periksa status notifikasi dan `error_message` di riwayat SG Admin.

Supervisor harus dikonfigurasi agar aktif setelah server boot. `autorestart=true` akan menjalankan kembali worker jika proses berhenti. Setelah deploy atau perubahan `.env`, jalankan `php artisan queue:restart` dari direktori aplikasi agar worker memuat kode dan konfigurasi terbaru; Supervisor akan menyalakan proses penggantinya. Perintah tersebut juga dapat memengaruhi worker queue lain yang memakai cache aplikasi yang sama.

Status `dry_run` hanya berarti request simulasi berhasil; FCM tidak dipanggil. Status `sent` menunjukkan endpoint menerima permintaan, bukan bukti perangkat menampilkan notifikasi.
