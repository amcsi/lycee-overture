<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console;

use amcsi\LyceeOverture\Console\Commands\BuildLackeyCommand;
use amcsi\LyceeOverture\Console\Commands\ImportAllCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $logPath = storage_path(sprintf('logs/schedule-%s.log', date('Y-m-d')));
        $schedule->command(ImportAllCommand::COMMAND . ' --translations --images --no-cache')
            ->dailyAt('20:00')
            ->sendOutputTo($logPath);
        $schedule->command(BuildLackeyCommand::COMMAND)
            ->dailyAt('08:00')
            ->sendOutputTo($logPath);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
