<?php

namespace App\Mail;


use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyAccountMail extends VerifyEmail
{
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }

        return (new MailMessage)
            ->greeting(trans('messages.greeting'))
            ->subject(trans('messages.verify.subject'))
            ->line(trans('messages.verify.info'))
            ->line(trans('messages.verify.login', ['login' => $notifiable->name]))
            ->line(trans('messages.verify.question', ['question' => $notifiable->Promt]))
            ->line(trans('messages.verify.answer', ['answer' => $notifiable->answer]))
            ->line(trans('messages.verify.reason'))
            ->action(
                trans('messages.verify.action'),
                $this->verificationUrl($notifiable)
            )
            ->line(trans('messages.verify.wrongRequest'))
            ->salutation(trans('messages.salutation', ['team' => env('APP_NAME')]));
    }
}