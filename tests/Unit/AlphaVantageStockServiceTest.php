<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Services\AlphaVantageStockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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

    public function testValidatePriceData()
    {
        // Create a valid price data array
        $valid_price_data = [
            'Global Quote' => [
                '01. symbol' => 'AAPL',
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

        // Create an instance of AlphaVantageStockService
        $service = new AlphaVantageStockService();

        // Validate the valid price data
        $this->assertTrue($service->validatePriceData($valid_price_data));

        // Create an invalid price data array
        $invalid_price_data = [
            'Global Quote' => [
                '01. symbol' => 'AAPL',
                '02. open' => '100.00',
                '03. high' => '100.00',
                '04. low' => '100.00',
                '05. price' => '100.00',
                '06. volume' => '100',
                '08. previous close' => '100.00',
                '09. change' => '0.00',
                // Missing '10. change percent'
            ]
        ];

        // Validate the invalid price data
        $this->assertFalse($service->validatePriceData($invalid_price_data));
    }

    public function testGetValue()
    {
        // Create an instance of AlphaVantageStockService
        $service = new AlphaVantageStockService();

        // Test the getValue method with a percentage value
        $this->assertEquals('0.00', $service->getValue('0.00%'));

        // Test the getValue method with a non-percentage value
        $this->assertEquals('100.00', $service->getValue('100.00'));
    }

    public function testFetchStockDataForCompanyWithInvalidPriceData()
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
                // Missing '10. change percent'
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
        if($service->validatePriceData($price_data)) {
            $service->storeStockData($price_data, $company);
        }

        // Check if the stock price was not stored in the database
        $this->assertDatabaseMissing('stock_prices', [
            'company_id' => $company->id,
            'symbol' => $company->symbol,
            'price' => '100.00',
        ]);
    }

    public function testFetchStockDataForCompanyWithException()
    {
        // Create a company
        $company = Company::factory()->create();

        // Mock the Http facade to throw an exception
        Http::fake([
            'https://www.alphavantage.co/query' => function () {
                throw new \Exception('Error fetching stock data');
            },
        ]);

        // Create an instance of AlphaVantageStockService with the mock Http
        $service = new AlphaVantageStockService();

        // Call the fetchStockDataForCompany method
        $service->fetchStockDataForCompany($company);

        // Check if the stock price was not stored in the database
        $this->assertDatabaseMissing('stock_prices', [
            'company_id' => $company->id,
            'symbol' => $company->symbol,
        ]);
    }

    public function testFetchStockDataForCompanyWithUnsuccessfulResponse()
    {
        // Create a company
        $company = Company::factory()->create();

        // Mock the Http facade to return an unsuccessful response
        Http::fake([
            'https://www.alphavantage.co/query' => Http::response([], 500),
        ]);

        // Create an instance of AlphaVantageStockService with the mock Http
        $service = new AlphaVantageStockService();

        // Call the fetchStockDataForCompany method
        $service->fetchStockDataForCompany($company);

        // Check if the stock price was not stored in the database
        $this->assertDatabaseMissing('stock_prices', [
            'company_id' => $company->id,
            'symbol' => $company->symbol,
        ]);
    }
}
