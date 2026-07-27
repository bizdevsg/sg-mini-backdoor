<?php

namespace App\Http\Controllers;

use App\Models\SystemActivityLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SystemLogController extends Controller
{
    public function show(Request $request, string $category): View
    {
        $allowedCategories = ['login', 'api', 'data'];
        $apiModuleMeta = [
            'client-area' => 'Client Area',
            'banner' => 'Banner',
            'produk' => 'Produk',
            'pengumuman' => 'Pengumuman',
            'ebook' => 'Ebook',
            'signal' => 'Signal',
            'berita' => 'Berita',
            'penghargaan' => 'Penghargaan',
            'legalitas' => 'Legalitas',
            'company-profile' => 'Profil Perusahaan',
            'terms-and-conditions' => 'Syarat dan Ketentuan',
            'privacy-policy' => 'Kebijakan Privasi',
            'massages' => 'Massages',
            'unknown' => 'Unknown',
        ];

        abort_unless(in_array($category, $allowedCategories, true), 404);

        $activeModule = $category === 'api'
            ? (string) $request->query('module', '')
            : '';

        $logsQuery = SystemActivityLog::query()
            ->with('user:id,name,email')
            ->where('category', $category)
            ->latest();

        if (
            $category === 'api'
            && $activeModule !== ''
            && array_key_exists($activeModule, $apiModuleMeta)
        ) {
            $logsQuery->where('subject', $activeModule);
        }

        $logs = $logsQuery->paginate(20)->withQueryString();

        $categoryMeta = [
            'login' => [
                'label' => 'Login',
                'icon' => 'fa-right-to-bracket',
                'description' => 'Aktivitas masuk dan keluar admin panel.',
                'accent' => 'blue',
            ],
            'api' => [
                'label' => 'API',
                'icon' => 'fa-plug',
                'description' => 'Akses semua endpoint /api/v1 dengan pengkategorian per modul API.',
                'accent' => 'gold',
            ],
            'data' => [
                'label' => 'Data',
                'icon' => 'fa-database',
                'description' => 'Perubahan status toggle client area dan setting terkait.',
                'accent' => 'emerald',
            ],
        ];

        $counts = [
            'login' => SystemActivityLog::query()->where('category', 'login')->count(),
            'api' => SystemActivityLog::query()->where('category', 'api')->count(),
            'data' => SystemActivityLog::query()->where('category', 'data')->count(),
        ];

        $apiModuleCounts = $category === 'api'
            ? SystemActivityLog::query()
                ->where('category', 'api')
                ->whereNotNull('subject')
                ->selectRaw('subject, COUNT(*) as aggregate')
                ->groupBy('subject')
                ->pluck('aggregate', 'subject')
                ->all()
            : [];

        return view('system-logs.show', [
            'activeCategory' => $category,
            'activeModule' => $activeModule,
            'activeMeta' => $categoryMeta[$category],
            'apiModuleCounts' => $apiModuleCounts,
            'apiModuleMeta' => $apiModuleMeta,
            'categoryMeta' => $categoryMeta,
            'counts' => $counts,
            'logs' => $logs,
        ]);
    }
}
