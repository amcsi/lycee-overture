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
use amcsi\LyceeOverture\Image\ImageUrlBuilder;
use amcsi\LyceeOverture\Image\ImageUrlTemplates;
use amcsi\LyceeOverture\Image\Local\LocalImageFiles;
use amcsi\LyceeOverture\Models\User;

Auth::routes();

Route::get('logout', [LoginController::class, 'logout']);
// Normally served by nginx; this covers setups without its /storage/images/ location, e.g. local development.
// Without the web middleware, as its cookies would keep Cloudflare from caching the images.
Route::get('storage/images/{path}', function (string $path) {
    $file = LocalImageFiles::resolve(config('images.output_path'), $path);
    abort_if($file === null, 404);
    return response()->file($file, ['Cache-Control' => 'public, max-age=2592000']);
})->where('path', '.*')->withoutMiddleware('web');

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
            'imageUrlTemplates' => ImageUrlTemplates::build(app(ImageUrlBuilder::class)),
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
