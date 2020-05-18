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

Auth::routes();

Route::get(
    '{any}',
    function () {

        if (!($appUrl = env('APP_URL'))) {
            $scheme = env('FORCE_HTTPS') ? 'https' : Request::getScheme();
            $host = Request::getHttpHost();
            $appUrl = "$scheme://$host";
        }

        $vars = [
            'apiBaseUrl' => "$appUrl/api",
            'cloudinaryCloudName' => config('cloudinary.defaults.cloud_name'),
            'rollbarToken' => env('ROLLBAR_CLIENT_TOKEN'),
            'gitSha1' => env('GIT_SHA1'),
            'environment' => config('app.env'),
        ];
        return view('spa', ['jsVars' => $vars]);
    }
)->where('any', '.*');
