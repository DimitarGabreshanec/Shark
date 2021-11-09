<?php

namespace App\Notifications\Store;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Hash;

class StorePasswordChangedNotification extends Notification
{
    private $p_email;
    private $p_password;

    /**
     * Create a new rule instance.
     *
     * @param $email
     * @param $password
     */
    public function __construct($email, $password)
    {
        $this->p_email = $email;
        $this->p_password = $password;
    }

    public static $toMailCallback;

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }

        return (new MailMessage)
            ->subject(Lang::get('message.Mail.StorePasswordUpdated.Title'))
            ->line(Lang::get('message.Mail.StorePasswordUpdated.Line1'))
            ->line(Lang::get('message.Mail.StorePasswordUpdated.Line2'))
            ->line($this->p_password)
            ->replyTo($this->p_email);
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify', Carbon::now()->addMinutes(60 * 24), ['id' => $notifiable->getKey()]
        );
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
