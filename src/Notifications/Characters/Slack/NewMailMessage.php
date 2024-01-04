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

namespace Seat\Notifications\Notifications\Characters\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Support\Str;
use Seat\Notifications\Notifications\AbstractSlackNotification;

/**
 * Class NewMailMessage.
 *
 * @package Seat\Notifications\Notifications\Characters
 */
class NewMailMessage extends AbstractSlackNotification
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
     * Get the Slack representation of the notification.
     *
     * @param  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {

        return (new SlackMessage)
            ->content('New EVEMail Received!')
            ->from('SeAT Personal Agent')
            ->attachment(function ($attachment) {

                $attachment->title('Read on SeAT', route('seatcore::character.view.mail.timeline.read', [
                    'message_id' => $this->message->mail_id,
                ]))->fields([
                    'Subject' => $this->message->subject,
                    'Sent Date' => $this->message->timestamp,
                    'Message' => Str::limit(
                        str_replace('<br>', ' ', clean_ccp_html($this->message->body->body, '<br>')),
                        2000),
                ]);
            });
    }
}
