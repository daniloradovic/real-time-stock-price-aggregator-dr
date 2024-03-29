<?php

namespace App\Contracts;

use App\Models\Company;

interface StockServiceInterface
{
    public function fetchStockData(): void;

    public function fetchStockDataForCompany(Company $company): void;

    public function validatePriceData(array $prices_data): bool;

    public function storeStockData(array $prices_data, Company $company): void;

    public function logError(string $message): void;
}
