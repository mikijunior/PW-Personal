<?php

namespace App\Mail;


use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordMail extends ResetPassword
{
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return (new MailMessage)
            ->greeting(trans('messages.greeting'))
            ->subject(trans('messages.reset.subject'))
            ->line(trans('messages.reset.reason'))
            ->action(trans('messages.reset.action'), url(config('app.url').route('password.reset', $this->token, false)))
            ->line(trans('messages.reset.expire', ['count' => config('auth.passwords.users.expire')]))
            ->line(trans('messages.reset.wrongRequest'))
            ->greeting(trans('messages.salutation', ['team' => config('app.name')]));
    }
}