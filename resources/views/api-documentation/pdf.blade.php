<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumentasi API Lengkap</title>
    <style>
        @page {
            size: A4;
            margin: 20mm 18mm 24mm;
        }

        :root {
            --ink: #1a1a24;
            --body-text: #3a3a45;
            --muted: #767686;
            --border: #e3e1db;
            --surface: #faf9f6;
            --accent: #8f6832;
            --accent-soft: #f3e9d6;
            --accent-line: #c7a15a;
            --code-bg: #1b1611;
            --code-border: #322a20;
            --code-text: #ece1cf;
            --code-accent: #e2c78f;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: var(--body-text);
            background: #ffffff;
            font-family: "Segoe UI", Arial, Helvetica, sans-serif;
            font-size: 11.5px;
            line-height: 1.65;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .page {
            max-width: 760px;
            margin: 0 auto;
            padding: 8px 4px 40px;
        }

        .toolbar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .print-button {
            border: 1px solid var(--accent-line);
            background: var(--accent-soft);
            color: var(--accent);
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .doc-footer {
            position: fixed;
            left: 18mm;
            right: 18mm;
            bottom: 8mm;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 8.5px;
            letter-spacing: .04em;
            color: var(--muted);
            border-top: 1px solid var(--border);
            padding-top: 6px;
        }

        .doc-footer strong {
            color: var(--accent);
        }

        .cover {
            min-height: 235mm;
            display: flex;
            flex-direction: column;
            page-break-after: always;
        }

        .cover-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .cover-brand img {
            height: 38px;
            width: auto;
        }

        .cover-brand .app-name {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .04em;
            color: var(--ink);
        }

        .kicker {
            display: inline-block;
            margin-top: 56px;
            text-transform: uppercase;
            letter-spacing: .22em;
            font-size: 10px;
            font-weight: 700;
            color: var(--accent);
        }

        .cover h1 {
            margin: 14px 0 0;
            font-size: 36px;
            line-height: 1.2;
            color: var(--ink);
            max-width: 480px;
        }

        .cover-sub {
            margin: 16px 0 0;
            max-width: 440px;
            font-size: 13px;
            line-height: 1.7;
            color: var(--muted);
        }

        .cover-meta {
            margin-top: 48px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 32px;
        }

        .cover-meta dt {
            text-transform: uppercase;
            letter-spacing: .14em;
            font-size: 9px;
            font-weight: 700;
            color: var(--muted);
        }

        .cover-meta dd {
            margin: 5px 0 0;
            font-family: "Courier New", Courier, monospace;
            font-size: 12px;
            color: var(--ink);
            word-break: break-word;
        }

        .cover-footer-note {
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px dashed var(--border);
            font-size: 9.5px;
            color: var(--muted);
            display: flex;
            justify-content: space-between;
            gap: 16px;
        }

        .toc-page {
            page-break-after: always;
        }

        .toc-page h2 {
            font-size: 20px;
            color: var(--ink);
            margin: 0 0 4px;
        }

        .toc-page .toc-intro {
            margin: 0 0 20px;
            font-size: 11.5px;
            color: var(--muted);
        }

        .toc-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .toc-item {
            display: flex;
            align-items: baseline;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px dotted var(--border);
        }

        .toc-num {
            font-family: "Courier New", Courier, monospace;
            font-size: 11px;
            font-weight: 700;
            color: var(--accent);
            width: 20px;
            flex-shrink: 0;
        }

        .toc-label {
            font-weight: 700;
            font-size: 12.5px;
            color: var(--ink);
            width: 150px;
            flex-shrink: 0;
        }

        .toc-desc {
            font-size: 10.5px;
            color: var(--muted);
        }

        .doc-section {
            page-break-before: always;
        }

        .section-heading {
            display: flex;
            align-items: baseline;
            gap: 12px;
            margin-bottom: 6px;
            border-bottom: 2px solid var(--accent-line);
            padding-bottom: 10px;
        }

        .section-number {
            font-family: "Courier New", Courier, monospace;
            font-size: 11px;
            font-weight: 700;
            color: var(--accent);
            border: 1px solid var(--accent-line);
            border-radius: 4px;
            padding: 2px 8px;
        }

        .section-heading h2 {
            margin: 0;
            font-size: 19px;
            color: var(--ink);
        }

        .section-lead {
            margin: 12px 0 18px;
            font-size: 11.5px;
            line-height: 1.7;
            color: var(--body-text);
        }

        h3.block-title {
            margin: 22px 0 10px;
            font-size: 12.5px;
            font-weight: 700;
            color: var(--ink);
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin: 0 0 16px;
        }

        .card-grid.cols-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        .card {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 13px;
            background: var(--surface);
        }

        .card-eyebrow {
            text-transform: uppercase;
            letter-spacing: .12em;
            font-size: 8.5px;
            font-weight: 700;
            color: var(--muted);
        }

        .card-value {
            margin-top: 6px;
            font-family: "Courier New", Courier, monospace;
            font-size: 11px;
            color: var(--accent);
            word-break: break-all;
        }

        .card-desc {
            margin-top: 8px;
            font-size: 10.5px;
            line-height: 1.6;
            color: var(--body-text);
        }

        .step-index {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--accent-soft);
            color: var(--accent);
            font-weight: 700;
            font-size: 10.5px;
            margin-bottom: 8px;
        }

        .step-card .card-title {
            font-size: 11.5px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 6px;
        }

        .panel {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 13px;
        }

        .panel h4 {
            margin: 0 0 8px;
            font-size: 11px;
            font-weight: 700;
            color: var(--ink);
        }

        .panel ul {
            margin: 0;
            padding-left: 15px;
        }

        .panel li {
            margin-bottom: 6px;
            font-size: 10.5px;
            line-height: 1.6;
            color: var(--body-text);
        }

        .panel li:last-child {
            margin-bottom: 0;
        }

        code.kbd {
            font-family: "Courier New", Courier, monospace;
            font-size: 10.5px;
            color: var(--accent);
            background: var(--accent-soft);
            padding: 1px 5px;
            border-radius: 3px;
        }

        .callout {
            display: flex;
            gap: 8px;
            align-items: flex-start;
            border-radius: 8px;
            padding: 11px 14px;
            font-size: 11px;
            line-height: 1.6;
            margin: 4px 0 16px;
        }

        .callout .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            margin-top: 5px;
            flex-shrink: 0;
        }

        .callout.success {
            background: #eefaf3;
            border: 1px solid #b8e6cc;
            color: #14603f;
        }

        .callout.success .dot {
            background: #2f9e63;
        }

        .callout.warning {
            background: #fdf6ea;
            border: 1px solid #eed9ac;
            color: #8a5e12;
        }

        .callout.warning .dot {
            background: #c98a13;
        }

        .kv-box {
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--surface);
            padding: 11px 14px;
            margin: 10px 0;
        }

        .kv-box .kv-label {
            text-transform: uppercase;
            letter-spacing: .12em;
            font-size: 8.5px;
            font-weight: 700;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .kv-box .kv-row {
            font-family: "Courier New", Courier, monospace;
            font-size: 10.5px;
            color: var(--accent);
        }

        .endpoint-group {
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .endpoint-group-header {
            background: var(--surface);
            padding: 9px 14px;
            border-bottom: 1px solid var(--border);
        }

        .endpoint-group-header .eyebrow {
            text-transform: uppercase;
            letter-spacing: .12em;
            font-size: 8.5px;
            font-weight: 700;
            color: var(--muted);
        }

        .endpoint-group-header .title {
            margin-top: 2px;
            font-size: 11.5px;
            font-weight: 700;
            color: var(--ink);
        }

        table.endpoint-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.endpoint-table th {
            background: #fbfaf8;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: .1em;
            font-size: 8.3px;
            font-weight: 700;
            color: var(--muted);
            padding: 7px 14px;
            border-bottom: 1px solid var(--border);
        }

        table.endpoint-table td {
            padding: 7px 14px;
            border-bottom: 1px solid #f0efe9;
            font-size: 10.5px;
            vertical-align: top;
        }

        table.endpoint-table tr:last-child td {
            border-bottom: none;
        }

        .badge-method {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            border: 1px solid;
            white-space: nowrap;
        }

        .badge-method.get {
            background: #eef4fd;
            border-color: #bcd6f7;
            color: #1d4ed8;
        }

        .badge-method.post {
            background: #eafbf2;
            border-color: #a9e2c4;
            color: #0f7a4c;
        }

        .badge-method.put,
        .badge-method.patch {
            background: #fdf6e6;
            border-color: #eed9ac;
            color: #8a5e12;
        }

        .badge-method.delete {
            background: #fdecec;
            border-color: #f3bcbc;
            color: #b91c1c;
        }

        .endpoint-path {
            font-family: "Courier New", Courier, monospace;
            color: var(--ink);
        }

        .code-card {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--code-border);
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .code-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 8px 14px;
            background: #241d16;
            border-bottom: 1px solid var(--code-border);
        }

        .code-card-header .label {
            font-size: 10.5px;
            font-weight: 700;
            color: var(--code-text);
        }

        .code-card-header .tag {
            font-family: "Courier New", Courier, monospace;
            font-size: 8.5px;
            color: var(--code-accent);
            border: 1px solid var(--code-border);
            border-radius: 3px;
            padding: 1px 6px;
        }

        pre.code-block {
            margin: 0;
            padding: 13px 14px;
            background: var(--code-bg);
            font-family: "Courier New", Courier, monospace;
            font-size: 10px;
            line-height: 1.7;
            color: var(--code-text);
            white-space: pre-wrap;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        table.status-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
        }

        table.status-table td {
            padding: 8px 14px;
            font-size: 10.5px;
            border-bottom: 1px solid #f0efe9;
        }

        table.status-table tr:last-child td {
            border-bottom: none;
        }

        table.status-table td:first-child {
            width: 60px;
            font-family: "Courier New", Courier, monospace;
            font-weight: 700;
        }

        td.status-error {
            color: #b91c1c;
        }

        td.status-warning {
            color: #8a5e12;
        }

        @media print {
            .toolbar {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    @php
        $headerExample = $apiKeyHeader . ': ' . (filled($apiKeyValue) ? $apiKeyValue : 'isi-api-key-di-env');
        $printedAt = now('Asia/Jakarta')->format('d F Y, H:i') . ' WIB';
        $tocDescriptions = [
            'getting-started' => 'Alur dasar memakai public API dari nol.',
            'authentication' => 'Cara kerja API key untuk client website dan mobile app.',
            'endpoints' => 'Daftar lengkap endpoint per kategori data.',
            'query-params' => 'Parameter query untuk pagination, pencarian, dan filter.',
            'examples' => 'Contoh request cURL dan daftar error response.',
        ];
    @endphp

    <div class="page">
        <div class="toolbar">
            <button type="button" onclick="window.print()" class="print-button">Download PDF</button>
        </div>

        <section class="cover">
            <div class="cover-brand">
                <img src="{{ asset('assets/logo-utama.png') }}" alt="SGB Admin">
                <span class="app-name">SGB Admin</span>
            </div>

            <span class="kicker">Dokumentasi Teknis &middot; Public API</span>
            <h1>Dokumentasi API Lengkap</h1>
            <p class="cover-sub">
                Panduan integrasi resmi untuk tim frontend website dan aplikasi mobile yang mengonsumsi
                public API SGB Admin. Dokumen ini mencakup autentikasi, daftar endpoint, query
                parameter, serta contoh request siap pakai.
            </p>

            <dl class="cover-meta">
                <div>
                    <dt>Base URL</dt>
                    <dd>{{ $apiBaseUrl }}</dd>
                </div>
                <div>
                    <dt>Header Autentikasi</dt>
                    <dd>{{ $apiKeyHeader }}</dd>
                </div>
                <div>
                    <dt>Client Mobile</dt>
                    <dd>{{ $mobileClientHeader }}: {{ $mobileClientValue }}</dd>
                </div>
                <div>
                    <dt>Tanggal Terbit</dt>
                    <dd>{{ $printedAt }}</dd>
                </div>
            </dl>

            <div class="cover-footer-note">
                <span>Dokumen internal &mdash; untuk kebutuhan integrasi resmi, jangan disebarluaskan.</span>
                <span>SGB Admin &middot; Public API</span>
            </div>
        </section>

        <section class="toc-page">
            <h2>Daftar Isi</h2>
            <p class="toc-intro">Ringkasan bagian yang dibahas dalam dokumen ini.</p>

            <ol class="toc-list">
                @foreach ($docSections as $section)
                    <li class="toc-item">
                        <span class="toc-num">{{ str_pad((string) ($loop->iteration), 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="toc-label">{{ $section['label'] }}</span>
                        <span class="toc-desc">
                            {{ $tocDescriptions[$section['key']] ?? 'Bagian dokumentasi API.' }}
                        </span>
                    </li>
                @endforeach
            </ol>
        </section>

        @foreach ($docSections as $section)
            <section class="doc-section">
                <div class="section-heading">
                    <span class="section-number">{{ str_pad((string) ($loop->iteration), 2, '0', STR_PAD_LEFT) }}</span>
                    <h2>{{ $section['label'] }}</h2>
                </div>

                @if ($section['key'] === 'getting-started')
                    <p class="section-lead">
                        Halaman ini merangkum langkah awal yang harus disiapkan sebelum frontend website atau mobile app
                        mulai memakai public API. Fokus utamanya adalah memastikan API key benar, tipe client sesuai,
                        dan request dikirim ke base URL yang tepat.
                    </p>

                    <div class="card-grid">
                        <div class="card">
                            <div class="card-eyebrow">Base URL</div>
                            <div class="card-value">{{ $apiBaseUrl }}</div>
                            <p class="card-desc">Semua endpoint public API memakai base URL ini sebagai titik awal request.</p>
                        </div>
                        <div class="card">
                            <div class="card-eyebrow">Authentication</div>
                            <div class="card-value">{{ $apiKeyHeader }}</div>
                            <p class="card-desc">Header ini wajib ikut di semua request, baik dari website maupun aplikasi mobile.</p>
                        </div>
                        <div class="card">
                            <div class="card-eyebrow">Response</div>
                            <div class="card-value">application/json</div>
                            <p class="card-desc">Response dikembalikan dalam format JSON agar mudah dipakai frontend terpisah.</p>
                        </div>
                    </div>

                    <h3 class="block-title">Langkah Awal</h3>
                    <div class="card-grid">
                        <div class="card step-card">
                            <span class="step-index">1</span>
                            <div class="card-title">Siapkan API Key</div>
                            <p class="card-desc">
                                Semua endpoint <code class="kbd">/api/v1</code> wajib memakai header
                                <code class="kbd">{{ $apiKeyHeader }}</code>. API key ini menjadi identitas utama
                                request.
                            </p>
                        </div>
                        <div class="card step-card">
                            <span class="step-index">2</span>
                            <div class="card-title">Pilih Tipe Client</div>
                            <p class="card-desc">
                                Website memakai header <code class="kbd">Origin</code>. Mobile app memakai
                                <code class="kbd">{{ $mobileClientHeader }}</code>.
                            </p>
                        </div>
                        <div class="card step-card">
                            <span class="step-index">3</span>
                            <div class="card-title">Hit Endpoint</div>
                            <p class="card-desc">
                                Kirim request ke endpoint yang dibutuhkan dengan header lengkap, lalu olah response JSON
                                di frontend.
                            </p>
                        </div>
                    </div>

                    @if (filled($apiKeyValue))
                        <div class="callout success">
                            <span class="dot"></span>
                            <div>API key saat ini sudah tersedia di server dan siap dipakai untuk integrasi resmi.</div>
                        </div>
                    @else
                        <div class="callout warning">
                            <span class="dot"></span>
                            <div>API key belum dikonfigurasi di environment server. Public API tidak akan bisa dipakai sebelum value ini diisi.</div>
                        </div>
                    @endif

                    <div class="card-grid cols-2">
                        <div class="panel">
                            <h4>Alur Integrasi Singkat</h4>
                            <ul>
                                <li>Pilih endpoint yang dibutuhkan dari dokumentasi.</li>
                                <li>Tambahkan header API key ke request.</li>
                                <li>Tentukan apakah request berasal dari website atau mobile app.</li>
                                <li>Uji lewat Postman atau terminal sebelum dipakai di frontend production.</li>
                            </ul>
                        </div>
                        <div class="panel">
                            <h4>Kesalahan Umum</h4>
                            <ul>
                                <li>API key salah atau tidak dikirim.</li>
                                <li>Origin website tidak sesuai dengan konfigurasi backdoor.</li>
                                <li>Header mobile app tidak dikirim pada aplikasi native.</li>
                                <li>Public API sedang dimatikan dari system settings.</li>
                            </ul>
                        </div>
                    </div>
                @elseif ($section['key'] === 'authentication')
                    <p class="section-lead">
                        Public API ini tidak memakai login session atau JWT per user. Proteksi dilakukan dengan
                        kombinasi API key dan identitas client, sehingga lebih cocok untuk frontend website dan aplikasi
                        mobile yang hanya mengambil data publik.
                    </p>

                    <div class="card-grid cols-2">
                        <div class="card">
                            <div class="card-eyebrow">Website Flow</div>
                            <div class="card-desc">
                                Website wajib mengirim <code class="kbd">{{ $apiKeyHeader }}</code> dan
                                <code class="kbd">Origin</code> yang sudah terdaftar di backdoor.
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-eyebrow">Mobile App Flow</div>
                            <div class="card-desc">
                                Mobile app wajib mengirim <code class="kbd">{{ $apiKeyHeader }}</code> dan
                                <code class="kbd">{{ $mobileClientHeader }}</code> tanpa membutuhkan origin browser.
                            </div>
                        </div>
                    </div>

                    <h3 class="block-title">Website</h3>
                    <div class="kv-box">
                        <div class="kv-label">Header Wajib</div>
                        <div class="kv-row">{{ $apiKeyHeader }}: {{ filled($apiKeyValue) ? $apiKeyValue : 'your-api-key' }}</div>
                        <div class="kv-row">Origin: {{ $webOriginExample }}</div>
                    </div>
                    <div class="panel" style="margin-bottom: 16px;">
                        <h4>Catatan Website</h4>
                        <ul>
                            <li>Jika header <code class="kbd">Origin</code> tidak dikirim, request akan ditolak.</li>
                            <li>Jika origin tidak cocok dengan daftar backdoor, request juga ditolak.</li>
                            <li>Mode ini dipakai untuk domain production atau development yang memang didaftarkan.</li>
                        </ul>
                    </div>

                    <h3 class="block-title">Mobile App</h3>
                    <div class="kv-box">
                        <div class="kv-label">Header Wajib</div>
                        <div class="kv-row">{{ $apiKeyHeader }}: {{ filled($apiKeyValue) ? $apiKeyValue : 'your-api-key' }}</div>
                        <div class="kv-row">{{ $mobileClientHeader }}: {{ $mobileClientValue }}</div>
                    </div>
                    <div class="panel" style="margin-bottom: 16px;">
                        <h4>Catatan Mobile App</h4>
                        <ul>
                            <li>Dipakai untuk request dari aplikasi native Android atau iOS.</li>
                            <li>Tidak memakai header <code class="kbd">Origin</code> seperti browser.</li>
                            <li>Jika value header client type salah, request tetap akan ditolak.</li>
                        </ul>
                    </div>

                    <div class="card-grid cols-2">
                        <div class="panel">
                            <h4>Kapan Website Ditolak</h4>
                            <ul>
                                <li>Header auth tidak ada atau nilainya salah.</li>
                                <li>Header <code class="kbd">Origin</code> tidak dikirim.</li>
                                <li>Origin tidak ada pada daftar allowed origin.</li>
                                <li>Public API sedang dimatikan.</li>
                            </ul>
                        </div>
                        <div class="panel">
                            <h4>Kapan Mobile App Ditolak</h4>
                            <ul>
                                <li>Header auth tidak ada atau tidak valid.</li>
                                <li>Header <code class="kbd">{{ $mobileClientHeader }}</code> tidak dikirim.</li>
                                <li>Value client type bukan <code class="kbd">{{ $mobileClientValue }}</code>.</li>
                                <li>Public API sedang dimatikan dari backdoor.</li>
                            </ul>
                        </div>
                    </div>
                @elseif ($section['key'] === 'endpoints')
                    <p class="section-lead">
                        Semua endpoint berikut berada dalam namespace <code class="kbd">{{ $apiBaseUrl }}</code>.
                        Pengelompokan dibuat berdasarkan kategori data agar tim frontend lebih cepat menemukan resource
                        yang dibutuhkan.
                    </p>

                    <div class="card-grid cols-2" style="margin-bottom: 18px;">
                        <div class="panel">
                            <h4>Konvensi yang Dipakai</h4>
                            <ul>
                                <li>Path tanpa parameter biasanya dipakai untuk list data.</li>
                                <li>Path dengan <code class="kbd">{slug}</code> dipakai untuk detail data.</li>
                                <li>Mayoritas endpoint public bersifat <code class="kbd">GET</code>.</li>
                                <li>Struktur response perlu dianggap sebagai kontrak integrasi frontend.</li>
                            </ul>
                        </div>
                        <div class="panel">
                            <h4>Catatan Integrasi</h4>
                            <ul>
                                <li>Mulai dari endpoint list dulu sebelum memakai endpoint detail.</li>
                                <li>Pastikan slug untuk endpoint detail berasal dari data list yang valid.</li>
                                <li>Data list yang sering dipakai sebaiknya dicache di frontend.</li>
                                <li>Jika ada menu baru, dokumentasi endpoint juga harus diperbarui.</li>
                            </ul>
                        </div>
                    </div>

                    @foreach ($endpointGroups as $group)
                        <div class="endpoint-group">
                            <div class="endpoint-group-header">
                                <div class="eyebrow">{{ $group['title'] }}</div>
                                <div class="title">{{ $group['description'] }}</div>
                            </div>
                            <table class="endpoint-table">
                                <thead>
                                    <tr>
                                        <th style="width: 70px;">Method</th>
                                        <th style="width: 220px;">Endpoint</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($group['endpoints'] as $endpoint)
                                        @php
                                            $methodClass = strtolower($endpoint['method']);
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="badge-method {{ $methodClass }}">{{ $endpoint['method'] }}</span>
                                            </td>
                                            <td class="endpoint-path">{{ $endpoint['path'] }}</td>
                                            <td>{{ $endpoint['notes'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                @elseif ($section['key'] === 'query-params')
                    <p class="section-lead">
                        Query parameter dipakai untuk mengubah hasil list endpoint tanpa mengubah path utama.
                        Parameter ini diletakkan setelah tanda <code class="kbd">?</code> pada URL dan bisa
                        digabung sesuai kebutuhan endpoint.
                    </p>

                    <div class="kv-box">
                        <div class="kv-label">Contoh Gabungan</div>
                        <div class="kv-row">{{ $apiBaseUrl }}/ebook?page=1&amp;per_page=10&amp;search=trading&amp;category=edukasi</div>
                    </div>

                    <div class="card-grid" style="margin-top: 16px;">
                        <div class="card">
                            <div class="card-value">?page=1&amp;per_page=10</div>
                            <p class="card-desc"><strong>Pagination</strong> &mdash; halaman dan jumlah item per halaman. Dipakai saat endpoint menampilkan banyak data.</p>
                        </div>
                        <div class="card">
                            <div class="card-value">?search=keyword</div>
                            <p class="card-desc"><strong>Pencarian</strong> full-text pada list resource, umum dipakai untuk fitur search bar.</p>
                        </div>
                        <div class="card">
                            <div class="card-value">?category={slug}</div>
                            <p class="card-desc"><strong>Filter</strong> berdasarkan kategori. Gunakan slug kategori yang valid dari data master.</p>
                        </div>
                    </div>

                    <div class="panel" style="margin-bottom: 16px;">
                        <h4>Menggunakan Postman</h4>
                        <p style="margin: 0; font-size: 10.5px; line-height: 1.6; color: var(--body-text);">
                            Isi header <code class="kbd">{{ $apiKeyHeader }}</code> dengan API key aktif. Untuk web tambahkan
                            <code class="kbd">Origin: {{ $webOriginExample }}</code>. Untuk mobile app tambahkan
                            <code class="kbd">{{ $mobileClientHeader }}: {{ $mobileClientValue }}</code>.
                            Query params bisa diisi di tab <strong>Params</strong>.
                        </p>
                    </div>

                    <div class="card-grid cols-2">
                        <div class="panel">
                            <h4>Praktik yang Disarankan</h4>
                            <ul>
                                <li>Gunakan pagination default agar request tidak terlalu besar.</li>
                                <li>URL encode keyword jika ada spasi atau karakter khusus.</li>
                                <li>Gabungkan filter hanya bila endpoint mendukung kombinasi tersebut.</li>
                                <li>Simpan state query di URL frontend agar refresh tidak menghapus filter.</li>
                            </ul>
                        </div>
                        <div class="panel">
                            <h4>Hal yang Perlu Diperhatikan</h4>
                            <ul>
                                <li>Tidak semua endpoint memakai seluruh query parameter yang sama.</li>
                                <li>Endpoint detail umumnya tidak memerlukan query parameter list.</li>
                                <li>Kalau hasil kosong, cek ulang slug kategori atau keyword yang dikirim.</li>
                                <li>Pastikan parameter dikirim sebagai query string, bukan header request.</li>
                            </ul>
                        </div>
                    </div>
                @elseif ($section['key'] === 'examples')
                    <p class="section-lead">
                        Contoh pada bagian ini memakai <code class="kbd">cURL</code> agar bisa dites cepat dari
                        terminal, CMD, PowerShell, atau dijadikan referensi saat setup request di Postman maupun
                        code frontend.
                    </p>

                    @foreach ($requestExamples as $example)
                        <div class="code-card">
                            <div class="code-card-header">
                                <span class="label">{{ $example['label'] }}</span>
                                <span class="tag">cURL</span>
                            </div>
                            <pre class="code-block">{{ $example['command'] }}</pre>
                        </div>
                    @endforeach

                    <h3 class="block-title">Response Error yang Perlu Diketahui</h3>
                    <table class="status-table" style="margin-bottom: 16px;">
                        <tbody>
                            <tr>
                                <td class="status-error">403</td>
                                <td>API key tidak valid.</td>
                            </tr>
                            <tr>
                                <td class="status-error">403</td>
                                <td>Allowed Origin Frontend belum dikonfigurasi untuk web client.</td>
                            </tr>
                            <tr>
                                <td class="status-error">403</td>
                                <td>Header Origin wajib dikirim untuk web client.</td>
                            </tr>
                            <tr>
                                <td class="status-error">403</td>
                                <td>Origin frontend tidak diizinkan.</td>
                            </tr>
                            <tr>
                                <td class="status-warning">503</td>
                                <td>Public API sedang dinonaktifkan.</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="card-grid cols-2">
                        <div class="panel">
                            <h4>Cara Test dari Terminal</h4>
                            <ul>
                                <li>Salin salah satu command lalu jalankan apa adanya di terminal.</li>
                                <li>Ganti domain, endpoint, atau header bila environment yang dipakai berbeda.</li>
                                <li>Perhatikan response body dan status code untuk memastikan authentication sudah benar.</li>
                                <li>Kalau response gagal, cocokkan error dengan daftar status di atas.</li>
                            </ul>
                        </div>
                        <div class="panel">
                            <h4>Cara Ubah ke Postman</h4>
                            <ul>
                                <li>Ambil method dan URL dari command cURL.</li>
                                <li>Pindahkan setiap <code class="kbd">--header</code> ke tab Headers di Postman.</li>
                                <li>Untuk web, pastikan header <code class="kbd">Origin</code> ikut dimasukkan.</li>
                                <li>Untuk mobile app, pastikan header <code class="kbd">{{ $mobileClientHeader }}</code> ikut dimasukkan.</li>
                            </ul>
                        </div>
                    </div>
                @endif
            </section>
        @endforeach
    </div>

    <div class="doc-footer">
        <span><strong>SGB Admin</strong> &middot; Dokumentasi API Lengkap</span>
        <span>Dicetak {{ $printedAt }} &middot; Dokumen Internal</span>
    </div>

    <script>
        window.addEventListener('load', () => {
            window.setTimeout(() => {
                window.print();
            }, 250);
        });
    </script>
</body>

</html>
