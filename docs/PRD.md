# Product Requirements Document (PRD)

## SG Admin — CMS & API Backend

|                   |                                                                            |
| ----------------- | -------------------------------------------------------------------------- |
| **Dokumen**       | Product Requirements Document                                              |
| **Produk**        | SG Admin (working name — sesuaikan dengan nama brand resmi)                |
| **Versi dokumen** | 1.0                                                                        |
| **Tanggal**       | 2026-08-03                                                                 |
| **Status**        | Reverse-engineered dari kondisi codebase saat ini (living document)        |
| **Stack**         | Laravel 13 (PHP 8.3), Blade + Tailwind CSS v4 + Vite, MySQL/SQLite, Pest 4 |

> Catatan: dokumen ini disusun dengan membaca struktur aktual aplikasi (routes, models, migrations, middleware, enum role) karena tidak ditemukan PRD sebelumnya di repo. Bagian yang bersifat asumsi bisnis (target metrik, timeline, stakeholder) ditandai perlu konfirmasi.

---

## 1. Ringkasan Eksekutif

SG Admin adalah **panel admin (CMS) internal** berbasis Laravel yang berfungsi sebagai **single source of truth** konten untuk sebuah perusahaan di industri keuangan/trading (indikasi dari section produk **SPA** dan **JFX** — Jakarta Futures Exchange). Aplikasi ini:

1. Menyediakan **panel admin berbasis Blade** untuk staff internal mengelola konten (banner, produk, berita, sinyal trading, ebook, pengumuman, legalitas, halaman legal, profil perusahaan, dsb).
2. Mengekspos **REST API publik (`/api/v1/*`)** yang dikonsumsi oleh **frontend/website publik dan/atau aplikasi mobile** milik perusahaan, diamankan dengan API key + origin allowlist.
3. Menyediakan **manajemen user & role**, **pengaturan keamanan API**, serta **audit trail (system activity log)** untuk kebutuhan governance/compliance.

Aplikasi ini **bukan** aplikasi trading itu sendiri — tidak ada eksekusi order, tidak ada data pasar real-time. Ini murni backend konten & pengaturan yang mendukung produk digital utama perusahaan (website/app).

---

## 2. Latar Belakang & Masalah yang Dipecahkan

Perusahaan membutuhkan cara terpusat untuk:

- Mengelola konten pemasaran & informasi (banner promosi, berita, pengumuman, produk SPA/JFX, penghargaan, ebook edukasi, sinyal trading) tanpa perlu deploy ulang aplikasi frontend.
- Memisahkan tanggung jawab antara tim **konten (admin/admin host)** dan tim **teknis/compliance (superadmin)**.
- Menjaga **keamanan akses API publik** (siapa boleh konsumsi, dari origin mana, dengan key apa) mengingat data yang disajikan berkaitan dengan produk keuangan yang diawasi regulator (legalitas, syarat & ketentuan, kebijakan privasi wajib selalu up-to-date dan auditable).
- Memiliki **jejak audit** siapa mengubah apa dan kapan (system activity log), penting untuk kepatuhan di industri berjizin (JFX/Bappebti-related).

---

## 3. Tujuan & Sasaran (Goals)

| Tujuan | Deskripsi                                                                                                                     |
| ------ | ----------------------------------------------------------------------------------------------------------------------------- |
| G1     | Sentralisasi seluruh konten publik perusahaan dalam satu admin panel                                                          |
| G2     | Sediakan API terdokumentasi & aman untuk dikonsumsi frontend/mobile                                                           |
| G3     | Kontrol akses berjenjang (role-based) agar staff non-teknis hanya bisa mengubah konten yang relevan dengan tugasnya           |
| G4     | Audit trail penuh untuk login, aktivitas API, dan perubahan data demi kepatuhan                                               |
| G5     | Meminimalkan downtime/salah konfigurasi lewat pengaturan client-area & API security yang self-service (tanpa perlu developer) |

### Non-Goals (di luar cakupan saat ini)

- Tidak menangani transaksi trading, data pasar real-time, atau integrasi broker/exchange.
- Tidak menjadi CRM atau sistem KYC nasabah.
- Tidak menyediakan multi-tenant (aplikasi ini didesain untuk satu perusahaan/brand).

---

## 4. Target Pengguna & Persona

