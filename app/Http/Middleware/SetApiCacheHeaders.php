<?php

namespace App\Http\Middleware;

use App\Services\ApiPayloadCache;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetApiCacheHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $response->headers->set('Vary', 'Accept, Accept-Encoding, Origin');

        if (! $this->isCacheablePublicGet($request, $response)) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');

            return $response;
        }

        $browserTtl = max(60, min(600, (int) config('custom.cache_minutes', 120) * 60));
        $sharedTtl = max($browserTtl, min(ApiPayloadCache::ttlSeconds(), 86400));
        $staleWhileRevalidate = min(300, max(60, (int) floor($browserTtl / 2)));
        $staleIfError = min(86400, max(600, $sharedTtl));

        $response->headers->remove('Pragma');
        $response->headers->remove('Expires');
        $response->headers->set(
            'Cache-Control',
            sprintf(
                'public, max-age=%d, s-maxage=%d, stale-while-revalidate=%d, stale-if-error=%d',
                $browserTtl,
                $sharedTtl,
                $staleWhileRevalidate,
                $staleIfError,
            )
        );

        return $response;
    }

    private function isCacheablePublicGet(Request $request, Response $response): bool
    {
        if (! in_array($request->getMethod(), ['GET', 'HEAD'], true)) {
            return false;
        }

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        $cacheablePatterns = [
            'api/v1/companies/*',
            'api/v1/menus/groups/*',
            'api/v1/page/*',
            'api/v1/posts',
            'api/v1/posts/*',
            'api/v1/categories',
            'api/v1/categories/*',
            'api/v1/sitemap',
            'api/v1/robots-txt',
            'api/v1/payments/currencies',
        ];

        foreach ($cacheablePatterns as $pattern) {
            if ($request->is($pattern)) {
                return true;
            }
        }

        return false;
    }
}
