<?php

namespace App\Http\Controllers;

use App\Http\Requests\TradingviewSymbol\StoreTradingviewSymbolRequest;
use App\Http\Requests\TradingviewSymbol\UpdateTradingviewSymbolRequest;
use App\Models\TradingviewSymbol;
use App\Support\ApiJsonCacheService;
use App\Support\DuplicateEntryGuard;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TradingviewSymbolController extends Controller
{
    public function __construct(
        private readonly ApiJsonCacheService $apiJsonCacheService,
    ) {
    }

    public function index(Request $request): View
    {
        $tradingviewSymbols = TradingviewSymbol::query()
            ->when(
                $request->string('search')->isNotEmpty(),
                fn ($query) => $query->where(function ($builder) use ($request) {
                    $search = $request->string('search')->toString();

                    $builder
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('symbol_ws', 'like', "%{$search}%")
                        ->orWhere('symbol_tv', 'like', "%{$search}%");
                })
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('tradingview-symbol.index', [
            'tradingviewSymbols' => $tradingviewSymbols,
        ]);
    }

    public function create(): View
    {
        return view('tradingview-symbol.create');
    }

    public function store(StoreTradingviewSymbolRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            TradingviewSymbol::create($validated);
        } catch (QueryException $exception) {
            throw DuplicateEntryGuard::translate(
                $exception,
                'symbol_ws',
                'symbol_ws ini baru saja dipakai oleh kode lain. Silakan gunakan kode yang berbeda.',
            );
        }

        $this->apiJsonCacheService->refreshTradingviewSymbol();

        return redirect()
            ->route('tradingview.index')
            ->with('status', 'Kode TradingView berhasil ditambahkan.');
    }

    public function edit(TradingviewSymbol $tradingviewSymbol): View
    {
        return view('tradingview-symbol.edit', [
            'tradingviewSymbol' => $tradingviewSymbol,
        ]);
    }

    public function update(UpdateTradingviewSymbolRequest $request, TradingviewSymbol $tradingviewSymbol): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $tradingviewSymbol->update($validated);
        } catch (QueryException $exception) {
            throw DuplicateEntryGuard::translate(
                $exception,
                'symbol_ws',
                'symbol_ws ini baru saja dipakai oleh kode lain. Silakan gunakan kode yang berbeda.',
            );
        }

        $this->apiJsonCacheService->refreshTradingviewSymbol();

        return redirect()
            ->route('tradingview.index')
            ->with('status', 'Kode TradingView berhasil diperbarui.');
    }

    public function destroy(TradingviewSymbol $tradingviewSymbol): RedirectResponse
    {
        $tradingviewSymbol->delete();
        $this->apiJsonCacheService->refreshTradingviewSymbol();

        return redirect()
            ->route('tradingview.index')
            ->with('status', 'Kode TradingView berhasil dihapus.');
    }
}
