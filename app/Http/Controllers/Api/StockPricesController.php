<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StockPricesController extends Controller
{
    public function latest()
    {
        // Fetch the latest stock prices for each symbol
        $stockPrices = Cache::remember('stock_prices', 1, function () {
            return DB::table('stock_prices as sp1')
                ->select('sp1.*')
                ->join(DB::raw('(SELECT symbol, MAX(date) as max_date FROM stock_prices GROUP BY symbol) sp2'), function ($join) {
                    $join->on('sp1.symbol', '=', 'sp2.symbol');
                    $join->on('sp1.date', '=', 'sp2.max_date');
                })
                ->get();
        });

        return response()->json($stockPrices);
    }
}
