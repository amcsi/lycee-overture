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

    protected $signature = self::COMMAND;
    protected $description = 'Uploads images to cloud service. Images must have been downloaded.';
    private $card;
    private $cardImage;
    private $cardImageCloudinaryUploader;

    public function __construct(
        Card $card,
        CardImage $cardImage,
        CardImageCloudinaryUploader $cardImageCloudinaryUploader
    ) {
        parent::__construct();
        $this->card = $card;
        $this->cardImage = $cardImage;
        $this->cardImageCloudinaryUploader = $cardImageCloudinaryUploader;
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
        /** @var CardImage[] $cardImages */
        $cardImages = $this->cardImage->all()->keyBy('card_id');
        foreach ($cards as $card) {
            try {
                $id = $card->id;
                $this->output->write("$id... ");
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
