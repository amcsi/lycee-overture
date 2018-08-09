<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n;

use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;

class OneSkyClient
{
    public const CHARACTER_TYPES = 'character_types';
    public const NAMES = 'names';
    public const ABILITY_NAMES = 'ability_names';

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
    public function uploadNamesAndTypes(array $characterTypesInput, array $namesInput, array $abilityNamesInput): void
    {
        $this->_uploadNameOrType(self::CHARACTER_TYPES, $characterTypesInput);
        $this->_uploadNameOrType(self::NAMES, $namesInput);
        $this->_uploadNameOrType(self::ABILITY_NAMES, $abilityNamesInput);
    }

    private function _uploadNameOrType(string $which, array $input): void
    {
        switch ($which) {
            case self::CHARACTER_TYPES:
            case self::NAMES:
            case self::ABILITY_NAMES:
                $key = $which;
                $oneSkyFileName = "$key.json";
                break;
            default:
                throw new \InvalidArgumentException("Bad which: $which");
        }

        $this->getGuzzleClient()->request(
            'POST',
            'files',
            [
                RequestOptions::MULTIPART => [
                    [
                        'name' => 'file',
                        'contents' => json_encode(
                            [
                                $key => $input,
                            ]
                        ),
                        'filename' => $oneSkyFileName,
                    ],
                    [
                        'name' => 'file_format',
                        'contents' => 'HIERARCHICAL_JSON',
                    ],
                    // Deprecate strings not present.
                    [
                        'name' => 'is_keeping_all_strings',
                        'contents' => 'false',
                    ],
                ],
            ]
        );
    }

    public function downloadTranslations(): array
    {
        $translations = [];
        foreach ([self::CHARACTER_TYPES, self::NAMES, self::ABILITY_NAMES] as $key) {
            $oneSkyFilename = "$key.json";
            $result = $this->getGuzzleClient()->get(
                'translations/multilingual',
                [
                    'query' => [
                        'source_file_name' => $oneSkyFilename,
                        'file_format' => 'I18NEXT_MULTILINGUAL_JSON',
                    ],
                ]
            );
            $result = json_decode($result->getBody()->__toString(), true);
            /** @noinspection SlowArrayOperationsInLoopInspection */
            $translations = array_merge_recursive($translations, $result);
        }
        return $translations;
    }
}
