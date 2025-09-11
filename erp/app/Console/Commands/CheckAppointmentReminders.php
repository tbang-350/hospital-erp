<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:check-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for upcoming appointments and send reminder notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for upcoming appointments...');
        
        // Get appointments for tomorrow
        $tomorrowAppointments = Appointment::whereDate('scheduled_at', Carbon::tomorrow())
            ->with('patient')
            ->get();
            
        foreach ($tomorrowAppointments as $appointment) {
            NotificationService::createAppointmentReminderNotification($appointment);
        }
        
        $this->info('Found ' . $tomorrowAppointments->count() . ' appointments for tomorrow.');
        
        // Get appointments for today that are starting soon (within 2 hours)
        $todayAppointments = Appointment::whereBetween('scheduled_at', [
                Carbon::now(),
                Carbon::now()->addHours(2)
            ])
            ->with('patient')
            ->get();
            
        foreach ($todayAppointments as $appointment) {
            NotificationService::createAppointmentStartingSoonNotification($appointment);
        }
        
        $this->info('Found ' . $todayAppointments->count() . ' appointments starting soon today.');
        
        $this->info('Appointment reminders check completed successfully!');
        
        return Command::SUCCESS;
    }
}
