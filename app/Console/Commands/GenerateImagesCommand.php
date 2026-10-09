<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\Image\Local\ImageSources;
use amcsi\LyceeOverture\Image\Local\ImageVariantGenerator;
use Illuminate\Console\Command;
use Illuminate\Process\Pool;
use Illuminate\Support\Facades\Process;
use Throwable;

class GenerateImagesCommand extends Command
{
    public const COMMAND = 'lycee:generate-images';

    protected $signature = self::COMMAND .
    ' {--static : Only generate the images in resources/image-sources}' .
    ' {--force : Regenerate variants even if they are up to date}' .
    ' {--workers=2 : Number of processes to split the work between}' .
    ' {--shard= : Only process every Nth image starting from the Ith, as "I/N" (used by the workers)}';
    protected $description = 'Generates the local image variants that are served instead of Cloudinary.';

    public function handle(ImageSources $imageSources, ImageVariantGenerator $generator): int
    {
        $shard = $this->option('shard');
        $workers = max(1, (int) $this->option('workers'));
        if ($shard === null && $workers > 1) {
            return $this->runWorkers($workers);
        }
        [$index, $count] = $shard === null ? [0, 1] : array_map('intval', explode('/', $shard));

        $sources = $imageSources->statics();
        if (!$this->option('static')) {
            $sources += $imageSources->cards();
        }

        $position = 0;
        $written = 0;
        $failed = 0;
        foreach ($sources as $path => $file) {
            if ($position++ % $count !== $index) {
                continue;
            }
            try {
                $written += $generator->generate($path, $file, (bool) $this->option('force'));
            } catch (Throwable $exception) {
                ++$failed;
                $this->warn("$path: {$exception->getMessage()}");
            }
        }

        $this->info("Wrote $written image variants" . ($failed ? "; $failed images failed." : '.'));
        return $failed ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Runs shards of this command in child processes. Not Laravel's Concurrency facade: its process driver times
     * out after 60 seconds, while a full backfill takes minutes.
     */
    private function runWorkers(int $workers): int
    {
        $flags = array_keys(array_filter(['--static' => $this->option('static'), '--force' => $this->option('force')]));
        $results = Process::pool(function (Pool $pool) use ($workers, $flags) {
            for ($index = 0; $index < $workers; ++$index) {
                $pool->as("worker $index")
                    ->path(base_path())
                    ->forever()
                    ->command([PHP_BINARY, 'artisan', self::COMMAND, "--shard=$index/$workers", ...$flags]);
            }
        })->start(fn(string $type, string $output, string $key) => $this->output->write("[$key] $output"))->wait();

        return $results->collect()->contains(fn($result) => $result->failed()) ? self::FAILURE : self::SUCCESS;
    }
}
