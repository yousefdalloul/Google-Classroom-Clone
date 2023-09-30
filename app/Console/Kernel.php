<?php

namespace App\Console;

use App\Jobs\SendNotificationToExpireSubscriptions;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * @method job(SendNotificationToExpireSubscriptions $param)
 */
class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('model:prune')->dailyAt('06:30'); // Change to '06:30' for 6:30 AM
        $schedule->job(new SendNotificationToExpireSubscriptions())->dailyAt('10:41');
    }



    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
