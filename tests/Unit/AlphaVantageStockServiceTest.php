<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Services\AlphaVantageStockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AlphaVantageStockServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testFetchStockDataForCompany()
    {
        // Create a company
        $company = Company::factory()->create();
        $price_data = [
            'Global Quote' => [
                '01. symbol' => $company->symbol,
                '02. open' => '100.00',
                '03. high' => '100.00',
                '04. low' => '100.00',
                '05. price' => '100.00',
                '06. volume' => '100',
                '08. previous close' => '100.00',
                '09. change' => '0.00',
                '10. change percent' => '0.00%',
            ]
        ];
        // Mock the Http facade
        Http::fake([
            'https://www.alphavantage.co/query' => Http::response($price_data, 200, [
                'Content-Type' => 'application/json'
            ]),
        ]);

        // Create an instance of AlphaVantageStockService with the mock Http
        $service = new AlphaVantageStockService();

        // Call the fetchStockDataForCompany method
        $service->storeStockData($price_data, $company);

        // Check if the stock price was stored in the database
        $this->assertDatabaseHas('stock_prices', [
            'company_id' => $company->id,
            'symbol' => $company->symbol,
            'price' => 100.00,
        ]);
    }
}
