# SG Admin Public API - Bruno Collection

1. Buka folder ini dari Bruno melalui **Open Collection**.
2. Pilih environment **Production**.
3. Isi secret environment variable `apiKey` dengan API key production.
4. Jalankan request yang dibutuhkan.

Base URL production sudah disetel ke `https://sg-admin.sg-berjangka.com/api/v1`.

Semua request mewarisi header berikut dari `collection.bru`:

- `Accept: application/json`
- `X-API-Key: {{apiKey}}`
- `X-Client-Type: mobile-app`

Parameter query opsional dinonaktifkan secara default. Aktifkan parameter tersebut dari tab **Params** ketika dibutuhkan. Request `POST /massages` membuat data production dan terkena rate limit 10 request per menit per IP.