| Role (enum `UserRole`) | Deskripsi                                                   | Akses                                                                                                                                                                                                                                                                       |
| ---------------------- | ----------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Superadmin**         | Pemilik sistem/IT admin tertinggi                           | Akses penuh ke semua modul, termasuk User Management, API Documentation, Client Area Settings (termasuk API Security), System Logs                                                                                                                                          |
| **Admin**              | Staff konten reguler (marketing/CS)                         | Akses ke seluruh modul konten (banner, produk, berita, sinyal, ebook, pengumuman, penghargaan, legalitas, company profile, T&C, privacy policy) — **tidak** ada akses ke User Management/API Docs/Client Area/System Logs (gate `manage-user-management` khusus superadmin) |
| **Admin Host**         | Role terbatas (kemungkinan vendor/partner content provider) | Hanya bisa akses: Dashboard, Signal + Signal Categories, Berita + Berita Categories, Ebook + Ebook Categories, upload gambar TinyMCE, logout (lihat `User::adminHostAllowedRoutePatterns()`)                                                                                |

Konsumen tidak langsung (bukan user aplikasi ini, tapi bergantung padanya):

- **Frontend website publik** — mengonsumsi `/api/v1/*` untuk render halaman publik.
- **Aplikasi mobile** — diidentifikasi lewat header `X-Client-Type: mobile-app` untuk bypass validasi Origin.
- **Pengunjung website** — mengisi form kontak (`POST /api/v1/massages`) yang di-throttle 10x/menit per IP.

---

## 5. Ruang Lingkup Fungsional (Modul)

### 5.1 Autentikasi & Otorisasi

- Login berbasis session (`AuthenticatedSessionController`), rate limit 5x/menit per email+IP (anti brute-force).
- Middleware `admin.panel.access` (`EnsureAdminPanelAccess`) membatasi rute sesuai role (khusus Admin Host).
- Middleware `admin.data.log` (`LogAdminDataActivity`) mencatat setiap perubahan data lewat panel admin.
- Gate `manage-user-management` — hanya Superadmin.

### 5.2 Manajemen Konten (CRUD penuh, via panel admin)

| Modul                  | Route prefix                       | Keterangan                                                                                  |
| ---------------------- | ---------------------------------- | ------------------------------------------------------------------------------------------- |
| Banner                 | `/banner`                          | Carousel banner promosi dengan slug & title                                                 |
| Produk                 | `/produk/{section}` (`spa`, `jfx`) | Produk per lini bisnis (SPA & JFX), termasuk spesifikasi HTML (rich text via TinyMCE/Quill) |
| Pengumuman (Informasi) | `/pengumuman`                      | Pengumuman/informasi resmi ke publik                                                        |
| Ebook + Kategori       | `/ebook`, `/kategori-ebook`        | Materi edukasi dengan kategori                                                              |
| Signal + Kategori      | `/signal`, `/kategori-signal`      | Sinyal trading (konten edukasi/analisis, bukan eksekusi order)                              |
| Berita + Kategori      | `/berita`, `/kategori-berita`      | Berita/artikel perusahaan                                                                   |
| Penghargaan            | `/penghargaan`                     | Daftar penghargaan/awards perusahaan                                                        |
| Legalitas              | `/legalitas`                       | Dokumen/izin legal perusahaan (mis. izin Bappebti/JFX)                                      |
| Profil Perusahaan      | `/profil-perusahaan`               | Single-record: nama, deskripsi, visi misi (ID/EN), alamat, maps, kontak, link komplain      |
| Syarat & Ketentuan     | `/syarat-dan-ketentuan`            | Single-record, dwibahasa (ID/EN)                                                            |
| Kebijakan Privasi      | `/kebijakan-privasi`               | Single-record, dwibahasa (ID/EN)                                                            |
| Upload gambar editor   | `/tinymce/images`                  | Endpoint upload gambar untuk rich text editor                                               |

Semua modul kategori/produk mendukung **slug otomatis dengan de-duplikasi** (`generateSlug`), dan output HTML rich-text disanitasi (strip atribut editor seperti `contenteditable`, `data-row`, class internal Quill/TinyMCE) sebelum ditampilkan.

### 5.3 User Management (Superadmin only)

- CRUD user, assign role (admin / admin_host / superadmin).
- Rute: `/user-management`.

### 5.4 Client Area & Pengaturan Sistem (Superadmin only)

