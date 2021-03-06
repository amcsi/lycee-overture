<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notification;
use SnoerenDevelopment\DiscordWebhook\DiscordMessage;
use SnoerenDevelopment\DiscordWebhook\DiscordWebhookChannel;

class NewSuggestionsNotification extends Notification
{
    use Queueable;

    public function __construct(private Collection $suggestions)
    {
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(): array
    {
        return [DiscordWebhookChannel::class];
    }

    public function toDiscord(): DiscordMessage
    {
        $cardIdsCommaSeparated = $this->suggestions->pluck('card_id')->join(',');

        return DiscordMessage::create()
            ->content(sprintf(
                "There's **%d** new translation suggestion(s) since yesterday.\n\n%s/cards?cardId=%s",
                count($this->suggestions),
                config('app.url'),
                $cardIdsCommaSeparated,
            ));
    }
}
