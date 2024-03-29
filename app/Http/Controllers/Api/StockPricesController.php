<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StockPrice;
use Illuminate\Support\Facades\Cache;

class StockPricesController extends Controller
{
    public function latest()
    {
        // Fetch the latest stock prices
        $stockPrices = Cache::remember('stock_prices', 1, function () {
            return StockPrice::latest()->get();
        });

        return response()->json($stockPrices);
    }
}
