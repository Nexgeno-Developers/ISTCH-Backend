<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    public const SUPPORTED_CODES = ['USD', 'INR', 'EUR', 'GBP', 'AED'];

    public function activeCurrencies()
    {
        $sortOrder = array_flip(self::SUPPORTED_CODES);

        return Currency::query()
            ->where('is_active', true)
            ->whereIn('code', self::SUPPORTED_CODES)
            ->get()
            ->sortBy(fn (Currency $currency) => $sortOrder[$currency->code] ?? 999)
            ->values();
    }

    public function calculateUsdAmount(float $amount, Currency $currency): float
    {
        $rate = (float) $currency->exchange_rate;
        if ($rate <= 0) {
            $rate = 1;
        }

        return round($amount / $rate, 2);
    }

    public function updateExchangeRates(): bool
    {
        $accessKey = config('services.exchangerate_host.access_key');
        if (blank($accessKey)) {
            Log::warning('Exchange rate update skipped: EXCHANGERATE_HOST_ACCESS_KEY is missing.');
            return false;
        }

        try {
            $response = Http::timeout(15)->get('https://api.exchangerate.host/live', [
                'access_key' => $accessKey,
                'currencies' => 'INR,EUR,GBP,AED',
            ]);

            if (!$response->successful()) {
                Log::error('Exchange rate update failed.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return false;
            }

            $payload = $response->json();
            if (!($payload['success'] ?? false) || empty($payload['quotes']) || !is_array($payload['quotes'])) {
                Log::error('Exchange rate API returned an invalid payload.', ['payload' => $payload]);
                return false;
            }

            Currency::where('code', 'USD')->update([
                'exchange_rate' => 1,
                'last_updated_at' => now(),
            ]);

            foreach (['INR', 'EUR', 'GBP', 'AED'] as $code) {
                $rate = $payload['quotes']['USD' . $code] ?? null;
                if (!is_numeric($rate) || (float) $rate <= 0) {
                    Log::warning('Exchange rate missing or invalid for currency.', ['currency' => $code, 'rate' => $rate]);
                    continue;
                }

                Currency::where('code', $code)->update([
                    'exchange_rate' => (float) $rate,
                    'last_updated_at' => now(),
                ]);
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Exchange rate update exception: ' . $e->getMessage(), ['exception' => $e]);
            return false;
        }
    }
}
