<?php
declare(strict_types=1);


namespace amcsi\LyceeOverture\Import;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Psr7\str;

/**
 * Downloads the CSV from the official Lycee website.
 */
class CsvDownloader
{
    public function __construct(private array $config, private Client $client)
    {
    }

    public function download(): ResponseInterface
    {
        $config = $this->config;
        $url = $config['importBaseUrl'] . '?' . http_build_query($config['importQueryParameters']);
        $response = $this->client->get($url);
        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException("Status code not 200: " . str($response));
        }
        return $response;
    }
}
