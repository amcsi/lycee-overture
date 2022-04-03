<?php
declare(strict_types=1);


namespace amcsi\LyceeOverture\Import;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\StreamWrapper;
use League\Csv\Reader;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\Console\Style\SymfonyStyle;
use function GuzzleHttp\Psr7\str;

/**
 * Downloads images of the cards.
 */
class ImageDownloader
{
    const HEADER_IF_MODIFIED_SINCE = 'If-Modified-Since';
    const HTTP_DATE_FORMAT = 'D, d M Y H:i:s \G\M\T';

    public function __construct(private Client $client, private FilesystemOperator $filesystem)
    {
    }

    public function downloadImages(SymfonyStyle $output, bool $newOnly = false)
    {
        $reader = Reader::createFromPath(storage_path(ImportConstants::CSV_PATH));
        // Iterator with the card ids pointing to the images (LO-0001, LO-0001-A, LO-0002 etc).
        $cardIdIterator = $reader->fetchColumn(0);

        $requests = [];
        $total = 0;
        foreach ($cardIdIterator as $cardId) {
            if ($newOnly && $this->filesystem->has(self::getLocalImagePathForCardId($cardId))) {
                continue;
            }
            $requests[$cardId] = $this->createCardIdRequest($cardId);
            ++$total;
        }

        // Newline after progress bar.
        $output->writeln('');

        $i = 0;

        /**
         * Gets the text that should be outputted to report progress.
         * Includes the index of card too.
         *
         * @param string $cardId
         * @return string
         */
        $getOutputText = function ($cardId) use (&$i, $total): string {
            return sprintf('(%04d/%04d) % -10s... ', ++$i, $total, $cardId);
        };

        // http://docs.guzzlephp.org/en/stable/quickstart.html#concurrent-requests
        $pool = new Pool($this->client, $requests, [
            'concurrency' => 50,
            'fulfilled' => function (Response $response, $cardId) use ($output, $getOutputText) {
                $statusCode = $response->getStatusCode();
                $output->write($getOutputText($cardId));
                $output->writeln(sprintf("[$statusCode]"));
                if ($statusCode === 200) {
                    // Copy body stream of downloaded card image to its file.
                    $file = self::getLocalImagePathForCardId($cardId);
                    $this->filesystem->writeStream($file, StreamWrapper::getResource($response->getBody()));
                } elseif ($response->getStatusCode() === 304) {
                    // Not Modified; no need to download.
                } else {
                    // Other status code = error.
                    $output->error(str($response));
                }
            },
            'rejected' => function ($reason, $cardId) use ($output, $getOutputText) {
                $output->write($getOutputText($cardId));
                if ($reason instanceof RequestException && ($response = $reason->getResponse())) {
                    // Bad response.
                    $statusCode = $response->getStatusCode();
                    $output->writeln(sprintf("[<fg=red>$statusCode</>]"));
                    return;
                }
                // No response.
                $output->error($reason);
            },
        ]);

        // Initiate the transfers and create a promise
        $promise = $pool->promise();

        // Force the pool of requests to complete.
        $promise->wait();
    }

    public function createCardIdRequest(string $cardId): Request
    {
        $filename = "$cardId.png";
        $headers = [];
        if ($this->filesystem->has($filename)) {
            // To get 304 when not needing to redownload an image.
            /** @noinspection PhpUnhandledExceptionInspection */
            $headers['If-Modified-Since'] = date(self::HTTP_DATE_FORMAT, $this->filesystem->lastModified($filename));
        }
        $imageUrl = str_replace('{id}', $cardId, ImportConstants::IMAGE_URL);
        return new Request('GET', $imageUrl, $headers);
    }

    public static function getLocalImagePathForCardId(string $cardId): string
    {
        return str_replace('{id}', $cardId, ImportConstants::IMAGE_FILENAME);
    }
}
