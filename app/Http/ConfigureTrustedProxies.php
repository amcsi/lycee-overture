<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http;

use Illuminate\Support\Facades\Request;

/**
 * This is to make sure that even though HTTPS is proxies with the help of nginx-proxy (docker),
 * Laravel should still show treat the current request as HTTPS when calling getSchemeAndHttpHost().
 */
class ConfigureTrustedProxies
{
    public static function configure(): void
    {
        // The first two numbers stick, the rest are dynamic.
        $dockerIpRange = '172.18.0.0/16';

        Request::setTrustedProxies(
            [$dockerIpRange],
            \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_PROTO
        );
    }
}
