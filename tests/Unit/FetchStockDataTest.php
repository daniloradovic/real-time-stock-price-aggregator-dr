<?php

namespace Tests\Unit;

use App\Console\Commands\FetchStockData;
use App\Contracts\StockServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchStockDataTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    
    public function testHandle()
    {
        // Create a mock for StockServiceInterface
        $mockStockService = $this->createMock(StockServiceInterface::class);

        // Set up the expectation for the fetchStockData method
        // to be called only once and with no parameters
        $mockStockService->expects($this->once())
            ->method('fetchStockData');

        // Create an instance of FetchStockData with the mock StockServiceInterface
        $command = new FetchStockData($mockStockService);

        // Call the handle method
        $command->handle();
    }
}
