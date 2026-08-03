<?php

namespace App\Models;

use Database\Factories\TradingviewSymbolFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'symbol_ws',
    'symbol_tv',
])]
class TradingviewSymbol extends Model
{
    /** @use HasFactory<TradingviewSymbolFactory> */
    use HasFactory;
}
