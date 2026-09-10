<?php

namespace Tests\Feature;

use App\Models\SeoSetting;
use App\Services\ApiPayloadCache;
use Tests\StripeDonationTestCase;

class RedirectApiTest extends StripeDonationTestCase
{
    public function test_it_returns_only_enabled_rules_and_refreshes_after_invalidation(): void
    {
        config(['custom.company_id' => 1]);
        $setting = SeoSetting::create([
            'company_id' => 1,
            'redirects' => [
                ['id' => 'c4d4e6ee-9bdc-4a79-9bd4-7b959a1c179d', 'from' => '/old', 'to' => '/new', 'status' => 301, 'enabled' => true, 'note' => null],
                ['id' => '1d31cc27-e761-49b6-bcb5-4a043b8d0d7e', 'from' => '/hidden', 'to' => '/new-hidden', 'status' => 302, 'enabled' => false, 'note' => null],
            ],
        ]);

        $this->getJson('/api/v1/redirects')
            ->assertOk()
            ->assertJsonPath('data.rules.0.from', '/old')
            ->assertJsonCount(1, 'data.rules');

        $setting->update(['redirects' => [[
            'id' => '8c8c9c55-ecee-4397-9dd7-31d2ccf05cc2', 'from' => '/later', 'to' => '/now', 'status' => 302, 'enabled' => true, 'note' => null,
        ]]]);
        ApiPayloadCache::invalidateRedirects(1);

        $this->getJson('/api/v1/redirects')
            ->assertOk()
            ->assertJsonPath('data.rules.0.from', '/later')
            ->assertJsonPath('data.rules.0.status', 302);
    }
}
