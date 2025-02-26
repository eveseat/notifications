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

namespace Seat\Notifications\Notifications\Structures\Mail;

use Illuminate\Notifications\Messages\MailMessage;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Notifications\Notifications\AbstractMailNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class TowerAlertMsg.
 *
 * @package Seat\Notifications\Notifications\Structures
 */
class TowerAlertMsg extends AbstractMailNotification
{
    use NotificationTools;

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * TowerAlertMsg constructor.
     *
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
     */
    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @param  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $system = MapDenormalize::find($this->notification->text['solarSystemID']);
        $moon = MapDenormalize::find($this->notification->text['moonID']);
        $type = InvType::find($this->notification->text['typeID']);

        return (new MailMessage)
            ->subject('Tower Under Attack Notification')
            ->line('A tower is under attack!')
            ->line(
                sprintf(
                    'Tower (%s, %s) attacked',
                    $moon->itemName,
                    $type->typeName
                )
            )
            ->line(
                sprintf(
                    '(%d shield, %d armor, %d hull)',
                    number_format($this->notification->text['shieldValue'] * 100, 2),
                    number_format($this->notification->text['armorValue'] * 100, 2),
                    number_format($this->notification->text['hullValue'] * 100, 2)
                )
            )
            ->line(
                sprintf(
                    'in %s',
                    $system->itemName
                )
            );
    }

    /**
     * @param  $notifiable
     * @return mixed
     */
    public function toArray($notifiable)
    {
        return $this->notification->text;
    }
}
