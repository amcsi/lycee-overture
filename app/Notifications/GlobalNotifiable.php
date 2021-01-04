<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Notifications;

use Illuminate\Notifications\Notifiable;

/**
 * Notifies all people on Discord.
 */
class GlobalNotifiable
{
    use Notifiable;

    public function routeNotificationForDiscord(): string
    {
        return config('services.discord.webhookUrl');
    }
}
