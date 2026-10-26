# SG Admin Public API - Bruno Collection

1. Buka folder ini dari Bruno melalui **Open Collection**.
2. Pilih environment **Local** untuk menguji source lokal atau **Production** untuk API yang sudah di-deploy.
3. Environment `Local` sudah memakai API key lokal dari file yang diabaikan Git. Untuk `Production`, isi secret environment variable `apiKey` melalui Bruno.
4. Jalankan request yang dibutuhkan.

Base URL production sudah disetel ke `https://sg-admin.sg-berjangka.com/api/v1`.
Base URL lokal sudah disetel ke `http://sg-admin.test/api/v1`.

Semua request mewarisi header berikut dari `collection.bru`:

- `Accept: application/json`
- `X-API-Key: {{apiKey}}`
- `X-Client-Type: mobile-app`

Parameter query opsional dinonaktifkan secara default. Aktifkan parameter tersebut dari tab **Params** ketika dibutuhkan. Request `POST /massages` membuat data production dan terkena rate limit 10 request per menit per IP.

Folder **Video TikTok** menyediakan request daftar video aktif pada path `/video-tiktok` dengan dukungan pencarian dan pagination.
