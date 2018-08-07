<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use function GuzzleHttp\Psr7\str;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;

class OneSkyClient
{
    private $guzzleClient;

    public function __construct(array $oneSkyConfig)
    {
        $projectId = $oneSkyConfig['project_id'];
        $guzzleClient = new Client([
            'base_uri' => "https://platform.api.onesky.io/1/projects/$projectId/",
        ]);
        $handler = $guzzleClient->getConfig('handler');

        // Configure default query parameters with authentication.
        $handler->unshift(Middleware::mapRequest(function (RequestInterface $request) use ($oneSkyConfig) {
            $queryString = $request->getUri()->getQuery();
            parse_str($queryString, $query);
            $query['api_key'] = $oneSkyConfig['api_key'];
            $time = time();
            $query['timestamp'] = $time;
            $query['dev_hash'] = md5($time . $oneSkyConfig['secret_key']);
            return $request->withUri($request->getUri()->withQuery(http_build_query($query)));
        }));

        $this->guzzleClient = $guzzleClient;
    }

    public function getGuzzleClient(): Client
    {
        return $this->guzzleClient;
    }

    /**
     * Uploads character types to OneSky.
     */
    public function uploadCharacterTypes(array $characterTypesInput): void
    {
        $characterTypesToUpload = [];
        foreach ($characterTypesInput as $characterType) {
            $characterTypesToUpload[$characterType] = $characterType;
        }

        $this->getGuzzleClient()->request('POST', 'files', [
            RequestOptions::MULTIPART => [
                [
                    'name' => 'file',
                    'contents' => json_encode(['character_types' => $characterTypesToUpload]),
                    'filename' => 'character_types.json',
                ],
                [
                    'name' => 'file_format',
                    'contents' => 'HIERARCHICAL_JSON',
                ],
            ],
        ]);
    }
}
