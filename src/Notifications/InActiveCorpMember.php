<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018  Leon Jacobs
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

namespace Seat\Notifications\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class InActiveCorpMember extends Notification
{
    /**
     * @var
     */
    private $member;

    /**
     * Create a new notification instance.
     *
     * @param $member
     */
    public function __construct($member)
    {

        $this->member = $member;
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
            ->error()
            ->greeting('Heads up!')
            ->subject('Inactive Member Notification')
            ->line(
                $this->member->character_id . ' logged off more than 3 months ago at ' .
                $this->member->logoff_date . '.'
            )
            ->action(
                'View Corporation Tracking', route('corporation.view.tracking', [
                'corporation_id' => $this->member->corporation_id,
            ]))
            ->line(
                'Last seen at ' . $this->member->location_id . ' in a ' .
                $this->member->ship_type_id
            );
    }

    /**
     * @param $notifiable
     *
     * @return $this
     */
    public function toSlack($notifiable)
    {

        return (new SlackMessage)
            ->error()
            ->content($this->member->character_id . ' has not logged in for some time!')
            ->attachment(function ($attachment) {

                $attachment->title('Tracking Details', route('corporation.view.tracking', [
                    'corporation_id' => $this->member->corporation_id,
                ]))->fields([
                    'Name'        => $this->member->character_id,
                    'Last Logoff' => $this->member->logoff_date,
                    'Location'    => $this->member->location_id,
                    'Ship'        => $this->member->ship_type_id,
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
            'name'        => $this->member->character_id,
            'last_logoff' => $this->member->logoff_date,
            'location'    => $this->member->location_id,
            'ship'        => $this->member->ship_type_id,
        ];
    }
}
