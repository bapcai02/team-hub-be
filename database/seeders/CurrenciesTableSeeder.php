<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrenciesTableSeeder extends Seeder
{
    public function run()
    {
        $currencies = [
            ['code' => 'VND', 'name' => 'Việt Nam Đồng', 'symbol' => '₫'],
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$'],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            ['code' => 'JPY', 'name' => 'Yen', 'symbol' => '¥'],
            ['code' => 'GBP', 'name' => 'Bảng Anh', 'symbol' => '£'],
        ];
        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
    }
} 