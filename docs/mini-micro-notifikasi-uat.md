# Integrasi notifikasi Mini Micro: tindak lanjut UAT

## Status

SG Admin sudah memiliki fitur broadcast push notification melalui API Mini Micro. Format request yang diimplementasikan sesuai dengan dokumen integrasi yang diterima. Namun, pengujian langsung ke UAT belum berhasil: server mengembalikan HTTP 200 dengan `headers.statusCode` 404 dan pesan `Not Found.`. Pengiriman ke perangkat belum terverifikasi.

## Implementasi di SG Admin

- Menu Notifikasi Push tersedia untuk Superadmin, dengan pembuatan broadcast manual dan riwayat pengiriman.
- Topic yang didukung adalah `Announcement` dan `Promotion`; judul maksimal 40 karakter dan deskripsi maksimal 150 karakter.
- Berita yang ditandai untuk notifikasi dapat dikirim sebagai `Announcement` dengan `news_id`. Banner yang ditandai dapat dikirim sebagai `Promotion` dengan `promotion_id`. ID dikirim sebagai data untuk aplikasi; kedua ID tidak dikirim bersamaan.
- Pengiriman masuk antrean. Riwayat mencatat status, respons dan kesalahan; notifikasi gagal dapat dicoba ulang. Broadcast live memiliki jeda dan konfirmasi untuk mengurangi pengiriman tidak disengaja.
- Dry run memakai parameter `logOnly=true`. Konfigurasi terakhir yang diperiksa di lingkungan lokal memakai `MINI_MICRO_DRY_RUN=true` dan URL UAT. Pengiriman live yang diuji langsung sebelumnya tidak mengubah konfigurasi tersebut.
- Saat ini SG Admin hanya mendukung broadcast. Pengiriman ke satu akun menggunakan `noAccount`, yang juga dijelaskan dalam dokumen API, belum tersedia di menu.
- Status sukses memeriksa kode HTTP dan `headers.statusCode` jika tersedia. Status sukses API belum membuktikan notifikasi diterima atau ditampilkan di perangkat.

## Request yang digunakan

```http
POST https://mmapiuat.sgberjangka.id/system/private/send-manual-notification
Content-Type: application/json
X-Api-Key: <SAI_API_KEY>
```

Contoh payload broadcast:

```json
{
  "topic": "Announcement",
  "title": "Tes integrasi UAT",
  "description": "Pengujian integrasi notifikasi UAT",
  "broadcast": true
}
```

Untuk dry run, URL ditambahkan `?logOnly=true`. API key dibaca dari konfigurasi lingkungan dan tidak disertakan dalam dokumen ini.

## Hasil pengujian langsung UAT

| Skenario | Hasil aktual | Hasil yang diharapkan menurut dokumen |
| --- | --- | --- |
| Broadcast dengan API key yang dikonfigurasi, dry run | HTTP 200; isi `headers.statusCode: 404`, `message: "Not Found."` | Request valid diterima sebagai dry run; FCM tidak dipanggil. |
| Broadcast dengan API key yang dikonfigurasi, tanpa `logOnly` | HTTP 200; isi `headers.statusCode: 404`, `message: "Not Found."` | Request valid diproses sebagai broadcast live. |
| Tanpa `X-Api-Key`, dry run | HTTP 200; isi `headers.statusCode: 404`, `message: "Not Found."` | HTTP 401. |
| Dengan API key yang sengaja salah, dry run | HTTP 200; isi `headers.statusCode: 404`, `message: "Not Found."` | HTTP 401. |

Respons yang diterima pada keempat skenario:

```json
{"headers":{"statusCode":404,"message":"Not Found."}}
```

Dokumen API menyebut respons 404 untuk device token yang tidak ditemukan pada endpoint manual. Karena uji broadcast tanpa key dan dengan key salah juga menghasilkan respons yang sama, penyebab 404 ini belum dapat dipastikan. Respons tersebut tidak membuktikan apakah request mencapai handler endpoint, maupun apakah API key dan payload telah divalidasi.

## Mohon konfirmasi tim Mini Micro

1. Apakah URL dan path di atas sudah benar dan endpoint manual notification sudah tersedia pada environment UAT yang dituju?
2. Apakah routing, gateway, deployment, atau akses jaringan UAT memerlukan konfigurasi tambahan?
3. Mengapa permintaan tanpa API key dan dengan key salah menghasilkan HTTP 200 dengan envelope 404, bukan HTTP 401 seperti pada dokumen? Apa arti 404 tersebut untuk request broadcast ini?
4. Apakah format payload dan nama header pada contoh di atas sudah sesuai untuk endpoint UAT? Mohon konfirmasi melalui kanal aman bila perlu memeriksa API key; jangan kirim nilainya di dokumen atau chat umum.
5. Mohon berikan contoh respons sukses dry run dan live, serta cara memverifikasi bahwa broadcast UAT diteruskan ke FCM dan diterima perangkat uji.

## Pengujian aplikasi

Pengujian lokal modul push notification terakhir: 25 tes lulus, 124 assertion. Pengujian ini menggunakan respons HTTP tiruan dan tidak membuktikan koneksi UAT atau penerimaan notifikasi pada perangkat.
