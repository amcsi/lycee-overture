<?php

namespace amcsi\LyceeOverture\Providers;

use amcsi\LyceeOverture\Api\QueryLogToResponseAdder;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'amcsi\LyceeOverture\Events\Event' => [
            'amcsi\LyceeOverture\Listeners\EventListener',
        ],
        'Dingo\Api\Event\ResponseWasMorphed' => [
            QueryLogToResponseAdder::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
