<?php

use Atom\Theme\Http\Controllers\AccountSettingsController;
use Atom\Theme\Http\Controllers\ArticleController;
use Atom\Theme\Http\Controllers\ClientController;
use Atom\Theme\Http\Controllers\CommentController;
use Atom\Theme\Http\Controllers\HelpCentreController;
use Atom\Theme\Http\Controllers\HomeController;
use Atom\Theme\Http\Controllers\IndexController;
use Atom\Theme\Http\Controllers\LeaderboardController;
use Atom\Theme\Http\Controllers\PasswordController;
use Atom\Theme\Http\Controllers\PayPalController;
use Atom\Theme\Http\Controllers\PhotoController;
use Atom\Theme\Http\Controllers\ProfileController;
use Atom\Theme\Http\Controllers\PurchaseController;
use Atom\Theme\Http\Controllers\RareValueController;
use Atom\Theme\Http\Controllers\RedeemVoucherController;
use Atom\Theme\Http\Controllers\RuleController;
use Atom\Theme\Http\Controllers\ShopController;
use Atom\Theme\Http\Controllers\SitemapController;
use Atom\Theme\Http\Controllers\StaffApplicationController;
use Atom\Theme\Http\Controllers\StaffController;
use Atom\Theme\Http\Controllers\TeamController;
use Atom\Theme\Http\Controllers\TicketController;
use Atom\Theme\Http\Controllers\TicketReplyController;
use Atom\Theme\Http\Controllers\TopUpController;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('sitemap.xml', SitemapController::class)
        ->name('sitemap');

    Route::get('/', IndexController::class)
        ->middleware('guest')
        ->name('index');


    Route::get('leaderboards', LeaderboardController::class)
        ->middleware(Authenticate::using('sanctum'), 'voting.check')
        ->name('leaderboards');

    Route::get('rare-values', RareValueController::class)
        ->middleware(Authenticate::using('sanctum'), 'voting.check')
        ->name('rare-values');

    Route::name('game.')->prefix('game')->group(function () {
        Route::get('nitro', ClientController::class)
            ->middleware(Authenticate::using('sanctum'), 'voting.check')
            ->name('nitro');

        Route::get('nitro/voted', ClientController::class)
            ->middleware(Authenticate::using('sanctum'))
            ->name('nitro.voted');
    });

    Route::name('users.')->prefix('users')->group(function () {
        Route::get('me', HomeController::class)
            ->middleware(Authenticate::using('sanctum'), 'voting.check')
            ->name('me');

        Route::name('settings.')->prefix('settings')->group(function () {
            Route::resource('account', AccountSettingsController::class)
                ->middleware(Authenticate::using('sanctum'), 'voting.check')
                ->only(['index', 'store']);

            Route::resource('email', AccountSettingsController::class)
                ->middleware(Authenticate::using('sanctum'), 'voting.check')
                ->only(['index', 'store']);

            Route::resource('password', PasswordController::class)
                ->middleware(Authenticate::using('sanctum'), 'voting.check')
                ->only(['index', 'store']);
        });
    });


    Route::name('community.')->prefix('community')->group(function () {

        Route::get('staff', StaffController::class)
            ->middleware(Authenticate::using('sanctum'), 'voting.check')
            ->name('staff');

        Route::resource('articles', ArticleController::class)
            ->middleware(Authenticate::using('sanctum'), 'voting.check')
            ->only(['index', 'show', 'update']);

        Route::post('articles/{websiteArticle}/comment', CommentController::class)
            ->middleware(Authenticate::using('sanctum'), 'voting.check')
            ->name('articles.comments.store');

    });
});
