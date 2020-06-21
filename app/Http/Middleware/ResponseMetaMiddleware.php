<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ResponseMetaMiddleware
{
    public function handle($request, Closure $next)
    {
        /** @var Response $response */
        $response = $next($request);

        if (!config('app.debug')) {
            return $response;
        }

        if ($response instanceof JsonResponse) {
            $json = $response->getData(true);
            $json['debug'] = self::getQueryLog();
            $response->setJson(json_encode($json, JSON_THROW_ON_ERROR));
        } elseif ($response->headers->get('Content-Type') === 'application/json') {
            $json = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $json['debug'] = self::getQueryLog();
            $response->setContent(json_encode($json, JSON_THROW_ON_ERROR));
        }

        return $response;
    }

    /**
     * Gets the query log, but with sql placeholders pre-bound.
     * @param bool $onlyBound If true, do not include the unbound queries.
     * @return array|array[]
     */
    public static function getQueryLog($onlyBound = true)
    {
        return array_map(
            function (array $logEntry) use ($onlyBound) {
                $logEntry['sql'] = self::getBoundSql($logEntry['query'], $logEntry['bindings']);
                if ($onlyBound) {
                    unset($logEntry['query'], $logEntry['bindings']);
                }
                return $logEntry;
            },
            \DB::getQueryLog()
        );
    }

    private static function getBoundSql(string $unboundSql, array $bindings)
    {
        $wrappedStr = str_replace('?', "'?'", $unboundSql);
        return Str::replaceArray('?', $bindings, $wrappedStr);
    }
}
