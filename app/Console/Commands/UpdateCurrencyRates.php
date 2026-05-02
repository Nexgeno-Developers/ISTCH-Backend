<?php

namespace App\Console\Commands;

use App\Services\CurrencyService;
use Illuminate\Console\Command;

class UpdateCurrencyRates extends Command
{
    protected $signature = 'update:currency-rates';

    protected $description = 'Update active currency exchange rates relative to USD.';

    public function handle(CurrencyService $currencyService): int
    {
        if (!$currencyService->updateExchangeRates()) {
            $this->error('Currency exchange rates were not updated. Check the Laravel log for details.');
            return self::FAILURE;
        }

        $this->info('Currency exchange rates updated successfully.');
        return self::SUCCESS;
    }
}
