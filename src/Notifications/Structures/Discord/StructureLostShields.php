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

namespace Seat\Notifications\Notifications\Structures\Discord;

use Illuminate\Support\Collection;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Notifications\Contracts\ExposesRequiredUniverseIds;
use Seat\Notifications\Jobs\Middleware\LoadRequiredUniverseIds;
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class StructureLostShields.
 *
 * @package Seat\Notifications\Notifications\Structures\Slack
 */
class StructureLostShields extends AbstractDiscordNotification implements ExposesRequiredUniverseIds
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
            $this->notification->text['charID'] ?? null,
            $this->notification->text['corpLinkData'][2] ?? null,
            $this->notification->text['allianceID'] ?? null,
        ])->filter()->unique()->values();
    }

    public function populateMessage(DiscordMessage $message, $notifiable): void
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

        $message
            ->content('A Structure lost Shield!')
            ->embed(function (DiscordEmbed $embed) use ($attacker, $character_id, $corporation, $corporation_id, $alliance, $alliance_id, $system, $type, $structure_name) {
                $embed->timestamp($this->notification->timestamp);
                $embed->color(DiscordMessage::ERROR);
                $embed->author('SeAT Structure Monitor', asset('web/img/favicon/apple-icon-180x180.png'));

                if (! is_null($attacker) && ! is_null($character_id)) {
                    $embed->field(function (DiscordEmbedField $field) use ($attacker, $character_id) {
                        $field->name('Character')
                            ->value($this->zKillBoardToDiscordLink('character', $character_id, $attacker->name));
                    });
                }

                if (! is_null($corporation) && ! is_null($corporation_id)) {
                    $corporation_name = $this->notification->text['corpName'] ?? $corporation->name;
                    $embed->field(function (DiscordEmbedField $field) use ($corporation_id, $corporation_name) {
                        $field->name('Corporation')
                            ->value($this->zKillBoardToDiscordLink('corporation', $corporation_id, $corporation_name));
                    });
                }

                if (! is_null($alliance) && ! is_null($alliance_id)) {
                    $alliance_name = $this->notification->text['allianceName'] ?? $alliance->name;
                    $embed->field(function (DiscordEmbedField $field) use ($alliance_id, $alliance_name) {
                        $field->name('Alliance')
                            ->value($this->zKillBoardToDiscordLink('alliance', $alliance_id, $alliance_name));
                    });
                }

                $embed->field(function (DiscordEmbedField $field) use ($system) {
                    $field->name('System')
                        ->value(
                            $this->zKillBoardToDiscordLink(
                                'system',
                                $system->itemID,
                                sprintf('%s (%s)', $system->itemName, number_format($system->security, 2))
                            )
                        );
                });

                $embed->field(function (DiscordEmbedField $field) use ($type, $structure_name) {
                    $field->name($structure_name)
                        ->value($this->zKillBoardToDiscordLink('ship', $type->typeID, $type->typeName));
                });

                if (array_key_exists('timeLeft', $this->notification->text)) {
                    $embed->field(function (DiscordEmbedField $field) {
                        $field->name('Reinforcement Timer')
                            ->value($this->eveDurationToDateTimeString($this->notification->text['timeLeft'], $this->notification->timestamp));
                    });
                }

                if (array_key_exists('vulnerableTime', $this->notification->text)) {
                    $embed->field(function (DiscordEmbedField $field) {
                        $field->name('Vulnerability Window')
                            ->value($this->eveDurationToString($this->notification->text['vulnerableTime']));
                    });
                }
            })
            ->warning();
    }
}
