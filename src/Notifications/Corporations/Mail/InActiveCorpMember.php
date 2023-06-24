<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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

namespace Seat\Notifications\Notifications\Corporations\Mail;

use Illuminate\Notifications\Messages\MailMessage;
use Seat\Notifications\Notifications\AbstractMailNotification;

/**
 * Class InActiveCorpMember.
 *
 * @package Seat\Notifications\Notifications\Corporations
 */
class InActiveCorpMember extends AbstractMailNotification
{
    /**
     * @var \Seat\Eveapi\Models\Corporation\CorporationMemberTracking
     */
    private $member;

    /**
     * Create a new notification instance.
     *
     * @param  $member  \Seat\Eveapi\Models\Corporation\CorporationMemberTracking
     */
    public function __construct($member)
    {

        $this->member = $member;
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
            ->error()
            ->greeting('Heads up!')
            ->subject('Inactive Member Notification')
            ->line(
                $this->member->character->name . ' logged off more than 3 months ago at ' .
                $this->member->logoff_date . '.'
            )
            ->action('View Corporation Tracking', route('corporation.view.tracking', [
                'corporation' => $this->member->corporation_id,
            ]))
            ->line(
                'Last seen at ' . $this->member->location . ' in a ' .
                $this->member->ship->typeName
            );
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
            'name'        => $this->member->character->name,
            'last_logoff' => $this->member->logoff_date,
            'location'    => $this->member->location,
            'ship'        => $this->member->ship->typeName,
        ];
    }
}
