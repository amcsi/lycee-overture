<?php

use amcsi\LyceeOverture\Http\Controllers\CardController;
use Dingo\Api\Routing\Router;
use Illuminate\Http\Request;

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
        $api->group(
            ['prefix' => 'cards'],
            function (Router $api) {
                $api->resource('/', CardController::class, ['only' => ['index']]);
            }
        );
    }
);
