<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Support\ApiJsonCacheService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * @var list<array<string, mixed>>
     */
    private array $dummyTerms = [
        '<ul><li>Program berlaku untuk pengguna yang telah menyelesaikan verifikasi akun.</li><li>Benefit promo hanya dapat digunakan selama periode campaign berlangsung.</li><li>Keputusan perusahaan bersifat final dan mengikat.</li></ul>',
        '<ol><li>Minimum transaksi mengikuti ketentuan produk yang dipilih.</li><li>Bonus promo tidak dapat diuangkan.</li><li>Satu user hanya dapat mengikuti promo satu kali selama periode berjalan.</li></ol>',
        '<p>Layanan tersedia untuk pengguna yang memenuhi kriteria internal perusahaan.</p><p>Dengan mengikuti program ini, pengguna dianggap telah membaca dan menyetujui seluruh syarat dan ketentuan yang berlaku.</p>',
    ];

    public function run(): void
    {
        Banner::query()
            ->where(function ($query) {
                $query
                    ->whereNull('terms_and_conditions')
                    ->orWhere('terms_and_conditions', '');
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->values()
            ->each(function (Banner $banner, int $index): void {
                $banner->update([
                    'terms_and_conditions' => $this->dummyTerms[$index % count($this->dummyTerms)],
                ]);
            });

        app(ApiJsonCacheService::class)->refreshBanner();
    }
}
