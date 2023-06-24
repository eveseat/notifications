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
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class MoonMiningExtractionStarted.
 *
 * @package Seat\Notifications\Notifications\Structures
 */
class MoonMiningExtractionStarted extends AbstractMailNotification
{
    use NotificationTools;

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * MoonMiningExtractionFinished constructor.
     *
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
     */
    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @param  $notifiable
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
        $system = MapDenormalize::find($this->notification->text['solarSystemID']);

        $moon = MapDenormalize::find($this->notification->text['moonID']);

        $type = InvType::find($this->notification->text['structureTypeID']);

        $mail = (new MailMessage)
            ->subject('Moon Mining Extraction Started Notification!')
            ->line(
                sprintf('A Moon Mining Extraction operated on %s (%s) - %s has been started.',
                    $system->itemName, number_format($system->security, 2), $moon->itemName)
            )
        ->line(
            sprintf('The structure %s (%s) has reported the content bellow:',
                $this->notification->text['structureName'], $type->typeName)
        );

        foreach ($this->notification->text['oreVolumeByType'] as $type_id => $volume) {
            $type = InvType::find($type_id);

            $mail->line(
                sprintf(' - %s : %s', $type->typeName, number_format($volume, 2))
            );
        }

        $mail->line(
            sprintf('Be ready, you\'ll be able to start mining on %s!',
                $this->mssqlTimestampToDate($this->notification->text['readyTime'])->toRfc7231String())
        );

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
