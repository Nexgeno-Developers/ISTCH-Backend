<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use App\Services\ApiPayloadCache;
use Illuminate\Http\JsonResponse;

class RedirectController extends Controller
{
    public function index(): JsonResponse
    {
        $companyId = $this->companyIdFromConfig();
        $cached = ApiPayloadCache::getCachedRedirectsPayload($companyId);
        if ($cached !== null) return response()->json(['data' => $cached]);

        $setting = SeoSetting::query()->where('company_id', $companyId)->first();
        if ($setting === null && $companyId !== 0) $setting = SeoSetting::query()->where('company_id', 0)->first();
        $rules = collect($setting?->redirects ?? [])
            ->filter(fn ($rule) => is_array($rule) && ($rule['enabled'] ?? false) === true)
            ->map(fn (array $rule) => [
                'id' => (string) ($rule['id'] ?? ''),
                'from' => (string) ($rule['from'] ?? ''),
                'to' => (string) ($rule['to'] ?? ''),
                'status' => (int) ($rule['status'] ?? 301),
            ])->values()->all();
        $payload = ['generated_at' => now()->toISOString(), 'updated_at' => $setting?->updated_at?->toISOString(), 'rules' => $rules];
        ApiPayloadCache::storeRedirectsPayload($companyId, $payload);

        return response()->json(['data' => $payload]);
    }

    private function companyIdFromConfig(): int
    {
        $raw = config('custom.company_id');
        return is_numeric($raw) && (int) $raw > 0 ? (int) $raw : 0;
    }
}
