<?php

use App\Http\Controllers\Api\V1\PageController;
use Illuminate\Http\Request;

it('keeps malformed values out of the public numeric page route', function () {
    $numericPageRoute = collect(app('router')->getRoutes()->getRoutes())
        ->first(fn ($route) => $route->getActionName() === PageController::class.'@showById');

    expect($numericPageRoute)->not->toBeNull()
        ->and($numericPageRoute->matches(Request::create('/api/v1/page/abc', 'GET')))->toBeFalse()
        ->and($numericPageRoute->matches(Request::create('/api/v1/page/123', 'GET')))->toBeTrue();
});

it('returns 404 for malformed numeric route identifiers', function (string $method, string $uri) {
    $this->call($method, $uri)->assertNotFound();
})->with([
    'public menu group' => ['GET', '/api/v1/menus/groups/abc'],
    'public company' => ['GET', '/api/v1/companies/abc'],
    'API payment success query' => ['GET', '/api/v1/payment/success?payment=abc'],
    'API payment cancel query' => ['GET', '/api/v1/payment/cancel?payment=abc'],
    'web payment success query' => ['GET', '/payment/success?payment=abc'],
    'web payment cancel query' => ['GET', '/payment/cancel?payment=abc'],

    'uploaded file show' => ['GET', '/backend/uploaded-files/abc'],
    'uploaded file update PUT' => ['PUT', '/backend/uploaded-files/abc'],
    'uploaded file update PATCH' => ['PATCH', '/backend/uploaded-files/abc'],
    'uploaded file delete' => ['DELETE', '/backend/uploaded-files/abc'],
    'uploaded file edit' => ['GET', '/backend/uploaded-files/abc/edit'],
    'uploaded file legacy destroy' => ['GET', '/backend/uploaded-files/destroy/abc'],
    'uploaded file download' => ['GET', '/backend/aiz-uploader/download/abc'],

    'company show' => ['GET', '/backend/companies/abc'],
    'company update PUT' => ['PUT', '/backend/companies/abc'],
    'company update PATCH' => ['PATCH', '/backend/companies/abc'],
    'company delete' => ['DELETE', '/backend/companies/abc'],
    'company edit' => ['GET', '/backend/companies/abc/edit'],

    'page show' => ['GET', '/backend/pages/abc'],
    'page update PUT' => ['PUT', '/backend/pages/abc'],
    'page update PATCH' => ['PATCH', '/backend/pages/abc'],
    'page delete' => ['DELETE', '/backend/pages/abc'],
    'page edit' => ['GET', '/backend/pages/abc/edit'],
    'page layout fields' => ['GET', '/backend/pages/abc/layout-fields'],
    'page clone' => ['GET', '/backend/pages/abc/clone'],

    'post update PUT' => ['PUT', '/backend/posts/abc'],
    'post update PATCH' => ['PATCH', '/backend/posts/abc'],
    'post delete' => ['DELETE', '/backend/posts/abc'],
    'post edit' => ['GET', '/backend/posts/abc/edit'],
    'post layout fields' => ['GET', '/backend/posts/abc/layout-fields'],

    'post category update PUT' => ['PUT', '/backend/post-categories/abc'],
    'post category update PATCH' => ['PATCH', '/backend/post-categories/abc'],
    'post category delete' => ['DELETE', '/backend/post-categories/abc'],
    'post category edit' => ['GET', '/backend/post-categories/abc/edit'],

    'post tag update PUT' => ['PUT', '/backend/post-tags/abc'],
    'post tag update PATCH' => ['PATCH', '/backend/post-tags/abc'],
    'post tag delete' => ['DELETE', '/backend/post-tags/abc'],
    'post tag edit' => ['GET', '/backend/post-tags/abc/edit'],

    'author update PUT' => ['PUT', '/backend/authors/abc'],
    'author update PATCH' => ['PATCH', '/backend/authors/abc'],
    'author delete' => ['DELETE', '/backend/authors/abc'],
    'author edit' => ['GET', '/backend/authors/abc/edit'],

    'payment show' => ['GET', '/backend/payments/abc'],

    'visitor show' => ['GET', '/backend/visitors/abc'],
    'visitor update PUT' => ['PUT', '/backend/visitors/abc'],
    'visitor update PATCH' => ['PATCH', '/backend/visitors/abc'],
    'visitor delete' => ['DELETE', '/backend/visitors/abc'],
    'visitor edit' => ['GET', '/backend/visitors/abc/edit'],

    'user show' => ['GET', '/backend/users/abc'],
    'user update PUT' => ['PUT', '/backend/users/abc'],
    'user update PATCH' => ['PATCH', '/backend/users/abc'],
    'user delete' => ['DELETE', '/backend/users/abc'],
    'user edit' => ['GET', '/backend/users/abc/edit'],

    'role show' => ['GET', '/backend/roles/abc'],
    'role update PUT' => ['PUT', '/backend/roles/abc'],
    'role update PATCH' => ['PATCH', '/backend/roles/abc'],
    'role delete' => ['DELETE', '/backend/roles/abc'],
    'role edit' => ['GET', '/backend/roles/abc/edit'],

    'SEO meta show' => ['GET', '/backend/seo-meta/abc'],
    'SEO meta update PUT' => ['PUT', '/backend/seo-meta/abc'],
    'SEO meta update PATCH' => ['PATCH', '/backend/seo-meta/abc'],
    'SEO meta delete' => ['DELETE', '/backend/seo-meta/abc'],
    'SEO meta edit' => ['GET', '/backend/seo-meta/abc/edit'],
    'SEO meta clone' => ['GET', '/backend/seo-meta/abc/clone'],
]);
