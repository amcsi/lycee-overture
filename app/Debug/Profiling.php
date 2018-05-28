<?php
declare(strict_types=1);


namespace amcsi\LyceeOverture\Debug;

use Symfony\Component\Stopwatch\StopwatchEvent;

/**
 * Tools for profiling.
 */
class Profiling
{
    static function stopwatchToHuman(StopwatchEvent $event): string
    {
        return sprintf('%.4f seconds', $event->getDuration() / 1000);
    }
}
