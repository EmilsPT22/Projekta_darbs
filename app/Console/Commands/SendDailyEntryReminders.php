<?php

namespace App\Console\Commands;

use App\Models\DailyEntry;
use App\Models\Internship;
use App\Notifications\DailyEntryReminderNotification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendDailyEntryReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send-daily-entries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily entry reminders to students who haven\'t submitted entries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending daily entry reminders...');

        // Get all active internships
        $internships = Internship::all();
        $reminderSent = 0;

        foreach ($internships as $internship) {
            // Get students in this internship
            $students = $internship->students;

            foreach ($students as $student) {
                // Only send to users with 'student' role
                if (!$student->hasRole('student')) {
                    continue;
                }

                // Check if student has submitted an entry today
                $today = Carbon::today();
                $hasEntryToday = DailyEntry::where('user_id', $student->id)
                    ->whereDate('date', $today)
                    ->exists();

                if (!$hasEntryToday) {
                    // Check if it's a weekday (Monday-Friday)
                    if ($today->isWeekday()) {
                        // Calculate days until end of week
                        $daysUntilFriday = 5 - $today->dayOfWeek;

                        $student->notify(
                            new DailyEntryReminderNotification(
                                $internship->title,
                                max(1, $daysUntilFriday)
                            )
                        );
                        $reminderSent++;
                        $this->line("✓ Sent reminder to {$student->name} for {$internship->title}");
                    }
                }
            }
        }

        $this->info("Sent {$reminderSent} reminder(s) successfully.");
        return Command::SUCCESS;
    }
}
