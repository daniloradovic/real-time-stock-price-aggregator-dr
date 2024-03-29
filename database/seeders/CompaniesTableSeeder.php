<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            ['name' => 'Apple Inc.', 'symbol' => 'AAPL'],
            ['name' => 'Microsoft Corporation', 'symbol' => 'MSFT'],
            ['name' => 'Amazon.com Inc.', 'symbol' => 'AMZN'],
            ['name' => 'Alphabet Inc.', 'symbol' => 'GOOGL'],
            ['name' => 'Meta Platforms Inc.', 'symbol' => 'META'],
            ['name' => 'Tesla Inc.', 'symbol' => 'TSLA'],
            ['name' => 'NVIDIA Corporation', 'symbol' => 'NVDA'],
            ['name' => 'PayPal Holdings Inc.', 'symbol' => 'PYPL'],
            ['name' => 'Adobe Inc.', 'symbol' => 'ADBE'],
            ['name' => 'Netflix Inc.', 'symbol' => 'NFLX'],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
