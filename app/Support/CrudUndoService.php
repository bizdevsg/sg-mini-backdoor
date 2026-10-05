<?php

namespace App\Support;

use App\Models\Banner;
use App\Models\Berita;
use App\Models\BeritaCategory;
use App\Models\CompanyProfile;
use App\Models\CrudUndoAction;
use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Models\Informasi;
use App\Models\Legalitas;
use App\Models\Penghargaan;
use App\Models\PrivacyPolicy;
use App\Models\Produk;
use App\Models\Signal;
use App\Models\SignalCategory;
use App\Models\TermsAndCondition;
use App\Models\TradingviewSymbol;
use App\Models\WakilPialang;
use App\Models\WakilPialangCategory;
use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CrudUndoService
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function undo(string $token, int $userId): string
    {
        $undoAction = DB::transaction(function () use ($token, $userId): CrudUndoAction {
            $undoAction = CrudUndoAction::query()
                ->where('token_hash', hash('sha256', $token))
                ->lockForUpdate()
                ->first();

            if (! $undoAction || (int) $undoAction->user_id !== $userId) {
                throw new DomainException('Aksi Undo tidak ditemukan.');
            }

            if ($undoAction->used_at !== null) {
                throw new DomainException('Aksi ini sudah pernah dibatalkan.');
            }

            if ($undoAction->expires_at->isPast()) {
                throw new DomainException('Waktu Undo sudah berakhir.');
            }

            $this->restoreDatabase($undoAction);
            $undoAction->forceFill(['used_at' => now()])->save();

            return $undoAction;
        });

        $this->restoreFiles($undoAction);
        $this->refreshApiCache($undoAction->model_type);

        return $undoAction->redirect_url;
    }

    public function pruneExpired(): int
    {
        $deleted = 0;

        CrudUndoAction::query()
            ->where('retention_until', '<=', now())
            ->chunkById(100, function ($actions) use (&$deleted): void {
                foreach ($actions as $undoAction) {
                    $this->deleteBackups($undoAction->files ?? []);
                    $undoAction->delete();
                    $deleted++;
                }
            });

        return $deleted;
    }

    private function restoreDatabase(CrudUndoAction $undoAction): void
    {
        $modelClass = $undoAction->model_type;

        if (! array_key_exists($modelClass, config('crud-undo.models', []))) {
            throw new DomainException('Jenis data Undo tidak didukung.');
        }

        /** @var Model $model */
        $model = new $modelClass();
        $keyName = $model->getKeyName();
        $query = DB::table($model->getTable())->where($keyName, $undoAction->model_key);
        $current = $query->first();
        $currentSnapshot = $current === null ? null : (array) $current;

        if (! $this->snapshotsMatch($currentSnapshot, $undoAction->after_snapshot)) {
            throw new DomainException('Data sudah berubah lagi sehingga tidak aman untuk di-Undo.');
        }

        if ($undoAction->before_snapshot === null) {
            $query->delete();

            return;
        }

        if ($current === null) {
            DB::table($model->getTable())->insert($undoAction->before_snapshot);

            return;
        }

        $query->update($undoAction->before_snapshot);
    }

    private function restoreFiles(CrudUndoAction $undoAction): void
    {
        foreach ($undoAction->files ?? [] as $file) {
            $field = $file['field'] ?? null;
            $originalPath = $file['original_path'] ?? null;
            $backupPath = $file['backup_path'] ?? null;
            $replacementPath = is_string($field) ? data_get($undoAction->after_snapshot, $field) : null;

            if (is_string($replacementPath) && $replacementPath !== $originalPath && ! Str::startsWith($replacementPath, ['http://', 'https://', '/'])) {
                Storage::disk('public')->delete($replacementPath);
            }

            if (! is_string($originalPath) || ! is_string($backupPath) || ! Storage::disk('local')->exists($backupPath)) {
                continue;
            }

            Storage::disk('public')->put($originalPath, Storage::disk('local')->get($backupPath));
            Storage::disk('local')->delete($backupPath);
        }
    }

    /** @param array<int, array<string, string>> $files */
    private function deleteBackups(array $files): void
    {
        Storage::disk('local')->delete(array_values(array_filter(array_column($files, 'backup_path'))));
    }

    /**
     * @param  array<string, mixed>|null  $current
     * @param  array<string, mixed>|null  $expected
     */
    private function snapshotsMatch(?array $current, ?array $expected): bool
    {
        if ($current === null || $expected === null) {
            return $current === $expected;
        }

        ksort($current);
        ksort($expected);

        return array_map('strval', $current) === array_map('strval', $expected);
    }

    private function refreshApiCache(string $modelType): void
    {
        match ($modelType) {
            Banner::class => $this->apiJsonCacheService->refreshBanner(),
            Produk::class => $this->apiJsonCacheService->refreshProduk(),
            Informasi::class => $this->apiJsonCacheService->refreshPengumuman(),
            Ebook::class, EbookCategory::class => $this->refreshEbookCache(),
            Signal::class, SignalCategory::class => $this->refreshSignalCache(),
            Berita::class, BeritaCategory::class => $this->refreshBeritaCache(),
            WakilPialang::class, WakilPialangCategory::class => $this->refreshWakilPialangCache(),
            Penghargaan::class => $this->apiJsonCacheService->refreshPenghargaan(),
            Legalitas::class => $this->apiJsonCacheService->refreshLegalitas(),
            TradingviewSymbol::class => $this->apiJsonCacheService->refreshTradingviewSymbol(),
            CompanyProfile::class => $this->apiJsonCacheService->refreshCompanyProfile(),
            TermsAndCondition::class => $this->apiJsonCacheService->refreshTermsAndConditions(),
            PrivacyPolicy::class => $this->apiJsonCacheService->refreshPrivacyPolicy(),
            default => null,
        };
    }

    private function refreshEbookCache(): void
    {
        $this->apiJsonCacheService->refreshEbook();
        $this->apiJsonCacheService->refreshEbookCategories();
    }

    private function refreshSignalCache(): void
    {
        $this->apiJsonCacheService->refreshSignal();
        $this->apiJsonCacheService->refreshSignalCategories();
    }

    private function refreshBeritaCache(): void
    {
        $this->apiJsonCacheService->refreshBerita();
        $this->apiJsonCacheService->refreshBeritaCategories();
    }

    private function refreshWakilPialangCache(): void
    {
        $this->apiJsonCacheService->refreshWakilPialang();
        $this->apiJsonCacheService->refreshWakilPialangCategories();
    }
}
