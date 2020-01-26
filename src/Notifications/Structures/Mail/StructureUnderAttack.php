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

namespace Seat\Notifications\Notifications\Structures\Mail;

use Illuminate\Notifications\Messages\MailMessage;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Notifications\Notifications\AbstractNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class StructureUnderAttack.
 *
 * @package Seat\Notifications\Notifications\Structures
 */
class StructureUnderAttack extends AbstractNotification
{
    use NotificationTools;

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * StructureUnderAttack constructor.
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @param $notifiable
     * @return mixed
     */
    public function via($notifiable)
    {

        return ['mail'];
    }

    /**
     * @param $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $system = MapDenormalize::find($this->notification->text['solarsystemID']);

        return (new MailMessage)
            ->subject('Structure Under Attack Notification')
            ->line('A structure is under attack!')
            ->line(
                sprintf('Citadel (%s, "%s") attacked')
            )
            ->line(
                sprintf('(%d shield, %d armor, %d hull)',
                    $this->notification->text['shieldPercentage'],
                    $this->notification->text['armorPercentage'],
                    $this->notification->text['hullPercentage'])
            )
            ->line(
                sprintf('in %s by %s',
                    $system->itemName,
                    $this->notification->text['corpName'])
            );
    }

    /**
     * @param $notifiable
     * @return mixed
     */
    public function toArray($notifiable)
    {
        return $this->notification->text;
    }
}
