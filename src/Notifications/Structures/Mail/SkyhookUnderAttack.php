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
use Illuminate\Support\Collection;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Notifications\Contracts\ExposesRequiredUniverseIds;
use Seat\Notifications\Jobs\Middleware\LoadRequiredUniverseIds;
use Seat\Notifications\Notifications\AbstractMailNotification;
use Seat\Notifications\Notifications\Structures\Traits\SkyhookNotificationTools;
use Seat\Notifications\Traits\NotificationTools;

class SkyhookUnderAttack extends AbstractMailNotification implements ExposesRequiredUniverseIds
{
    use NotificationTools;
    use SkyhookNotificationTools;

    private CharacterNotification $notification;

    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    public function middleware(): array
    {
        return array_merge(
            parent::middleware(),
            [new LoadRequiredUniverseIds]
        );
    }

    public function getRequiredUniverseIds(): Collection
    {
        return collect([
            $this->notification->text['charID'] ?? null,
        ])->filter()->unique()->values();
    }

    public function toMail($notifiable)
    {
        $attacker = UniverseName::firstOrNew(
            ['entity_id' => $this->notification->text['charID']],
            ['category' => 'character', 'name' => trans('web::seat.unknown')]
        );
        $system = $this->getSkyhookSystem();
        $planet = $this->getSkyhookPlanet();
        $type = $this->getSkyhookType();

        $mail = (new MailMessage)
            ->subject('Skyhook Under Attack Notification')
            ->line('A skyhook is under attack!')
            ->line(sprintf(
                'Skyhook (%s, "%s") attacked',
                $this->getSkyhookName(),
                $type->typeName
            ))
            ->line(sprintf(
                '(%d shield, %d armor, %d hull)',
                $this->notification->text['shieldPercentage'],
                $this->notification->text['armorPercentage'],
                $this->notification->text['hullPercentage']
            ))
            ->line(sprintf(
                'at %s in %s by %s (%s)',
                $planet->itemName,
                $system->itemName,
                $attacker->name,
                $this->notification->text['corpName']
            ));

        if (array_key_exists('allianceName', $this->notification->text) && ! empty($this->notification->text['allianceName'])) {
            $mail->line(sprintf('Alliance: %s', $this->notification->text['allianceName']));
        }

        return $mail;
    }

    public function toArray($notifiable)
    {
        return $this->notification->text;
    }
}
