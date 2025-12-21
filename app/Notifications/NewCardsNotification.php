<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Notifications;

use amcsi\LyceeOverture\Models\Card;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notification;
use SnoerenDevelopment\DiscordWebhook\DiscordMessage;
use SnoerenDevelopment\DiscordWebhook\DiscordWebhookChannel;

class NewCardsNotification extends Notification
{
    public function __construct(
        /** @var Collection|Card[] */
        private $newCards
    ) {
    }

    public function via(): array
    {
        return [DiscordWebhookChannel::class];
    }

    public function toDiscord(): DiscordMessage
    {
        $cardIdsCommaSeparated = $this->newCards->pluck('id')->sort()->join(', ');

        return DiscordMessage::create()
            ->content(sprintf(
                "%d new card(s) imported!\n\n%s\n\n%s/cards",
                count($this->newCards),
                $cardIdsCommaSeparated,
                config('app.url'),
            ));
    }
}
