<?php

namespace App\Http\Controllers;

use App\Models\Massage;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MassageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (! $this->hasMassageTable()) {
            $massages = new LengthAwarePaginator(
                items: [],
                total: 0,
                perPage: 12,
                currentPage: LengthAwarePaginator::resolveCurrentPage(),
                options: [
                    'path' => request()->url(),
                    'query' => request()->query(),
                ],
            );

            return view('massage.index', [
                'massages' => $massages,
            ]);
        }

        $massages = Massage::query()
            ->select($this->detailColumns())
            ->latest()
            ->paginate(12);

        return view('massage.index', [
            'massages' => $massages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $massage): View
    {
        abort_unless($this->hasMassageTable(), 404);

        $massage = Massage::query()
            ->select($this->detailColumns())
            ->findOrFail($massage);

        $replySubject = 'Re: '.$massage->subjek;
        $replyMessage = trim(implode("\n\n", [
            'Halo '.$massage->nama.',',
            'Menindaklanjuti pesan Anda dengan subjek "'.$massage->subjek.'".',
            'Silakan kabari kami jika masih ada yang ingin ditanyakan.',
            'Pesan awal:',
            $massage->massage,
        ]));
        $sanitizedPhone = preg_replace('/\D+/', '', $massage->no_tlp) ?? '';
        $telPhone = $sanitizedPhone;
        $whatsAppPhone = $this->normalizeWhatsAppPhone($sanitizedPhone);

        return view('massage.show', [
            'massage' => $massage,
            'mailToUrl' => 'mailto:'.$massage->email.'?subject='.rawurlencode($replySubject).'&body='.rawurlencode($replyMessage),
            'telUrl' => $telPhone !== '' ? 'tel:'.$telPhone : null,
            'whatsAppUrl' => $whatsAppPhone !== ''
                ? 'https://wa.me/'.$whatsAppPhone.'?text='.rawurlencode($replyMessage)
                : null,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Massage $massage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Massage $massage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Massage $massage)
    {
        //
    }

    private function hasMassageTable(): bool
    {
        return Schema::hasTable((new Massage())->getTable());
    }

    /**
     * @return array<int, string>
     */
    private function detailColumns(): array
    {
        return [
            'id',
            'id_laporan',
            'nama',
            'email',
            'no_tlp',
            'subjek',
            'massage',
            'created_at',
            'updated_at',
        ];
    }

    private function normalizeWhatsAppPhone(string $phone): string
    {
        if ($phone === '') {
            return '';
        }

        if (Str::startsWith($phone, '0')) {
            return '62'.substr($phone, 1);
        }

        if (Str::startsWith($phone, '8')) {
            return '62'.$phone;
        }

        return $phone;
    }
}
