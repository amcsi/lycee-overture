<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\Http;

use amcsi\LyceeOverture\Http\ConfigureTrustedProxies;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

class ConfigureTrustedProxiesTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        Request::setTrustedHosts([]);
        Request::setTrustedProxies([], -1);
    }

    public function tearDown()
    {
        Request::setTrustedHosts([]);
        Request::setTrustedProxies([], -1);
        parent::tearDown();
    }


    public function testProxiesDockerHttps(): void
    {
        $request = new Request(
            [], [], [], [], [], [
            'HTTP_HOST' => 'some_host',
            'REMOTE_ADDR' => '172.18.0.4',
            'HTTP_X_FORWARDED_PROTO' => 'https',
        ]
        );
        self::assertSame('http://some_host', $request->getSchemeAndHttpHost());
        ConfigureTrustedProxies::configure();
        self::assertSame('https://some_host', $request->getSchemeAndHttpHost());
    }
}
