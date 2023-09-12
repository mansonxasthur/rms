<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        $this->loadModuleCommands();

        require base_path('routes/console.php');
    }

    protected function loadModuleCommands()
    {
        foreach (app_modules() as $module) {
            $moduleCommands = get_module_path($module, ['Core', 'Commands']);
            if (file_exists($moduleCommands)) {
                $this->load($moduleCommands);
            }
        }
    }
}
