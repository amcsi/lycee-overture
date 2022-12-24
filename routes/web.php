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

use amcsi\LyceeOverture\Http\Controllers\Auth\LoginController;
use amcsi\LyceeOverture\User;

Auth::routes();

Route::get('logout', [LoginController::class, 'logout']);
Route::get(
    '{any}',
    function () {

        if (!($appUrl = env('APP_URL'))) {
            $scheme = env('FORCE_HTTPS') ? 'https' : Request::getScheme();
            $host = Request::getHttpHost();
            $appUrl = "$scheme://$host";
        }

        /** @var User $authUser */
        $authUser = Auth::user();

        $vars = [
            'apiBaseUrl' => "$appUrl/api",
            'cloudinaryCloudName' => config('cloudinary.defaults.cloud_name'),
            'sentryToken' => env('SENTRY_LARAVEL_DSN'),
            'gitSha1' => env('GIT_SHA1'),
            'environment' => config('app.env'),
            'auth' => $authUser ? [
                'id' => $authUser->id,
                'name' => $authUser->name,
                'canApproveLocales' => $authUser->can_approve_locale ?
                    explode(',', $authUser->can_approve_locale) :
                    [],
            ] : null,
        ];
        return view('spa', ['jsVars' => $vars]);
    }
)->where('any', '.*');
