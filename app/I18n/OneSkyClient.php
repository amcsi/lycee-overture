<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n;

use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;

class OneSkyClient
{
    private $guzzleClient;

    const CHARACTER_TYPES_JSON = 'character_types.json';

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
                    'filename' => self::CHARACTER_TYPES_JSON,
                ],
                [
                    'name' => 'file_format',
                    'contents' => 'HIERARCHICAL_JSON',
                ],
            ],
        ]);
    }

    public function downloadTranslations(): array
    {
        $result = $this->getGuzzleClient()->get('translations/multilingual', [
            'query' => [
                'source_file_name' => self::CHARACTER_TYPES_JSON,
                'file_format' => 'I18NEXT_MULTILINGUAL_JSON',
            ],
        ]);
        return json_decode($result->getBody()->__toString(), true);
    }
}