- **Client Area Setting**: toggle tampil/sembunyi client area (dev/prod terpisah), toggle live chat Tawk.to (dev/prod terpisah).
- **API Security Setting**:
    - `api_enabled` — kill switch API publik.
    - `allowed_origin_frontend` — whitelist origin (CORS custom, mendukung multi-origin & wildcard `*`).
    - `api_key_rotation_notice` — pesan notifikasi rotasi API key dikirim lewat response header `X-API-Key-Rotation-Notice`.
- Konfigurasi API key aktual disimpan di config (`config('api-auth.key')`), header default `X-API-Key`.

### 5.5 API Documentation

- Halaman internal (`/dokumentasi-api`, superadmin-only) dengan render per-section dan **export PDF** — dipakai untuk memberi dokumentasi ke tim frontend/mobile eksternal.

### 5.6 System Activity Log (Audit Trail)

- 3 kategori log (`/system-logs/{category}`): **login**, **api**, **data**.
- Dicatat otomatis lewat middleware (`LogApiActivity`, `LogAdminDataActivity`) + `SystemActivityLogger` service.
- Subject katalog terdefinisi (`SystemActivitySubjectCatalog`) memetakan setiap entitas (banner, produk, signal, dst.) ke label yang human-readable, terpisah untuk log API dan log data.

### 5.7 Public REST API (`/api/v1/*`)

- Middleware stack: `api.activity.log` → `api.settings` (cek `api_enabled` + validasi Origin/allowed origins) → `api.key` (validasi `X-API-Key` dengan `hash_equals`, constant-time).
- Endpoint **read-only** (GET) untuk seluruh entitas konten publik: banner, produk (per section), pengumuman, ebook (+kategori+detail), signal (+kategori+detail), berita (+kategori+detail), penghargaan, legalitas, company-profile, terms-and-conditions, privacy-policy, client-area.
- Endpoint **write** satu-satunya: `POST /api/v1/massages` (form kontak/pesan dari publik), throttle 10x/menit/IP.
- Response API di-cache lewat `ApiJsonCacheService` untuk performa.
- Dukungan mobile app: header `X-Client-Type: mobile-app` melewati validasi Origin (karena mobile app native tidak mengirim header `Origin`).

---

## 6. Alur Pengguna Utama (User Flows)

1. **Staff konten login → kelola berita/produk/sinyal → publish → tampil otomatis di frontend via API** tanpa perlu deploy.
2. **Superadmin mengonfigurasi API security** (aktifkan/nonaktifkan API, atur allowed origin, kirim notice rotasi key) → perubahan langsung berlaku ke semua konsumen API.
3. **Pengunjung mengisi form kontak di website publik** → `POST /api/v1/massages` → tersimpan sebagai `Massage`, tercatat di log API.
4. **Superadmin investigasi insiden** → buka System Logs (login/api/data) untuk telusur siapa login gagal, siapa ubah data apa, request API mencurigakan dari mana.
5. **Admin Host (partner/vendor)** login → hanya bisa kelola Signal & Berita (+ kategori) & Ebook — tidak bisa lihat/ubah modul lain sama sekali (route-level block, bukan cuma UI hide).

---

## 7. Kebutuhan Non-Fungsional

| Aspek               | Kebutuhan                                                                                                                                                                                                                                |
| ------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Keamanan**        | API key comparison pakai `hash_equals` (anti timing attack); rate limiting login & contact form; origin allowlist untuk API publik; role-based route blocking di level middleware (bukan hanya UI); audit log wajib untuk login/API/data |
| **Ketersediaan**    | API publik punya kill-switch (`api_enabled`) agar bisa cepat dimatikan saat insiden tanpa deploy                                                                                                                                         |
| **Performa**        | Response API publik di-cache (`ApiJsonCacheService`)                                                                                                                                                                                     |
| **Auditabilitas**   | Semua perubahan data & aktivitas API tercatat by design (relevan untuk kepatuhan industri finansial)                                                                                                                                     |
| **I18n**            | Beberapa entitas legal (Company Profile, T&C, Privacy Policy) mendukung dwibahasa ID/EN                                                                                                                                                  |
| **Maintainability** | Konvensi Laravel standar: Form Request per aksi (Store/Update terpisah), API Resource per entitas, PSR-4, testing dengan Pest                                                                                                            |

