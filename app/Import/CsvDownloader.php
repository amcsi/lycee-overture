<?php
declare(strict_types=1);


namespace amcsi\LyceeOverture\Import;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Message;
use Psr\Http\Message\ResponseInterface;

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
        return $this->downloadPage(page: 1, limit: 100000);
    }

    public function downloadPage(int $page, int $limit = 1000): ResponseInterface
    {
        $config = $this->config;
        $query = array_merge(
            $config['importQueryParameters'] ?? [],
            ['page' => (string) $page, 'limit' => (string) $limit]
        );
        $url = $config['importBaseUrl'] . '?' . http_build_query($query);
        $response = $this->client->get($url);
        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException("Status code not 200: " . Message::toString($response));
        }
        return $response;
    }
}
