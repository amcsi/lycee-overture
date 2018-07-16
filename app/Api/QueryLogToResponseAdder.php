<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Api;

use Dingo\Api\Event\ResponseWasMorphed;

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
            $content['debug'] = \DB::getQueryLog();
        }
    }
}
