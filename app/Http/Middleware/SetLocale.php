<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Middleware;

use amcsi\LyceeOverture\I18n\Locale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

/**
 * https://laracasts.com/discuss/channels/general-discussion/where-to-setlocale-in-laravel-5-on-multilingual-multidomain-app
 */
class SetLocale
{
    /**
     * The available languages.
     */
    protected $languages = [...Locale::TRANSLATION_LOCALES, 'ja'];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $locale = null;
        if (($locale = $request->query('locale')) && in_array($locale, $this->languages, true)) {
            // Reassemble the query, but without the locale, then redirect.
            $query = $request->query();
            unset($query['locale']);
            $url = $request->path() . '?' . http_build_query($query);
            return redirect($url)->withCookie(Cookie::make('locale', $locale));
        }

        if (!Cookie::has('locale')) {
            // Get the preferred language from the browser info. But do not save it.
            $locale = $request->getPreferredLanguage($this->languages);
        } else {
            $locale = Cookie::get('locale');
        }

        app()->setLocale($locale ?: 'en');

        return $next($request);
    }
}
