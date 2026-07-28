<?php

namespace App\Http\Controllers;

use App\Models\SystemActivityLog;
use App\Support\SystemActivitySubjectCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SystemLogController extends Controller
{
    public function show(Request $request, string $category): View
    {
        $allowedCategories = ['login', 'api', 'data'];
        $subjectMeta = [
            'api' => SystemActivitySubjectCatalog::api(),
            'data' => SystemActivitySubjectCatalog::data(),
        ];

        abort_unless(in_array($category, $allowedCategories, true), 404);

        $activeModule = in_array($category, ['api', 'data'], true)
            ? (string) $request->query('module', '')
            : '';

        $logsQuery = SystemActivityLog::query()
            ->with('user:id,name,email')
            ->where('category', $category)
            ->latest();

        if (
            in_array($category, ['api', 'data'], true)
            && $activeModule !== ''
            && array_key_exists($activeModule, $subjectMeta[$category])
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
                'description' => 'Aktivitas CRUD dan perubahan data dari semua menu backdoor admin.',
                'accent' => 'emerald',
            ],
        ];

        $counts = [
            'login' => SystemActivityLog::query()->where('category', 'login')->count(),
            'api' => SystemActivityLog::query()->where('category', 'api')->count(),
            'data' => SystemActivityLog::query()->where('category', 'data')->count(),
        ];

        $moduleCounts = in_array($category, ['api', 'data'], true)
            ? SystemActivityLog::query()
                ->where('category', $category)
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
            'categoryMeta' => $categoryMeta,
            'counts' => $counts,
            'logs' => $logs,
            'moduleCounts' => $moduleCounts,
            'moduleMeta' => $subjectMeta[$category] ?? [],
        ]);
    }
}
