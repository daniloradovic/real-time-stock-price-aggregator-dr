<?php

namespace App\Console\Commands;

use App\Contracts\StockServiceInterface;
use Illuminate\Console\Command;

class FetchStockData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-stock-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch stock data from the API and store it in the database.';

    public function __construct(private StockServiceInterface $stockService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Fetch stock data from the API
        $this->stockService->fetchStockData();
    }
}
