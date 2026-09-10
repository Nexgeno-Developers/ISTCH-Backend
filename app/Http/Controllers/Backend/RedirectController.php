<?php

namespace App\Http\Controllers\Backend;

use App\Models\SeoSetting;
use App\Services\ApiPayloadCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RedirectController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:seo-settings view')->only(['index']);
        $this->middleware('permission:seo-settings edit')->only(['update']);
    }

    public function index()
    {
        $companyId = $this->companyIdFromConfig();
        $setting = SeoSetting::query()->firstOrNew(['company_id' => $companyId]);

        return view('backend.redirects.index', [
            'companyId' => $companyId,
            'redirects' => $this->normalizeRules($setting->redirects ?? []),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate(['redirects' => ['required', 'json']]);
        $decoded = json_decode((string) $request->input('redirects'), true, 512, JSON_THROW_ON_ERROR);
        $rules = $this->normalizeRules($decoded, true);
        $companyId = $this->companyIdFromConfig();

        SeoSetting::query()->updateOrCreate(
            ['company_id' => $companyId],
            ['redirects' => $rules],
        );
        ApiPayloadCache::invalidateRedirects($companyId);

        return response()->json([
            'status' => true,
            'notification' => __('messages.updated'),
            'data' => $rules,
        ]);
    }

    /** @return array<int, array{id:string,from:string,to:string,status:int,enabled:bool,note:?string}> */
    private function normalizeRules(mixed $rules, bool $strict = false): array
    {
        if (!is_array($rules)) {
            if ($strict) throw ValidationException::withMessages(['redirects' => ['Redirect rules must be a JSON array.']]);
            return [];
        }

        $normalized = [];
        $sources = [];
        foreach ($rules as $index => $rule) {
            if (!is_array($rule)) {
                if ($strict) throw ValidationException::withMessages(["redirects.$index" => ['Each redirect must be an object.']]);
                continue;
            }
            $from = $this->normalizePath($rule['from'] ?? null);
            $to = $this->normalizePath($rule['to'] ?? null);
            $status = (int) ($rule['status'] ?? 301);
            $enabled = filter_var($rule['enabled'] ?? true, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $note = isset($rule['note']) ? trim((string) $rule['note']) : null;

            if ($from === null || $to === null || $from === $to || !in_array($status, [301, 302], true) || $enabled === null || ($note !== null && mb_strlen($note) > 500) || isset($sources[$from])) {
                if ($strict) throw ValidationException::withMessages(["redirects.$index" => ['Use unique internal paths, a different destination, and a 301 or 302 status.']]);
                continue;
            }
            $sources[$from] = true;
            $id = is_string($rule['id'] ?? null) && Str::isUuid($rule['id']) ? $rule['id'] : (string) Str::uuid();
            $normalized[] = ['id' => $id, 'from' => $from, 'to' => $to, 'status' => $status, 'enabled' => $enabled, 'note' => $note ?: null];
        }
        return $normalized;
    }

    private function normalizePath(mixed $value): ?string
    {
        if (!is_string($value)) return null;
        $path = trim($value);
        if ($path === '' || !str_starts_with($path, '/') || str_starts_with($path, '//') || str_contains($path, '\\') || preg_match('/[\r\n]/', $path)) return null;
        $parts = parse_url($path);
        if ($parts === false || isset($parts['scheme'], $parts['host'], $parts['user'], $parts['pass'])) return null;
        $normalized = '/' . ltrim((string) ($parts['path'] ?? '/'), '/');
        return $normalized === '//' ? '/' : (rtrim($normalized, '/') ?: '/');
    }

    private function companyIdFromConfig(): int
    {
        $raw = config('custom.company_id');
        return is_numeric($raw) && (int) $raw > 0 ? (int) $raw : 0;
    }
}
