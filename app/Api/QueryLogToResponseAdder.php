<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Api;

use Dingo\Api\Event\ResponseWasMorphed;
use Illuminate\Support\Str;

/**
 * Adds DB query log to the response (except on production).
 */
class QueryLogToResponseAdder
{
    public function handle(ResponseWasMorphed $event)
    {
        if (!config('app.debug')) {
            return;
        }
        $content =& $event->content;
        // Need to check that the response is an API response.
        if (is_array($content)) {
            $content['debug'] = array_map(
                function (array $logEntry) {
                    $logEntry['sql'] = self::getBoundSql($logEntry['query'], $logEntry['bindings']);
                    return $logEntry;
                },
                \DB::getQueryLog()
            );
        }
    }

    private static function getBoundSql(string $unboundSql, array $bindings)
    {
        $wrappedStr = str_replace('?', "'?'", $unboundSql);
        return Str::replaceArray('?', $bindings, $wrappedStr);
    }
}
