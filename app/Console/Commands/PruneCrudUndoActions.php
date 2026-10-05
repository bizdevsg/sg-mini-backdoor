<?php

namespace App\Console\Commands;

use App\Support\CrudUndoService;
use Illuminate\Console\Command;

class PruneCrudUndoActions extends Command
{
    protected $signature = 'crud-undo:prune';

    protected $description = 'Hapus snapshot Undo dan backup file yang telah melewati retensi 24 jam';

    public function handle(CrudUndoService $crudUndoService): int
    {
        $deleted = $crudUndoService->pruneExpired();

        $this->info("{$deleted} snapshot Undo kedaluwarsa dihapus permanen.");

        return self::SUCCESS;
    }
}
