<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\models\Participant;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

        $schedule->command('app:send-daily-reminder')->daily();
        // $schedule->command('app:send-daily-reminder')->dailyAt('13:00');
        $schedule->command('app:send-admin-alert')->daily();

        // $schedule->command('app:send-daily-reminder')->everyMinute();
        // $schedule->command('app:send-daily-reminder')->everyFiveSeconds();

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
