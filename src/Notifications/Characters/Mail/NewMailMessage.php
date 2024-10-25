<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace Seat\Notifications\Notifications\Characters\Mail;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Str;
use Seat\Notifications\Notifications\AbstractMailNotification;

/**
 * Class NewMailMessage.
 *
 * @package Seat\Notifications\Notifications\Characters
 */
class NewMailMessage extends AbstractMailNotification
{
    /**
     * @var
     */
    private $message;

    /**
     * Create a new notification instance.
     *
     * @param  $message
     */
    public function __construct($message)
    {

        $this->message = $message;
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
            ->line('You have received a new EVEMail!')
            ->line(
                'Subject: ' . $this->message->subject . '. A snippet from the mail ' .
                'follows:'
            )
            ->line('"' .
                Str::limit(
                    str_replace('<br>', ' ', clean_ccp_html($this->message->body->body, '<br>')),
                    2000) .
                '"'
            )
            ->action('Read it on SeAT', route('seatcore::character.view.mail.timeline.read', [
                'message_id' => $this->message->mail_id,
            ]));
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
            'from' => $this->message->senderName,
            'subject' => $this->message->title,
            'sent_date' => $this->message->sentDate,
        ];
    }
}
