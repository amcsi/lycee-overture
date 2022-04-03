<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Middleware;

use Illuminate\Http\Request;

class TrustProxies extends \Illuminate\Http\Middleware\TrustProxies
{
    /**
     * The trusted proxies for this application.
     *
     * Docker; The first two numbers stick, the rest are dynamic.
     *
     * @var array
     */
    protected $proxies = ['172.18.0.0/16'];
}
