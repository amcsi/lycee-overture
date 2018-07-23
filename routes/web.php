<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/** @noinspection PhpUndefinedMethodInspection */
Auth::routes();

Route::get(
    '{any}',
    function () {
        $vars = [
            'apiBaseUrl' => env('API_BASE_URL'),
        ];
        return view('spa', ['jsVars' => $vars]);
    }
)->where('any', '.*');
