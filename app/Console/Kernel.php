<?php

namespace App\Console;

use App\Mail\ToDoReminder;
use App\Models\ToDo;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function(){
           $toDos = ToDo::whereDate('end_date',Carbon::today()->toDateString())->get();
           foreach($toDos as $toDo){
            Mail::to($toDo->toDoList->user->email)->send(new ToDoReminder($toDo));
           }
        })->dailyAt('08:00');

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
