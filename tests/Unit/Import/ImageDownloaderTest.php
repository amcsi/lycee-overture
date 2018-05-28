<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import;

use GuzzleHttp\Client;
use League\Flysystem\Adapter\NullAdapter;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;
use PHPUnit\Framework\TestCase;

class ImageDownloaderTest extends TestCase
{
    public function testCreateCardIdRequest()
    {
        $cardId = 'foo';
        $instance = new ImageDownloader(new Client(), new Filesystem(new NullAdapter()));
        $request = $instance->createCardIdRequest($cardId);
        $expected = str_replace('{id}', $cardId, ImportConstants::IMAGE_URL);
        self::assertSame($expected, (string)$request->getUri());
        $headers = $request->getHeaders();
        self::assertArrayNotHasKey(ImageDownloader::HEADER_IF_MODIFIED_SINCE, $headers);
    }

    public function testCreateCardIdRequestWithIfModifiedSince()
    {
        $cardId = 'foo';
        $filesystem = new Filesystem(new MemoryAdapter());
        $instance = new ImageDownloader(new Client(), $filesystem);

        // Put a file there to use for If-Modified-Since.
        $filename = str_replace('{id}', $cardId, ImportConstants::IMAGE_FILENAME);
        $time = strtotime('2012-01-01 00:00:00 GMT');
        $filesystem->put($filename, 'bar', ['timestamp' => $time]);

        $request = $instance->createCardIdRequest($cardId);

        $expected = str_replace('{id}', $cardId, ImportConstants::IMAGE_URL);
        self::assertSame($expected, (string)$request->getUri());
        $headers = $request->getHeaders();
        self::assertArrayHasKey(ImageDownloader::HEADER_IF_MODIFIED_SINCE, $headers);
        self::assertSame('Sun, 01 Jan 2012 00:00:00 GMT', $headers[ImageDownloader::HEADER_IF_MODIFIED_SINCE][0]);
    }
}
