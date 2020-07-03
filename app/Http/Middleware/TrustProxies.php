<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Middleware;

use Fideloper\Proxy\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * Docker; The first two numbers stick, the rest are dynamic.
     *
     * @var array
     */
    protected $proxies = ['172.18.0.0/16'];

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
