<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumentasi API Lengkap</title>
    <style>
        @page {
            size: A4;
            margin: 18mm 16mm;
        }

        body {
            margin: 0;
            color: #111827;
            background: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }

        .page {
            max-width: 900px;
            margin: 0 auto;
            padding: 24px;
        }

        .toolbar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .print-button {
            border: 1px solid #d1d5db;
            background: #f9fafb;
            color: #111827;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 12px;
            cursor: pointer;
        }

        .document-header {
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }

        .document-header h1 {
            margin: 0 0 8px;
            font-size: 28px;
            line-height: 1.2;
        }

        .document-header p {
            margin: 0;
        }

        .meta {
            margin-top: 16px;
        }

        .meta p {
            margin: 4px 0;
        }

        .section {
            margin-top: 28px;
            page-break-inside: avoid;
        }

        .section h2 {
            margin: 0 0 12px;
            font-size: 20px;
            line-height: 1.3;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
        }

        .section h3,
        .section h4,
        .section h5,
        .section h6 {
            margin-top: 18px;
            margin-bottom: 8px;
            font-size: 14px;
            line-height: 1.4;
        }

        .section p,
        .section li,
        .section td,
        .section th,
        .section span,
        .section div {
            color: #111827 !important;
        }

        .section p {
            margin: 8px 0;
        }

        .section ul,
        .section ol {
            margin: 8px 0 8px 20px;
            padding: 0;
        }

        .section table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        .section th,
        .section td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        .section pre {
            white-space: pre-wrap;
            word-break: break-word;
            overflow-wrap: anywhere;
            margin: 12px 0;
            padding: 12px;
            border: 1px solid #d1d5db;
            background: #f9fafb;
            border-radius: 6px;
            font-family: "Courier New", Courier, monospace;
            font-size: 11px;
            line-height: 1.6;
        }

        .section code {
            font-family: "Courier New", Courier, monospace;
            font-size: 11px;
        }

        .section [data-copy-button] {
            display: none !important;
        }

        .section .rounded-2xl,
        .section .rounded-3xl,
        .section .overflow-hidden,
        .section .border,
        .section .bg-white\/3,
        .section .bg-white\/5,
        .section .bg-black\/20,
        .section .bg-black\/40,
        .section .bg-\[\#120f0c\],
        .section .bg-\[\#1b1611\] {
            background: transparent !important;
            border: 0 !important;
            box-shadow: none !important;
        }

        .section .grid,
        .section .space-y-4,
        .section .space-y-3,
        .section .space-y-2,
        .section .space-y-1 {
            display: block !important;
        }

        .section .flex {
            display: block !important;
        }

        .section .h-px,
        .section .h-7,
        .section .h-8,
        .section .w-7,
        .section .w-8,
        .section .h-1\.5,
        .section .w-1\.5 {
            display: none !important;
        }

        .section .text-\[10px\],
        .section .text-\[11px\],
        .section .text-xs,
        .section .text-sm,
        .section .text-base {
            font-size: 12px !important;
        }

        .section .text-2xl {
            font-size: 20px !important;
        }

        .section .font-mono {
            font-family: "Courier New", Courier, monospace !important;
        }

        @media print {
            .toolbar {
                display: none !important;
            }

            .page {
                max-width: none;
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    @php
        $headerExample = $apiKeyHeader . ': ' . (filled($apiKeyValue) ? $apiKeyValue : 'isi-api-key-di-env');
    @endphp

    <div class="page">
        <div class="toolbar">
            <button type="button" onclick="window.print()" class="print-button">Download PDF</button>
        </div>

        <div class="document-header">
            <h1>Dokumentasi API Lengkap</h1>
            <p>Dokumen ini berisi seluruh penjelasan dokumentasi API untuk integrasi website dan mobile app.</p>

            <div class="meta">
                <p><strong>Base URL:</strong> {{ $apiBaseUrl }}</p>
                <p><strong>Header Auth:</strong> {{ $headerExample }}</p>
                <p><strong>Client Mobile:</strong> {{ $mobileClientHeader }}: {{ $mobileClientValue }}</p>
            </div>
        </div>

        @foreach ($docSections as $section)
            <section class="section">
                <h2>{{ $section['label'] }}</h2>

                @include($section['partial'], [
                    'apiBaseUrl' => $apiBaseUrl,
                    'apiKeyHeader' => $apiKeyHeader,
                    'apiKeyValue' => $apiKeyValue,
                    'headerExample' => $headerExample,
                    'mobileClientHeader' => $mobileClientHeader,
                    'mobileClientValue' => $mobileClientValue,
                    'webOriginExample' => $webOriginExample,
                    'endpointGroups' => $endpointGroups,
                    'requestExamples' => $requestExamples,
                ])
            </section>
        @endforeach
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
