<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class TaskNotification extends Notification
{
    use Queueable;

    private $taskDetails;

    /**
     * Create a new notification instance.
     *
     * @param array $taskDetails
     */
    public function __construct(array $taskDetails)
    {
        $this->taskDetails = $taskDetails;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database']; // You can add 'mail' or other channels here as needed
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'task_id' => $this->taskDetails['task_id'],
            'message' => $this->taskDetails['message'],
            'created_at' => now(),
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('You have a new task notification.')
                    ->action('View Task', url('/tasks/'.$this->taskDetails['task_id']))
                    ->line('Thank you for using our application!');
    }
}
