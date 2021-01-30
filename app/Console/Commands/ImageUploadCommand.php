<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\Card\Image\CardImageCloudinaryUploader;
use amcsi\LyceeOverture\CardImage;
use amcsi\LyceeOverture\Debug\Profiling;
use Illuminate\Console\Command;
use Symfony\Component\Stopwatch\Stopwatch;

class ImageUploadCommand extends Command
{
    public const COMMAND = 'lycee:upload-images';

    protected $signature = self::COMMAND .
    ' {--new-only : Only upload images that haven\'t been uploaded yet according to the local DB.}';
    protected $description = 'Uploads images to cloud service. Images must have been downloaded.';

    public function __construct(
        private Card $card,
        private CardImage $cardImage,
        private CardImageCloudinaryUploader $cardImageCloudinaryUploader
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        $stopwatch = new Stopwatch();
        $stopwatchEvent = $stopwatch->start('upload-images');

        $this->output->text('Started uploading images to cloud service...');

        $cards = $this->card->all();
        $newOnly = $this->option('new-only');
        /** @var CardImage[] $cardImages */
        $cardImages = $this->cardImage->all()->keyBy('card_id');
        if ($newOnly) {
            // Filter out cards whose images are already in the card images DB.
            $cards = $cards->reject(function (Card $card) use ($cardImages) {
                return isset($cardImages[$card->id]);
            });
        }

        $total = $cards->count();
        $this->output->text("$total card images to upload.");

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

        foreach ($cards as $card) {
            try {
                $id = $card->id;
                if ($newOnly && isset($cardImages[$id])) {
                    // Only new card option was provided, and this is an old card.
                    continue;
                }
                $this->output->write($getOutputText($id));
                $message = $this->cardImageCloudinaryUploader->upload($card, $cardImages);
                $this->output->writeln($message);
            } catch (\Exception $e) {
                $this->output->error($e);
            }
        }

        $this->output->text(
            "Finished uploading images to cloud service in " . Profiling::stopwatchToHuman($stopwatchEvent->stop())
        );
    }
}
