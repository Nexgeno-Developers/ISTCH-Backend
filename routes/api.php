<?php

use App\Http\Controllers\Api\V1\AuthorController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CompanyController;
use App\Http\Controllers\Api\V1\FormSubmissionController;
use App\Http\Controllers\Api\V1\MenuController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\SeoSettingController;
use App\Http\Controllers\Api\V1\SitemapController;
use App\Http\Controllers\Api\V1\StripeWebhookController;
use App\Http\Controllers\Api\V1\TagController;
use Illuminate\Support\Facades\Route;

Route::post('donate', [PaymentController::class, 'donate'])
    ->middleware('throttle:10,1');

Route::prefix('v1')->group(function () {
    Route::get('menus/groups/{id}', [MenuController::class, 'showById'])->whereNumber('id');
    Route::get('menus/groups/by-name/{name}', [MenuController::class, 'showByName']);

    Route::get('companies/{id}', [CompanyController::class, 'showById'])->whereNumber('id');

    Route::post('forms/submit', [FormSubmissionController::class, 'submit'])
        ->middleware(['protect.forms', 'recaptcha', 'throttle:10,1']);

    Route::get('page/{id}', [PageController::class, 'showById'])->whereNumber('id');
    Route::get('page/{slug}', [PageController::class, 'showBySlug'])->where('slug', '.*');

    Route::get('posts', [PostController::class, 'index']);
    Route::get('posts/{slug}', [PostController::class, 'showBySlug'])->where('slug', '.*');

    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{slug_or_id}', [CategoryController::class, 'show'])
        ->where('slug_or_id', '^(?!.*\\/posts$).*$');

    Route::get('sitemap', [SitemapController::class, 'index']);
    Route::get('robots-txt', [SeoSettingController::class, 'robotsTxt']);

    Route::get('payments/currencies', [PaymentController::class, 'currencies']);
    Route::post('donate', [PaymentController::class, 'donate'])->middleware('throttle:10,1')->name('donation.submit');
    Route::get('payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
    Route::post('stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');
});
