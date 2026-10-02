<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\SeoController;
use App\Http\Middleware\TrackPageView;
use Illuminate\Support\Facades\Route;

Route::middleware(TrackPageView::class)->group(function () {
    Route::get('/', [PortfolioController::class, 'index'])->name('home');
    Route::get('/resume', [PortfolioController::class, 'resume'])->name('resume');
    Route::get('/work/{slug}', [PortfolioController::class, 'caseStudy'])->where('slug', '[a-z0-9-]+')->name('work.show');
});

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])
    ->withoutMiddleware([\Illuminate\Session\Middleware\StartSession::class, \Illuminate\View\Middleware\ShareErrorsFromSession::class, \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,10')
    ->name('contact');

Route::prefix('admin/api')->group(function () {
    Route::post('login', [Admin\AuthController::class, 'login'])->middleware('throttle:6,1');
    Route::post('logout', [Admin\AuthController::class, 'logout']);

    Route::middleware('auth')->group(function () {
        Route::get('dashboard', Admin\DashboardController::class);

        Route::get('messages', [Admin\MessageController::class, 'index']);
        Route::patch('messages/{message}', [Admin\MessageController::class, 'update']);
        Route::delete('messages/{message}', [Admin\MessageController::class, 'destroy']);

        Route::get('content', [Admin\ContentController::class, 'show']);
        Route::put('content', [Admin\ContentController::class, 'update']);
        Route::patch('content/availability', [Admin\ContentController::class, 'availability']);
        Route::delete('content', [Admin\ContentController::class, 'destroy']);

        Route::get('resume', [Admin\ResumeController::class, 'show']);
        Route::post('resume', [Admin\ResumeController::class, 'store']);

        Route::put('password', [Admin\AccountController::class, 'password']);
    });
});

Route::get('/admin/{any?}', Admin\AppController::class)->where('any', '(?!api).*')->name('admin');
