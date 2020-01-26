<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
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

namespace Seat\Notifications\Notifications\Corporations;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Notifications\Notifications\AbstractNotification;

/**
 * Class InActiveCorpMember.
 *
 * @package Seat\Notifications\Notifications\Corporations
 */
class InActiveCorpMember extends AbstractNotification
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
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        return (new MailMessage)
            ->error()
            ->greeting('Heads up!')
            ->subject('Inactive Member Notification')
            ->line(
                $this->member->name . ' logged off more than 3 months ago at ' .
                $this->member->logoffDateTime . '.'
            )
            ->action('View Corporation Tracking', route('corporation.view.tracking', [
                'corporation_id' => $this->member->corporationID,
            ]))
            ->line(
                'Last seen at ' . $this->member->location . ' in a ' .
                $this->member->shipType
            );
    }

    /**
     * @param $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {

        return (new SlackMessage)
            ->error()
            ->content('A member has not logged in for some time! Check corp tracking.')
            ->attachment(function ($attachment) {

                $attachment->title('Tracking Details', route('corporation.view.tracking', [
                    'corporation_id' => $this->member->corporation_id,
                ]))->fields([
                    'Last Logoff' => $this->member->logoff_date,
                    'Ship'        => $this->member->type->typeName,
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
            'name'        => $this->member->name,
            'last_logoff' => $this->member->logoffDateTime,
            'location'    => $this->member->location,
            'ship'        => $this->member->shipType,
        ];
    }
}