---

## 8. Arsitektur & Batasan Teknis

- **Monolith Laravel** — Blade + Tailwind v4 (bukan SPA/Inertia), asset di-build via Vite.
- **Autentikasi panel**: session-based (bukan Sanctum/Passport) — hanya untuk staff internal, bukan API konsumen.
- **Autentikasi API publik**: shared API key statis (header `X-API-Key`), bukan OAuth/JWT per-klien — cocok untuk 1 frontend + 1 mobile app resmi, **tidak cocok** bila ke depan butuh banyak klien pihak ketiga dengan kuota/permission berbeda (perlu API key per klien jika scope bertambah).
- **Storage gambar**: `OptimizedImageStorage` + `ImagePath` helper — indikasi ada optimasi/resizing gambar upload otomatis.
- **Database**: migration terbaru (Juli 2026) menunjukkan skema masih aktif berevolusi (banyak migration "refine"/"repair"/"align" — indikasi iterasi cepat, perlu kehati-hatian menambah kolom baru).

---

## 9. Metrik Keberhasilan (perlu konfirmasi stakeholder)

> Belum ada instrumentasi analytics di codebase. Rekomendasi metrik yang perlu didiskusikan dengan pemilik produk:

- Waktu rata-rata publish konten (dari draft ke live) — target: near-instant tanpa deploy.
- Jumlah insiden downtime API publik akibat kesalahan konfigurasi.
- Waktu respon audit saat insiden keamanan (mis. API key bocor → waktu rotasi via notice + revoke).
- Adoption rate modul oleh masing-masing role (apakah Admin Host memang hanya pakai modul yang di-allow).

---

## 10. Risiko & Pertanyaan Terbuka

| #   | Risiko/Pertanyaan                                                                                                                                                           | Dampak                                      |
| --- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------- |
| 1   | API key bersifat tunggal/statis untuk semua konsumen — bila bocor, semua klien (web+mobile) harus rotasi bersamaan                                                          | Downtime multi-klien saat rotasi            |
| 2   | Tidak ada versi kedua (`/v2`) atau strategi deprecation API yang terlihat                                                                                                   | Breaking change ke depan berisiko           |
| 3   | Role `Admin` (bukan Admin Host) tampaknya punya akses ke **semua** modul konten termasuk yang sensitif (legalitas, company profile) — perlu konfirmasi apakah ini disengaja | Over-privilege                              |
| 4   | Tidak ditemukan 2FA untuk Superadmin                                                                                                                                        | Risiko akun admin tertinggi diretas         |
| 5   | Belum ada rate limit terlihat di level endpoint `/api/v1` GET (hanya contact-form yang di-throttle)                                                                         | Potensi API scraping/abuse konten publik    |
| 6   | Nama produk resmi ("SG Admin") hanya asumsi dari nama folder — perlu konfirmasi brand/nama perusahaan sebenarnya                                                            | Dokumen ini perlu revisi setelah konfirmasi |

---

## 11. Roadmap — AI Agent Readiness

### 11.1 Latar Belakang

Project ini sudah mulai dikembangkan dengan bantuan AI coding agent (Laravel Boost sudah terpasang, lihat `composer.json` & `boost.json`). Namun saat ini **konteks project belum dipersist** untuk agent:

| Kondisi saat ini                                 | Temuan                                                                     |
| ------------------------------------------------ | -------------------------------------------------------------------------- |
| `boost.json`                                     | Ada, tapi `"agents": ["codex"]` — belum eksplisit mendaftarkan Claude Code |
| `CLAUDE.md` / `AGENTS.md` di root                | **Tidak ada**                                                              |
| Codemap/arsitektur tersimpan (`docs/CODEMAPS/*`) | **Tidak ada**                                                              |
| Dokumentasi peran, modul, keamanan API           | Baru ada setelah PRD ini dibuat (2026-08-03)                               |

Dampaknya: setiap sesi AI agent baru (Claude Code, Codex, dst.) harus **re-explore** codebase dari nol (baca routes, models, middleware, enum role satu per satu) sebelum bisa membantu secara akurat — seperti yang terjadi saat penyusunan PRD ini. Ini memakan waktu & token, dan berisiko agent membuat asumsi keliru bila tidak sempat membaca semua bagian relevan.

### 11.2 Tujuan

