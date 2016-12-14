<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace Seat\Notifications\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

/**
 * Class NewMailMessage
 * @package Seat\Notifications\Notifications
 */
class NewMailMessage extends Notification
{

    /**
     * @var
     */
    private $message;

    /**
     * Create a new notification instance.
     *
     * @param $message
     */
    public function __construct($message)
    {

        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {

        return $notifiable->notificationChannels();
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        return (new MailMessage)
            ->line('You have received a new EVEMail!')
            ->line(
                'The message is from ' . $this->message->senderName . ' with ' .
                'subject: ' . $this->message->title . '. A snippet from the mail ' .
                'follows:'
            )
            ->line('"' .
                str_limit(
                    str_replace('<br>', ' ', clean_ccp_html($this->message->body->body, '<br>')),
                    250) .
                '"'
            )
            ->action('Read it on SeAT', route('character.view.mail.timeline.read', [
                'message_id' => $this->message->messageID
            ]));
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param $notifiable
     *
     * @return $this
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->content('New EVEMail Received!')
            ->attachment(function ($attachment) {

                $attachment->title('Read on SeAT', route('character.view.mail.timeline.read', [
                    'message_id' => $this->message->messageID
                ]))->fields([
                    'From'      => $this->message->senderName,
                    'Subject'   => $this->message->title,
                    'Sent Date' => $this->message->sentDate,
                    'Message'   => str_limit(
                        str_replace('<br>', ' ', clean_ccp_html($this->message->body->body, '<br>')),
                        250)
                ]);
            });
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {

        return [
            'from'      => $this->message->senderName,
            'subject'   => $this->message->title,
            'sent_date' => $this->message->sentDate
        ];
    }
}
