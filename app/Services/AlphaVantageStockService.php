<?php

namespace App\Services;

use App\Contracts\StockServiceInterface;
use App\Models\Company;
use App\Models\StockPrice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AlphaVantageStockService implements StockServiceInterface
{
    public function fetchStockData(): void
    {
        $cache_key = 'companies';
        $companies = Cache::remember($cache_key, 1440, function () {
            return Company::all();
        });

        foreach ($companies as $company) {
            $this->fetchStockDataForCompany($company);
        }
    }

    public function fetchStockDataForCompany(Company $company): void
    {
        $cache_key = 'stock_data_'.$company->symbol;

        if (Cache::has($cache_key)) {
            return;
        }

        // Fetch stock data for the given company from the Alpha Vantage API
        try {
            $response = Http::get('https://www.alphavantage.co/query', [
                'function' => 'GLOBAL_QUOTE',
                'symbol' => $company->symbol,
                'apikey' => config('services.alpha_vantage.api_key'),
            ]);

            if ($response->successful()) {
                $prices_data = $response->json();
            } else {
                // Log the error if the API request was not successful
                $this->logError('Error fetching stock data for company: '.$company->symbol.'. Error: '.$response->body());

                return;
            }
        } catch (\Exception $e) {
            // Log the exception if an error occurred during the API request
            $this->logError('Error fetching stock data for company: '.$company->symbol.'. Exception: '.$e->getMessage());

            return;
        }
        // Validate the price data
        if ($this->validatePriceData($prices_data)) {
            // Store the stock data in the database
            $this->storeStockData($prices_data, $company);
            // Cache the stock data for 1 minute
            Cache::put($cache_key, $prices_data, 1);
        } else {
            // Log an error if the price data is invalid
            $this->logError('Invalid price data for company: '.$company->symbol);
        }
    }

    public function validatePriceData($prices_data): bool
    {
        // Return true if the price data is valid, false otherwise
        // For example, you can check if all required fields are present in the prices_data array
        return isset($prices_data['Global Quote']['01. symbol']) &&
               isset($prices_data['Global Quote']['02. open']) &&
               isset($prices_data['Global Quote']['03. high']) &&
               isset($prices_data['Global Quote']['04. low']) &&
               isset($prices_data['Global Quote']['05. price']) &&
               isset($prices_data['Global Quote']['06. volume']) &&
               isset($prices_data['Global Quote']['08. previous close']) &&
               isset($prices_data['Global Quote']['09. change']) &&
               isset($prices_data['Global Quote']['10. change percent']);
    }

    public function storeStockData(array $prices_data, Company $company): void
    {
        // Store the stock data in the database
        StockPrice::create([
            'company_id' => $company->id,
            'symbol' => $prices_data['Global Quote']['01. symbol'],
            'open' => $prices_data['Global Quote']['02. open'],
            'high' => $prices_data['Global Quote']['03. high'],
            'low' => $prices_data['Global Quote']['04. low'],
            'price' => $prices_data['Global Quote']['05. price'],
            'volume' => $prices_data['Global Quote']['06. volume'],
            'previous_close' => $prices_data['Global Quote']['08. previous close'],
            'change' => $prices_data['Global Quote']['09. change'],
            'change_percent' => $this->getValue($prices_data['Global Quote']['10. change percent']),
            'date' => Carbon::now(),
            'timestamp' => Carbon::now(),
        ]);
    }

    public function logError(string $message): void
    {
        // Log the error message
        logger()->error($message);
    }

    public function getValue($value)
    {
        return str_replace('%', '', $value);
    }
}
