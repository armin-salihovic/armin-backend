<?php

use App\Http\Controllers\MailController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController;
use App\Models\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::domain(config('app.url'))->group(function () {
    Route::get('cache', function() {
        $cache = Cache::first();
        return response()->json(['cache' => $cache->cache]);
    });

    Route::get('projects', [ProjectController::class, 'index']);
    Route::get('projects/{slug}', [ProjectController::class, 'show']);
    Route::post('send-email', [MailController::class, 'sendEmail']);
    Route::get('settings', [PageController::class, 'getSettings']);
    Route::get('{slug}', [PageController::class, 'show']);

});
