<?php

use App\Models\Author;
use App\Models\MenuGroup;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\PageMeta;
use App\Models\Post;
use App\Models\PostMeta;
use App\Models\SeoMeta;
use App\Models\SeoSetting;
use App\Models\Upload;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

it('publishes populated CMS content through the public APIs', function () {
    config([
        'cache.default' => 'array',
        'custom.company_id' => 1,
        'custom.frontend_url' => 'https://frontend.audit.test',
        'custom.assets_url' => 'https://assets.audit.test',
    ]);

    $upload = Upload::create([
        'file_original_name' => 'audit-hero.jpg',
        'file_name' => 'storage/uploads/audit-hero.jpg',
        'extension' => 'jpg',
        'type' => 'image',
        'file_size' => 1234,
    ]);
    $detailUpload = Upload::create([
        'file_original_name' => 'audit-detail.jpg',
        'file_name' => 'storage/uploads/audit-detail.jpg',
        'extension' => 'jpg',
        'type' => 'image',
        'file_size' => 2345,
    ]);

    $page = Page::create([
        'slug' => 'audit-page',
        'language' => 'en',
        'title' => 'Audit Page',
        'content' => '<p>Published page body</p>',
        'is_active' => true,
        'layout' => 'audit-layout',
        'company_id' => 1,
        'seo_title' => 'Page SEO fallback',
    ]);
    PageMeta::create([
        'page_id' => $page->id,
        'meta_key' => 'hero_image',
        'meta_value' => (string) $upload->id,
    ]);
    SeoMeta::create([
        'slug' => 'audit-page',
        'meta_title' => 'Audit SEO Title',
        'meta_description' => 'Audit SEO Description',
        'canonical_url' => 'https://frontend.audit.test/audit-page',
        'robots_index' => 'index',
        'robots_follow' => 'follow',
        'sitemap_priority' => 0.8,
    ]);

    $author = Author::create([
        'name' => 'Audit Author',
        'slug' => 'audit-author',
        'is_active' => true,
        'company_id' => 1,
    ]);
    $post = Post::create([
        'slug' => 'audit-post',
        'language' => 'en',
        'title' => 'Audit Post',
        'content' => '<p>Published post body</p>',
        'featured_image' => (string) $upload->id,
        'featured_detail_image' => $upload->id . ',' . $detailUpload->id,
        'layout' => 'default_post_detail',
        'is_active' => true,
        'company_id' => 1,
        'author_id' => $author->id,
        'published_at' => now(),
    ]);
    PostMeta::create([
        'post_id' => $post->id,
        'meta_key' => 'short_summary',
        'meta_value' => 'Audit post summary',
    ]);
    SeoMeta::create([
        'slug' => 'audit-post',
        'meta_title' => 'Audit Post SEO Title',
        'robots_index' => 'index',
        'robots_follow' => 'follow',
    ]);

    $menu = MenuGroup::create([
        'name' => 'Audit Navigation',
        'slug' => 'audit-navigation',
        'status' => true,
    ]);
    $rootItem = MenuItem::create([
        'menu_group_id' => $menu->id,
        'name' => 'Audit Home',
        'url' => '/audit-page',
        'order' => 1,
        'status' => true,
    ]);
    MenuItem::create([
        'menu_group_id' => $menu->id,
        'parent_id' => $rootItem->id,
        'name' => 'Audit Child',
        'url' => '/audit-page/child',
        'order' => 1,
        'status' => true,
    ]);

    SeoSetting::create([
        'company_id' => 1,
        'content' => "User-agent: *\nAllow: /\nSitemap: https://frontend.audit.test/sitemap.xml",
    ]);

    $this->getJson('/api/v1/page/audit-page')
        ->assertOk()
        ->assertJsonPath('data.title', 'Audit Page')
        ->assertJsonPath('data.content', '<p>Published page body</p>')
        ->assertJsonPath('data.meta.hero_image.id', $upload->id)
        ->assertJsonPath('data.meta.hero_image.filename', 'audit-hero.jpg')
        ->assertJsonPath('data.seo.title', 'Audit SEO Title')
        ->assertJsonPath('data.seo.canonical_url', 'https://frontend.audit.test/audit-page');

    $this->getJson('/api/v1/posts/audit-post')
        ->assertOk()
        ->assertJsonPath('data.title', 'Audit Post')
        ->assertJsonPath('data.content', '<p>Published post body</p>')
        ->assertJsonPath('data.featured_image.id', $upload->id)
        ->assertJsonPath('data.featured_detail_image.0.id', $upload->id)
        ->assertJsonPath('data.featured_detail_image.1.id', $detailUpload->id)
        ->assertJsonPath('data.summary', 'Audit post summary')
        ->assertJsonPath('data.seo.title', 'Audit Post SEO Title');

    $this->getJson('/api/v1/posts')
        ->assertOk()
        ->assertJsonFragment(['slug' => 'audit-post']);

    $this->getJson('/api/v1/menus/groups/' . $menu->id)
        ->assertOk()
        ->assertJsonPath('data.menu_group.slug', 'audit-navigation')
        ->assertJsonPath('data.items.0.name', 'Audit Home')
        ->assertJsonPath('data.items.0.children.0.name', 'Audit Child');

    $this->getJson('/api/v1/robots-txt')
        ->assertOk()
        ->assertJsonPath('data.source_company_id', 1)
        ->assertJsonPath('data.content', "User-agent: *\nAllow: /\nSitemap: https://frontend.audit.test/sitemap.xml\n");

    $this->get('/api/v1/robots-txt?format=txt')
        ->assertOk()
        ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
        ->assertSeeText('Sitemap: https://frontend.audit.test/sitemap.xml');

    $this->getJson('/api/v1/sitemap')
        ->assertOk()
        ->assertJsonFragment(['slug' => 'https://frontend.audit.test/audit-page'])
        ->assertJsonFragment(['slug' => 'https://frontend.audit.test/audit-post']);
});
