<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\Http;

use amcsi\LyceeOverture\Http\ConfigureTrustedProxies;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

class ConfigureTrustedProxiesTest extends TestCase
{
    private $originalTrustedHosts;
    private $originalTrustedProxies;
    private $originalTrustedHeaderSet;

    public function setUp()
    {
        parent::setUp();
        $this->originalTrustedHosts = Request::getTrustedHosts();
        $this->originalTrustedProxies = Request::getTrustedProxies();
        $this->originalTrustedHeaderSet = Request::getTrustedHeaderSet();
    }

    public function tearDown()
    {
        Request::setTrustedHosts($this->originalTrustedHosts);
        Request::setTrustedProxies($this->originalTrustedProxies, $this->originalTrustedHeaderSet);
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
