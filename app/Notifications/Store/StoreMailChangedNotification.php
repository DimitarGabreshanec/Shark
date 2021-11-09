<?php

namespace App\Notifications\Store;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Hash;

class StoreMailChangedNotification extends Notification
{
    private $p_old_email;
    private $p_email;

    /**
     * Create a new rule instance.
     *
     * @param $old_email
     * @param $email
     */
    public function __construct($old_email, $email)
    {
        $this->p_old_email = $old_email;
        $this->p_email = $email;
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
            ->subject(Lang::get('message.Mail.StoreMailUpdated.Title'))
            ->line(Lang::get('message.Mail.StoreMailUpdated.Line1'))
            ->line(Lang::get('message.Mail.StoreMailUpdated.Line2'))
            ->line($this->p_old_email)
            ->line(Lang::get('message.Mail.StoreMailUpdated.Line3'))
            ->line($this->p_email)
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
