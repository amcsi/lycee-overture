<?php
declare(strict_types=1);

use amcsi\LyceeOverture\Http\Controllers\ArticleController;
use amcsi\LyceeOverture\Http\Controllers\CardController;
use amcsi\LyceeOverture\Http\Controllers\CardSetController;
use amcsi\LyceeOverture\Http\Controllers\SetController;
use amcsi\LyceeOverture\Http\Controllers\StatisticsController;
use Dingo\Api\Routing\Router;
use Illuminate\Http\Request;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

$api = app('Dingo\Api\Routing\Router');
$api->version(
    'v1',
    function (Router $api) {
        $api->group(['middleware' => EnsureFrontendRequestsAreStateful::class],
            function (Router $api) {
                $api->group(
                    ['prefix' => 'cards'],
                    function (Router $api) {
                        $api->resource('/', CardController::class, ['only' => ['index']]);
                    }
                );
                $api->get('/card-sets', CardSetController::class . '@index');
                $api->get('/sets', SetController::class . '@index');
                $api->get('/statistics', StatisticsController::class . '@index');
                $api->get('/articles', ArticleController::class . '@index');
            });
    }
);
