<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\Notifications\GlobalNotifiable;
use amcsi\LyceeOverture\Notifications\NewSuggestionsNotification;
use amcsi\LyceeOverture\Models\Suggestion;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class NewSuggestionsNotificationCommand extends Command
{
    public const COMMAND = 'lycee:new-suggestions-notification';

    protected $description = 'Notifies if there has been any new translation suggestions since yesterday.';
    protected $signature = self::COMMAND;

    public function handle()
    {
        $newSuggestions = Suggestion::query()->where('created_at', '>=', Carbon::now()->subDay())->get();
        $newSuggestionsCount = $newSuggestions->count();
        if (!$newSuggestionsCount) {
            $this->info('No new translation suggestions since yesterday.');

            return 0;
        }

        app(GlobalNotifiable::class)->notify(new NewSuggestionsNotification($newSuggestions));

        return 0;
    }
}
