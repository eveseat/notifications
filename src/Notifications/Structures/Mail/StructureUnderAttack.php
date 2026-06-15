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
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Notifications\Contracts\ExposesRequiredUniverseIds;
use Seat\Notifications\Jobs\Middleware\LoadRequiredUniverseIds;
use Seat\Notifications\Notifications\AbstractMailNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class StructureUnderAttack.
 *
 * @package Seat\Notifications\Notifications\Structures
 */
class StructureUnderAttack extends AbstractMailNotification implements ExposesRequiredUniverseIds
{
    use NotificationTools;

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * StructureUnderAttack constructor.
     *
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
     */
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

    /**
     * @param  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $attacker = UniverseName::firstOrNew(
            ['entity_id' => $this->notification->text['charID']],
            ['category' => 'character', 'name' => trans('web::seat.unknown')]
        );
        $system = MapDenormalize::find($this->notification->text['solarsystemID']);
        $structure = UniverseStructure::find($this->notification->text['structureID']);
        $type = InvType::find($this->notification->text['structureShowInfoData'][1]);
        $title = 'Structure';
        if (! is_null($structure)) {
            $title = $structure->name;
        }
        $structureType = $type->typeName;

        return (new MailMessage)
            ->subject('Structure Under Attack Notification')
            ->line('A structure is under attack!')
            ->line(
                sprintf('Citadel (%s, "%s") attacked', $title, $structureType)
            )
            ->line(
                sprintf('(%d shield, %d armor, %d hull)',
                    $this->notification->text['shieldPercentage'],
                    $this->notification->text['armorPercentage'],
                    $this->notification->text['hullPercentage'])
            )
            ->line(
                sprintf('in %s by %s (%s)',
                    $system->itemName,
                    $attacker->name,
                    $this->notification->text['corpName'])
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
