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

namespace Seat\Notifications\Notifications\Structures\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Support\Collection;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Notifications\Contracts\ExposesRequiredUniverseIds;
use Seat\Notifications\Jobs\Middleware\LoadRequiredUniverseIds;
use Seat\Notifications\Notifications\AbstractSlackNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class StructureLostShields.
 *
 * @package Seat\Notifications\Notifications\Structures\Slack
 */
class StructureLostShields extends AbstractSlackNotification implements ExposesRequiredUniverseIds
{
    use NotificationTools;

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * StructureLostShields constructor.
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
            $this->notification->text['corpLinkData'][2] ?? null,
            $this->notification->text['allianceID'] ?? null,
        ])->filter()->unique()->values();
    }

    /**
     * @param  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $character_id = $this->notification->text['charID'] ?? null;
        $corporation_id = $this->notification->text['corpLinkData'][2] ?? null;
        $alliance_id = $this->notification->text['allianceID'] ?? null;
        $attacker = is_null($character_id) ? null : UniverseName::firstOrNew(
            ['entity_id' => $character_id],
            ['category' => 'character', 'name' => trans('web::seat.unknown')]
        );
        $corporation = is_null($corporation_id) ? null : UniverseName::firstOrNew(
            ['entity_id' => $corporation_id],
            ['category' => 'corporation', 'name' => trans('web::seat.unknown')]
        );
        $alliance = is_null($alliance_id) ? null : UniverseName::firstOrNew(
            ['entity_id' => $alliance_id],
            ['category' => 'alliance', 'name' => trans('web::seat.unknown')]
        );
        $system = MapDenormalize::firstOrNew(
            ['itemID' => $this->notification->text['solarsystemID']],
            ['itemName' => trans('web::seat.unknown'), 'security' => 0]
        );
        $type = InvType::firstOrNew(
            ['typeID' => $this->notification->text['structureTypeID']],
            ['typeName' => trans('web::seat.unknown')]
        );
        $structure = UniverseStructure::find($this->notification->text['structureID'] ?? 0);
        $structure_name = ! is_null($structure) && ! empty($structure->name) ? $structure->name : 'Structure';

        return (new SlackMessage)
            ->content('A Structure lost Shield!')
            ->from('SeAT Structure Monitor')
            ->attachment(function ($attachment) use ($attacker, $character_id, $corporation, $corporation_id, $alliance, $alliance_id, $system, $type, $structure_name) {
                if (! is_null($attacker) && ! is_null($character_id)) {
                    $attachment->field(function ($field) use ($attacker, $character_id) {
                        $field->title('Character')
                            ->content($this->zKillBoardToSlackLink('character', $character_id, $attacker->name));
                    });
                }

                if (! is_null($corporation) && ! is_null($corporation_id)) {
                    $corporation_name = $this->notification->text['corpName'] ?? $corporation->name;
                    $attachment->field(function ($field) use ($corporation_id, $corporation_name) {
                        $field->title('Corporation')
                            ->content($this->zKillBoardToSlackLink('corporation', $corporation_id, $corporation_name));
                    });
                }

                if (! is_null($alliance) && ! is_null($alliance_id)) {
                    $alliance_name = $this->notification->text['allianceName'] ?? $alliance->name;
                    $attachment->field(function ($field) use ($alliance_id, $alliance_name) {
                        $field->title('Alliance')
                            ->content($this->zKillBoardToSlackLink('alliance', $alliance_id, $alliance_name));
                    });
                }

                $attachment->field(function ($field) use ($system) {
                    $field->title('System')
                        ->content($this->zKillBoardToSlackLink(
                            'system',
                            $system->itemID,
                            sprintf('%s (%s)', $system->itemName, number_format($system->security, 2))));
                });

                $attachment->field(function ($field) use ($type, $structure_name) {
                    $field->title($structure_name)
                        ->content($this->zKillBoardToSlackLink('ship', $type->typeID, $type->typeName));
                });

                if (array_key_exists('timeLeft', $this->notification->text)) {
                    $attachment->field(function ($field) {
                        $field->title('Reinforcement Timer')
                            ->content($this->eveDurationToDateTimeString($this->notification->text['timeLeft'], $this->notification->timestamp));
                    });
                }

                if (array_key_exists('vulnerableTime', $this->notification->text)) {
                    $attachment->field(function ($field) {
                        $field->title('Vulnerability Window')
                            ->content($this->eveDurationToString($this->notification->text['vulnerableTime']));
                    });
                }
            })
            ->warning();
    }
}