Menjadikan project ini **"agent-ready"**: AI coding agent mana pun (Claude Code, Codex, Cursor, dll.) dapat langsung memahami arsitektur, modul, role, dan aturan keamanan project ini di awal sesi tanpa re-derivasi manual, dan tetap akurat seiring project berkembang.

### 11.3 Inisiatif yang Diusulkan

| #   | Inisiatif                                                | Deskripsi                                                                                                                                                                | Prioritas |
| --- | -------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | --------- |
| 1   | **Tambahkan `CLAUDE.md` di root repo**                   | Ringkasan project (mengacu ke PRD ini), konvensi kode, daftar role & gate, peta modul, cara jalankan test (`composer test`), catatan "jangan lakukan X" spesifik project | Tinggi    |
| 2   | **Daftarkan Claude ke `boost.json`**                     | Tambahkan `"claude"` ke array `agents` di `boost.json` agar Laravel Boost menyediakan tools/skills yang sama ke Claude Code seperti ke Codex                             | Tinggi    |
| 3   | **Generate & maintain codemap** (`docs/CODEMAPS/`)       | Peta arsitektur ringkas (controllers ↔ models ↔ routes ↔ middleware) yang di-refresh tiap ada modul baru, supaya agent tidak perlu grep manual seperti di Lampiran §12   | Sedang    |
| 4   | **Jadikan PRD ini living document**                      | Setiap penambahan modul/role/endpoint baru wajib update §5 (Ruang Lingkup) dan §12 (Peta Modul) di PRD ini, agar tidak basi                                              | Sedang    |
| 5   | **Dokumentasikan aturan keamanan eksplisit untuk agent** | Catatan tegas di `CLAUDE.md`: jangan hardcode API key, jangan bypass `hash_equals`, jangan expose field sensitif lewat API Resource baru tanpa review                    | Sedang    |
| 6   | **(Opsional, jangka panjang) MCP server internal**       | Server MCP kecil yang mengekspos ringkasan skema DB & daftar endpoint API secara terstruktur ke agent, sebagai pelengkap dokumentasi statis                              | Rendah    |

### 11.4 Non-Goals (tegaskan batasan)

- Ini **bukan** rencana menambahkan fitur AI-facing untuk end-user (chatbot publik, asisten trading, dsb.) — itu topik terpisah bila dibutuhkan ke depan.
- Tidak mengubah arsitektur aplikasi; murni memperbaiki _discoverability_ project untuk tooling AI development.

### 11.5 Definition of Done

- [ ] `CLAUDE.md` ada di root, merujuk ke `docs/PRD.md`
- [ ] `boost.json` mencantumkan `claude` di `agents`
- [ ] Codemap awal ter-generate di `docs/CODEMAPS/`
- [ ] Sesi AI agent baru dapat menjawab "role apa saja yang ada & apa batasannya" tanpa membaca ulang seluruh `routes/web.php` dan `User.php`

---

## 12. Lampiran — Peta Modul Teknis (referensi cepat)

```
Controllers (Admin Panel)     Controllers (Public API v1)         Model
─────────────────────────     ─────────────────────────           ─────
BannerController               BannerApiController                 Banner
ProdukController               ProdukApiController                 Produk
InformasiController            InformasiApiController              Informasi
EbookController/Category       EbookApiController/Category         Ebook, EbookCategory
SignalController/Category      SignalApiController/Category        Signal, SignalCategory
BeritaController/Category      BeritaApiController/Category        Berita, BeritaCategory
PenghargaanController          PenghargaanApiController            Penghargaan
LegalitasController            LegalitasApiController              Legalitas
CompanyProfileController       CompanyProfileController (Api)      CompanyProfile
TermsAndConditionsController   TermsAndConditionsApiController     TermsAndCondition
PrivacyPolicyController        PrivacyPolicyApiController          PrivacyPolicy
ClientAreaSettingController    ClientAreaApiController             ClientAreaSetting
—                              MassageApiController (POST only)    Massage
UserManagementController       —                                   User
SystemLogController            —                                   SystemActivityLog
ApiDocumentationController     —                                   —
```

---

_Dokumen ini disusun otomatis berdasarkan analisis kode per 2026-08-03. Mohon divalidasi oleh product owner/stakeholder bisnis untuk bagian tujuan bisnis, metrik, dan roadmap yang tidak dapat disimpulkan dari kode._
