<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\GeneratePayroll::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Auto-generate payroll on the 1st of every month for the previous month
        $schedule->command('payroll:generate')->monthlyOn(1, '09:00');
        
        // Optional: Auto-pay on the 15th of every month
        // $schedule->command('payroll:generate --auto-pay')->monthlyOn(15, '09:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}