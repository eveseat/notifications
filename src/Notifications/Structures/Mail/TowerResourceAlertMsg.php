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
use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Notifications\Notifications\AbstractMailNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class TowerResourceAlertMsg.
 *
 * @package Seat\Notifications\Notifications\Structures
 */
class TowerResourceAlertMsg extends AbstractMailNotification
{
    use NotificationTools;

    private CharacterNotification $notification;

    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    public function toMail($notifiable)
    {
        $system = MapDenormalize::firstOrNew(
            ['itemID' => $this->notification->text['solarSystemID']],
            ['itemName' => trans('web::seat.unknown'), 'security' => 0]
        );
        $moon = MapDenormalize::firstOrNew(
            ['itemID' => $this->notification->text['moonID']],
            ['itemName' => trans('web::seat.unknown')]
        );
        $type = InvType::firstOrNew(
            ['typeID' => $this->notification->text['typeID']],
            ['typeName' => trans('web::seat.unknown')]
        );
        $corporation = UniverseName::find($this->notification->text['corpID']);
        $alliance = $this->notification->text['allianceID'] ?? null;
        $mail = (new MailMessage)
            ->subject('Tower Resource Alert Notification')
            ->line('A tower requires additional resources!')
            ->line(sprintf(
                'Tower (%s, %s) in %s (%s) requires the following items.',
                $moon->itemName,
                $type->typeName,
                $system->itemName,
                number_format($system->security, 2)
            ));

        if (! is_null($corporation)) {
            $mail->line(sprintf('Corporation: %s', $corporation->name));
        }

        if (! is_null($alliance)) {
            $alliance_name = Alliance::firstOrNew(
                ['alliance_id' => $alliance],
                ['name' => trans('web::seat.unknown')]
            );

            if (! is_null($alliance_name)) {
                $mail->line(sprintf('Alliance: %s', $alliance_name->name));
            }
        }

        foreach ($this->notification->text['wants'] ?? [] as $item) {
            $resource = InvType::firstOrNew(
                ['typeID' => $item['typeID']],
                ['typeName' => trans('web::seat.unknown')]
            );

            $mail->line(sprintf(' - %s : %d', $resource->typeName, $item['quantity']));
        }

        return $mail;
    }

    public function toArray($notifiable)
    {
        return $this->notification->text;
    }
}
