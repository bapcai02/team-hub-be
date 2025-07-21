<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class WelcomeUserNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('messages.register_mail_subject'))
            ->greeting(__('messages.register_mail_greeting', ['name' => $notifiable->name]))
            ->line(__('messages.register_mail_body'))
            ->action(__('messages.register_mail_action'), url('/'))
            ->line(__('messages.register_mail_thanks'));
    }
}
