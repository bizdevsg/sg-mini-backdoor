<?php

namespace Database\Seeders;

use App\Models\WakilPialang;
use App\Models\WakilPialangCategory;
use App\Support\ApiJsonCacheService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WakilPialangSeeder extends Seeder
{
    use WithoutModelEvents;

    private const CATEGORY_NAME = 'Kantor Pusat Jakarta';

    /**
     * @var list<array{nama: string, no_identitas: string, status: string}>
     */
    private array $wakilPialangs = [
        ['nama' => 'IRIAWAN WIDADI, IR', 'no_identitas' => '542/BAPPEBTI/SI/XI/2004', 'status' => 'aktif'],
        ['nama' => 'JONATHAN LOK', 'no_identitas' => '0097/UPTP/SI/7/2017', 'status' => 'aktif'],
        ['nama' => 'INDAH KUSUMANINGRUM', 'no_identitas' => '222/UPTP/SI/11/2019', 'status' => 'aktif'],
        ['nama' => 'SRI HARTATI HIDAWATI', 'no_identitas' => '230/uptp/si/11/2019', 'status' => 'tidak_aktif'],
        ['nama' => 'ANIE SAFRIA IRFANI', 'no_identitas' => '0054/UPTP/SI/04/2021', 'status' => 'aktif'],
        ['nama' => 'TULUS RIFWANDI GEA', 'no_identitas' => '0653/UPTP/SI/8/2023', 'status' => 'aktif'],
        ['nama' => 'A. Andriawan', 'no_identitas' => '0056/UPTP/SI/3/2019', 'status' => 'tidak_aktif'],
        ['nama' => 'Bayu Setyawan', 'no_identitas' => '081/UPTP/SI/6/2026', 'status' => 'aktif'],
    ];

    public function run(): void
    {
        $category = WakilPialangCategory::query()->where('nama_kategori', self::CATEGORY_NAME)->first();

        if (! $category) {
            $existing = WakilPialangCategory::query()->pluck('nama_kategori')->implode(', ');

            $this->command?->error('Kategori "'.self::CATEGORY_NAME.'" tidak ditemukan. Kategori yang ada: '.($existing ?: '(kosong)').'.');

            return;
        }

        $this->command?->info('Memakai kategori: '.$category->nama_kategori);

        foreach ($this->wakilPialangs as $item) {
            $wakilPialang = WakilPialang::query()->firstOrNew(['no_identitas' => $item['no_identitas']]);

            if (! $wakilPialang->exists) {
                $wakilPialang->slug = WakilPialang::generateSlug($item['nama']);
            }

            $wakilPialang->fill([
                'nama' => $item['nama'],
                'id_kategori' => $category->getKey(),
                'status' => $item['status'],
            ])->save();
        }

        $cache = app(ApiJsonCacheService::class);
        $cache->refreshWakilPialangCategories();
        $cache->refreshWakilPialang();
    }
}
