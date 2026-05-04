<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Post;
use App\Models\SeoMeta;
use App\Services\ApiPayloadCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageController extends Controller
{
    // Upload-based meta keys
    private array $uploadMetaKeys = [
        'banner_images',
        'short_summary_icon',
        'hero_image',
        'single_image',
        'multiple_image',
        'single_document',
        'multiple_document',
        'single_video',
        'multiple_video',
        'image',
        'icon',
        'breadcrumb_image',
        'banner_desktop_image',
        'banner_mobile_image',
    ];

    // Post reference keys
    private array $post_category_MetaKeys = [
        'post_block_categories',
    ];

    // Page reference keys
    private array $pageSectionMetaKeys = [

    ];

    // JSON dynamic keys
    private array $dynamicJsonMetaKeys = [
        'dynamic_field',
        'hero_items',
        'info_items',
        'testimonials',
        'engagement_items',
        'our_actions',
        'core_values',
        'highlights',
    ];

    /**
     * Fetch by ID
     */
    public function showById(int $id, Request $request): JsonResponse
    {
        $autofetch = $request->get('autofetch');

        $cached = ApiPayloadCache::getCachedPagePayload($id, $autofetch);
        if ($cached !== null) {
            return response()->json(['data' => $cached]);
        }

        $page = Page::query()
            ->with('meta')
            ->where('id', $id)
            ->where('is_active', true)
            ->first();

        if (!$page) {
            return response()->json([
                'error' => [
                    'message' => 'Page not found',
                    'code' => 'PAGE_NOT_FOUND',
                ],
            ], 404);
        }

        $data = $this->pagePayload($page, $autofetch);
        ApiPayloadCache::storePagePayload((int) $page->id, $autofetch, $data);

        return response()->json(['data' => $data]);
    }

    /**
     * Fetch by slug
     */
    public function showBySlug(string $slug, Request $request): JsonResponse
    {
        $normalizedSlug = trim($slug, '/');
        $autofetch = $request->get('autofetch');

        $pageId = Page::query()
            ->where('slug', $normalizedSlug)
            ->where('is_active', true)
            ->value('id');

        if ($pageId === null) {
            return response()->json([
                'error' => [
                    'message' => 'Page not found',
                    'code' => 'PAGE_NOT_FOUND',
                ],
            ], 404);
        }

        $pageId = (int) $pageId;

        $cached = ApiPayloadCache::getCachedPagePayload($pageId, $autofetch);
        if ($cached !== null) {
            return response()->json(['data' => $cached]);
        }

        $page = Page::query()
            ->with('meta')
            ->where('id', $pageId)
            ->where('is_active', true)
            ->first();

        if (!$page) {
            return response()->json([
                'error' => [
                    'message' => 'Page not found',
                    'code' => 'PAGE_NOT_FOUND',
                ],
            ], 404);
        }

        $data = $this->pagePayload($page, $autofetch);
        ApiPayloadCache::storePagePayload($pageId, $autofetch, $data);

        return response()->json(['data' => $data]);
    }

    /**
     * Main Payload Builder
     */
    private function pagePayload(Page $page, $additionalParams = null): array
    {
        $seoMeta = SeoMeta::query()
            ->where('slug', $page->slug)
            ->first();

        //additionalParams

        $autofetchSections = [];
        if(!empty($additionalParams)){
            $additionalParams = explode(',', $additionalParams);
            
            foreach($additionalParams as $param):
                if($param === 'services') {
                    // $ids = Page::query()->whereIn('layout', ['marketing_services', 'technical_services'])->where('is_active', true)->pluck('id')->toArray();
                    // $autofetchSections['services'] = page_details_from_ids($ids);
                }

                if($param === 'industries') {
                    // $ids = Page::query()->whereIn('layout', ['product_industry_detail'])->where('is_active', true)->pluck('id')->toArray();
                    // $autofetchSections['industries'] = page_details_from_ids($ids);
                }                

                if($param === 'events') {
                    $categoryId = 1;
                    $postsQuery = Post::query()
                        ->where('is_active', true)
                        ->whereHas('categories', function ($q) use ($categoryId) {
                            $q->where('categories.id', $categoryId);
                        })
                        ->with('meta')
                        ->orderByDesc('published_at')
                        ->limit(8);

                    if (auth()->user()?->company_id) {
                        $postsQuery->where('company_id', auth()->user()->company_id);
                    }

                    $latestPosts = $postsQuery->get();

                    $autofetchSections['events'] = $latestPosts->map(function (Post $post) {
                        $summary = $post->meta->firstWhere('meta_key', 'short_summary')?->meta_value;
                        if (!filled($summary)) {
                            $summary = $post->meta->firstWhere('meta_key', 'summary')?->meta_value;
                        }

                        $date = $post->meta->firstWhere('meta_key', 'date')?->meta_value;
                        $time = $post->meta->firstWhere('meta_key', 'time')?->meta_value;

                        return [
                            'id' => $post->id,
                            'title' => $post->title,
                            'slug' => $post->slug,
                            'featured_image' => filled($post->featured_image)
                                ? uploaded_asset_details_from_ids($post->featured_image)
                                : null,
                            'summary' => $summary,
                            'date' => filled($date) ? $date : null,
                            'time' => filled($time) ? $time : null,
                        ];
                    })->values()->all();
                }                  
            endforeach;

        }

        return [
            'id' => $page->id,
            'slug' => $page->slug,
            'language' => $page->language,
            'title' => $page->title,
            'content' => $page->content,
            'is_active' => (bool) $page->is_active,
            'layout' => $page->layout,
            'company_id' => $page->company_id,

            'meta' => $page->meta
                ->mapWithKeys(function ($m) {

                    $value = $m->meta_value;

                    if (in_array($m->meta_key, $this->dynamicJsonMetaKeys)) {
                        $value = $this->resolveDynamicJson($value);
                    } else {
                        $value = $this->resolveMetaValue($m->meta_key, $value);
                    }

                    return [$m->meta_key => $value];
                })
                ->all(),

            'seo' => [
                'title'       => filled($seoMeta?->meta_title) ? $seoMeta->meta_title : $page->seo_title,
                'description' => filled($seoMeta?->meta_description) ? $seoMeta->meta_description : $page->seo_description,
                'keywords'    => filled($seoMeta?->meta_keywords) ? $seoMeta->meta_keywords : $page->seo_keywords,
                'schema'              => $seoMeta?->schema_json,
                'canonical_url'       => $seoMeta?->canonical_url,
                'robots_index'        => $seoMeta?->robots_index,
                'robots_follow'       => $seoMeta?->robots_follow,
                'og_title'            => $seoMeta?->og_title,
                'og_description'      => $seoMeta?->og_description,
                'og_image'            => filled($seoMeta?->og_image) ? uploaded_asset_details_from_ids($seoMeta->og_image) : null,
                'twitter_title'       => $seoMeta?->twitter_title,
                'twitter_description' => $seoMeta?->twitter_description,
                'twitter_image'       => filled($seoMeta?->twitter_image) ? uploaded_asset_details_from_ids($seoMeta->twitter_image) : null,
                'sitemap_priority'    => $seoMeta?->sitemap_priority,
            ],
            'autofetch' => $autofetchSections,
        ];
    }

    /**
     * Resolve normal meta values
     */
    private function resolveMetaValue(string $key, $value)
    {
        if (in_array($key, $this->uploadMetaKeys)) {
            return filled($value)
                ? uploaded_asset_details_from_ids($value)
                : null;
        }

        if (in_array($key, $this->pageSectionMetaKeys)) {
            return page_details_from_ids($value);
        }

        if (in_array($key, $this->post_category_MetaKeys)) {
            return post_category_details_from_ids($value);
        }

        return $value;
    }

    /**
     * Resolve dynamic JSON meta
     */
    private function resolveDynamicJson($json)
    {
        $decoded = json_decode($json, true);

        if (!is_array($decoded)) {
            return $json;
        }

        foreach ($decoded as $key => $values) {

            if (!is_array($values)) continue;

            foreach ($values as $index => $val) {
                $decoded[$key][$index] = $this->resolveMetaValue($key, $val);
            }
        }

        return $decoded;
    }
}
