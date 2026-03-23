<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DailyEntryReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $internshipName,
        public int $daysUntilDeadline
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $urgency = $this->daysUntilDeadline <= 2 ? 'urgent' : 'reminder';
        $subject = $urgency === 'urgent' 
            ? 'Urgent: Daily Entry Due Soon' 
            : 'Reminder: Submit Your Daily Entry';

        return (new MailMessage)
            ->subject($subject)
            ->line("Don't forget to submit your daily entry for '{$this->internshipName}'.")
            ->line("You have {$this->daysUntilDeadline} day(s) remaining to submit.")
            ->action('Submit Daily Entry', route('internships.index'))
            ->line('Timely submissions help track your progress effectively.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'daily_entry_reminder',
            'title' => 'Daily Entry Reminder',
            'message' => "Submit your daily entry for '{$this->internshipName}'. {$this->daysUntilDeadline} day(s) remaining.",
            'internship_name' => $this->internshipName,
            'days_until_deadline' => $this->daysUntilDeadline,
            'icon' => 'reminder',
        ];
    }
}
