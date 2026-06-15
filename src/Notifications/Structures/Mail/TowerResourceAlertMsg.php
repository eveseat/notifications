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
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Notifications\Contracts\ExposesRequiredUniverseIds;
use Seat\Notifications\Jobs\Middleware\LoadRequiredUniverseIds;
use Seat\Notifications\Notifications\AbstractMailNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class TowerResourceAlertMsg.
 *
 * @package Seat\Notifications\Notifications\Structures
 */
class TowerResourceAlertMsg extends AbstractMailNotification implements ExposesRequiredUniverseIds
{
    use NotificationTools;

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
            $this->notification->text['corpID'] ?? null,
            $this->notification->text['allianceID'] ?? null,
        ])->filter()->unique()->values();
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
        $corporation = UniverseName::firstOrNew(
            ['entity_id' => $this->notification->text['corpID']],
            ['category' => 'corporation', 'name' => trans('web::seat.unknown')]
        );
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

        $mail->line(sprintf('Corporation: %s', $corporation->name));

        if (! is_null($alliance)) {
            $alliance_name = UniverseName::firstOrNew(
                ['entity_id' => $alliance],
                ['category' => 'alliance', 'name' => trans('web::seat.unknown')]
            );
            $mail->line(sprintf('Alliance: %s', $alliance_name->name));
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
