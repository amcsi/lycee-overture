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
        $content['debug'] = \DB::getQueryLog();
    }
}
