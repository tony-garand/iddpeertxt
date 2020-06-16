<?php

namespace peertxt\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Log;

class ContactImportNotify extends Notification
{
    use Queueable;

    public $validContacts;

    /**
     * Create a new notification instance.
     *
     * @param $validContacts
     */
    public function __construct($validContacts)
    {
        $this->validContacts = $validContacts;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Contact import finished')
            ->line(sprintf('Your import of contact data has finished; imported %s contacts.', $this->validContacts));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
