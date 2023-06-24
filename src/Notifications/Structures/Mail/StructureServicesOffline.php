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

namespace Seat\Notifications\Notifications\Structures\Mail;

use Illuminate\Notifications\Messages\MailMessage;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Notifications\Notifications\AbstractMailNotification;

/**
 * Class StructureServicesOffline.
 *
 * @package Seat\Notifications\Notifications\Structures
 */
class StructureServicesOffline extends AbstractMailNotification
{
    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * StructureServicesOffline constructor.
     *
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
     */
    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @param  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * @param  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $system = MapDenormalize::find($this->notification->text['solarsystemID']);

        $mail = (new MailMessage)
            ->subject('Structure Services Offline Notification!')
            ->line(
                sprintf('A structure service has been shutdown in the system %s (%s)!',
                    $system->itemName, number_format($system->security, 2)))
            ->line('The following modules are concerned :');

        foreach ($this->notification->text['listOfServiceModuleIDs'] as $type_id) {
            $type = InvType::find($type_id);

            $mail->line(sprintf(' - %s', $type->typeName));
        }

        return $mail;
    }

    /**
     * @param  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->notification->text;
    }
}
