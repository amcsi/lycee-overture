<?php
declare(strict_types=1);

use amcsi\LyceeOverture\Http\Controllers\ArticleController;
use amcsi\LyceeOverture\Http\Controllers\CardController;
use amcsi\LyceeOverture\Http\Controllers\CardSetController;
use amcsi\LyceeOverture\Http\Controllers\SetController;
use amcsi\LyceeOverture\Http\Controllers\StatisticsController;
use amcsi\LyceeOverture\Http\Controllers\SuggestionController;

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

Route::apiResource('/cards', CardController::class, ['only' => ['index', 'show']]);
Route::get('/card-sets', [CardSetController::class, 'index']);
Route::get('/sets', [SetController::class, 'index']);
Route::get('/statistics', [StatisticsController::class, 'index']);
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/suggestions', [SuggestionController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('suggestions', [SuggestionController::class, 'store']);
});
