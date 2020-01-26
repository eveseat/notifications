<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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

namespace Seat\Notifications\Notifications\Characters\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Support\Str;
use Seat\Notifications\Notifications\AbstractNotification;

/**
 * Class NewMailMessage.
 *
 * @package Seat\Notifications\Notifications\Characters
 */
class NewMailMessage extends AbstractNotification
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
     * @return array
     */
    public function via($notifiable)
    {

        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {

        return (new SlackMessage)
            ->content('New EVEMail Received!')
            ->attachment(function ($attachment) {

                $attachment->title('Read on SeAT', route('character.view.mail.timeline.read', [
                    'message_id' => $this->message->mail_id,
                ]))->fields([
                    'Subject'   => $this->message->subject,
                    'Sent Date' => $this->message->timestamp,
                    'Message'   => Str::limit(
                        str_replace('<br>', ' ', clean_ccp_html($this->message->body->body, '<br>')),
                        2000),
                ]);
            });
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {

        return [
            'from'      => $this->message->senderName,
            'subject'   => $this->message->title,
            'sent_date' => $this->message->sentDate,
        ];
    }
}
