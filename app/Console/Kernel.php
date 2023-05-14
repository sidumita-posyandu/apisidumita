<?php

namespace App\Console;

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
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->call(function () {

        //     $dates=[
        //       '2021-06-12 13:00' => [
        //          'commander'=>'Kim Young Un',
        //          'country'  => 'America'
        //        ],
        //        '2021-06-15 18:00' => [
        //          'commander'=>'Osama Bin Laden\'s clone',
        //          'country'  => 'America'
        //        ],
        //        '2021-06-15 06:00' => [
        //          'commander'=>'Adolf Hitler\'s clone',
        //          'country'  => 'Israel'
        //        ],
        //     ];  
          
        //     $date = Carbon::now()->format('Y-m-d H:i:s');
            
        //     if(isset($dates[$date])){
        //        $params=$dates[$date];
        //        Artisan::call("nuke:country \"{$params['commander']}\" {$params['country']}");
        //     }
        //   })->cron('*****');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
