<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Etc;

use Illuminate\Support\Facades\Log;
use function GuzzleHttp\Psr7\try_fopen;

class LackeyHasher
{
    /**
     * LackeyCCH hashing algorithm.
     *
     * http://www.lackeyccg.com/forum/index.php?topic=808.msg6056#msg6056
     */
    public static function hashFile($filename): int
    {
        try {
            $fp = try_fopen($filename, 'rb');
        } catch (\RuntimeException $exception) {
            Log::warning((string) $exception);
            return 0;
        }


        $f2 = 0;
        $sum = 0;

        do {
            $f1 = $f2;
            $char = fgetc($fp);

            $ord = is_string($char) ?
                ord($char) :
                -1;
            $ord = $ord > 127 ? -256 + $ord : $ord;

            assert(is_int($ord));
            $f2 = ftell($fp);
            if ($ord !== 10 && $ord !== 13) {
                $sum += $ord;
            }
            $sum %= 100000000;
        } while ($f1 !== $f2);

        fclose($fp);

        return $sum;
    }
}
