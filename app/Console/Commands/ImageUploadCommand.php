<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\Models\Card;
use amcsi\LyceeOverture\Card\Image\CardImageCloudinaryUploader;
use amcsi\LyceeOverture\Models\CardImage;
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

        $newOnly = $this->option('new-only');

        // LO-0001, LO-0001A etc
        $cardVariants = collect();
        foreach ($this->card->cursor() as $cardVariant) {
            foreach (explode(',', $cardVariant->variants) as $variant) {
                $cardVariants[] = "$cardVariant->id$variant";
            }
        }

        /** @var CardImage[] $cardImages */
        $cardImages = $this->cardImage->all()->keyBy('card_id');
        if ($newOnly) {
            // Filter out cards whose images are already in the card images DB.
            $cardVariants = $cardVariants->reject(function ($cardVariant) use ($cardImages) {
                return isset($cardImages[$cardVariant]);
            });
        }

        $total = $cardVariants->count();
        $this->output->text("$total card images to upload.");

        $i = 0;

        /**
         * Gets the text that should be outputted to report progress.
         * Includes the index of card too.
         *
         * @param string $cardVariant
         * @return string
         */
        $getOutputText = function ($cardVariant) use (&$i, $total): string {
            return sprintf('(%04d/%04d) % -10s... ', ++$i, $total, $cardVariant);
        };

        foreach ($cardVariants as $cardVariant) {
            try {
                if ($newOnly && isset($cardImages[$cardVariant])) {
                    // Only new card option was provided, and this is an old card.
                    continue;
                }
                $this->output->write($getOutputText($cardVariant));
                $message = $this->cardImageCloudinaryUploader->upload($cardVariant, $cardImages);
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
