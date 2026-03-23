<?php

namespace App\Notifications;

use App\Models\InternshipApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public InternshipApplication $application,
        public string $oldStatus,
        public string $newStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Internship Application Status Updated')
            ->line("Your application for '{$this->application->internship->name}' has been updated.")
            ->line("Status changed from {$this->oldStatus} to {$this->newStatus}.")
            ->action('View Application', route('applications.show', ['internship' => $this->application->internship_id, 'application' => $this->application->id]))
            ->line('Thank you for using our platform!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'application_status_changed',
            'title' => 'Application Status Updated',
            'message' => "Your application for '{$this->application->internship->name}' is now {$this->newStatus}.",
            'application_id' => $this->application->id,
            'internship_id' => $this->application->internship_id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'icon' => 'status',
        ];
    }
}
