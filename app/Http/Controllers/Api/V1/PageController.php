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
        'partner_logos',
        'genesis_image',
        'genesis_video',
        'why_peace_needs_image',
        'our_approach_images',
        'our_inspiration_image',
        'vision_image',
        'vision_person_image',
        'join_bg_image',
        'section_one_image',
        'where_peace_bg_image',
        'collaborate_image',
        'global_presence_image',
        'financial_support_icon',
        'financial_support_image',
        'global_volunteer_image',
        'global_volunteer_images',
        'global_peace_image',
        'global_peace_gallery_images',
        'thought_videos',
        'thought_images',
        'video',
        'images',
        'leadership_image',
        'testimonials_section_image',
        'support_image',
        'global_movements_first_image',
        'global_movements_second_image',
    ];

    private array $multipleUploadMetaKeys = [
        'partner_logos',
        'global_peace_image',
        'global_volunteer_images',
        'thought_videos',
        'thought_images',
        'images',
    ];

    // Post reference keys
    private array $post_category_MetaKeys = [
        'post_block_categories',
        'events_posts',
        'hero_post_categories',
        'upcoming_convocations_post_categories',
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
        'collaborate_items',
        'engage_items',
        'leadership_key_points',
        'thought_leadership_items',
        'thought_video_urls',
        'board_members',
        'patron_members',
        'charter_council_members',
        'team_members',
        'advisory_members',
        'global_movements_items',
        'global_volunteer_items_one',
        'global_volunteer_items_two',
        'build_peace_items',
        'impact_items',
        'philosophy_items',
        'quick_navigation_items',
        'about_items',
        'peace_building_items',
        'our_approach_items',
        'what_we_do_items',
        'our_inspiration_key_highlights',
        'i_am_piece_items',
        'financial_support_items',
        'highlights',
    ];

    private array $removedHomeMetaKeys = [
        'banner_active_community_count',
        'i_am_piece_title',
        'i_am_piece_subtitle',
        'i_am_piece_items',
    ];

    private array $removedAboutUsMetaKeys = [
        'genesis_title',
    ];

    private array $removedOurInspirationMetaKeys = [
        'vision_person_image',
    ];

    private array $removedEventsMetaKeys = [
        'thought_videos',
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
                ->reject(function ($m) use ($page) {
                    return ($page->layout === 'home' && in_array($m->meta_key, $this->removedHomeMetaKeys, true))
                        || ($page->layout === 'about-us' && in_array($m->meta_key, $this->removedAboutUsMetaKeys, true))
                        || ($page->layout === 'our-inspiration' && in_array($m->meta_key, $this->removedOurInspirationMetaKeys, true))
                        || ($page->layout === 'events' && in_array($m->meta_key, $this->removedEventsMetaKeys, true));
                })
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
                ? uploaded_asset_details_from_ids($value, null, !in_array($key, $this->multipleUploadMetaKeys))
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
