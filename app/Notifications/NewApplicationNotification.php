<?php

namespace App\Notifications;

use App\Models\InternshipApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewApplicationNotification extends Notification
{
    use Queueable;

    public function __construct(
        public InternshipApplication $application
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Internship Application Received')
            ->line("You have a new application for '{$this->application->internship->name}'.")
            ->line("Student: {$this->application->student->name}")
            ->action('Review Application', route('applications.show', ['internship' => $this->application->internship_id, 'application' => $this->application->id]))
            ->line('Please review the application at your earliest convenience.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_application',
            'title' => 'New Application Received',
            'message' => "{$this->application->student->name} applied for '{$this->application->internship->name}'.",
            'application_id' => $this->application->id,
            'internship_id' => $this->application->internship_id,
            'student_id' => $this->application->user_id,
            'student_name' => $this->application->student->name,
            'icon' => 'application',
        ];
    }
}
