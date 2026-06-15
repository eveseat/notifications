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
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Notifications\Contracts\ExposesRequiredUniverseIds;
use Seat\Notifications\Jobs\Middleware\LoadRequiredUniverseIds;
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Notifications\Traits\NotificationTools;

class OrbitalReinforced extends AbstractDiscordNotification implements ExposesRequiredUniverseIds
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
            $this->notification->text['aggressorID'] ?? null,
            $this->notification->text['aggressorCorpID'] ?? null,
            $this->notification->text['aggressorAllianceID'] ?? null,
        ])->filter()->unique()->values();
    }

    public function populateMessage(DiscordMessage $message, $notifiable): void
    {
        $aggressor_character = UniverseName::firstOrNew(
            ['entity_id' => $this->notification->text['aggressorID']],
            ['category' => 'character', 'name' => trans('web::seat.unknown')]
        );
        $aggressor_corporation = UniverseName::firstOrNew(
            ['entity_id' => $this->notification->text['aggressorCorpID']],
            ['category' => 'corporation', 'name' => trans('web::seat.unknown')]
        );
        $type = InvType::firstOrNew(
            ['typeID' => $this->notification->text['typeID']],
            ['typeName' => trans('web::seat.unknown')]
        );

        $message
            ->content('A customs office has been reinforced!')
            ->embed(function (DiscordEmbed $embed) use ($aggressor_character, $aggressor_corporation) {
                $embed->timestamp($this->notification->timestamp);
                $embed->color(DiscordMessage::WARNING);
                $embed->author('SeAT Structure Monitor', asset('web/img/favicon/apple-icon-180x180.png'));

                $embed->field(function (DiscordEmbedField $field) use ($aggressor_character) {
                    $field->name('Character')
                        ->value($this->zKillBoardToDiscordLink(
                            'character',
                            $this->notification->text['aggressorID'],
                            $aggressor_character->name
                        ));
                });

                $embed->field(function (DiscordEmbedField $field) use ($aggressor_corporation) {
                    $field->name('Corporation')
                        ->value($this->zKillBoardToDiscordLink(
                            'corporation',
                            $this->notification->text['aggressorCorpID'],
                            $aggressor_corporation->name
                        ));
                });

                if (array_key_exists('aggressorAllianceID', $this->notification->text) && ! is_null($this->notification->text['aggressorAllianceID'])) {
                    $embed->field(function (DiscordEmbedField $field) {
                        $field->name('Alliance')
                            ->value($this->zKillBoardToDiscordLink(
                                'alliance',
                                $this->notification->text['aggressorAllianceID'],
                                UniverseName::firstOrNew(
                                    ['entity_id' => $this->notification->text['aggressorAllianceID']],
                                    ['category' => 'alliance', 'name' => trans('web::seat.unknown')]
                                )->name
                            ));
                    });
                }
            })
            ->embed(function (DiscordEmbed $embed) use ($type) {
                $embed->field(function (DiscordEmbedField $field) {
                    $system = MapDenormalize::find($this->notification->text['solarSystemID']);

                    $field->name('System')
                        ->value($this->zKillBoardToDiscordLink(
                            'system',
                            $system->itemID,
                            $system->itemName . ' (' . number_format($system->security, 2) . ')'
                        ));
                })->field(function (DiscordEmbedField $field) {
                    $planet = MapDenormalize::find($this->notification->text['planetID']);

                    $field->name('Planet')
                        ->value($this->zKillBoardToDiscordLink(
                            'location',
                            $planet->itemID,
                            $planet->itemName
                        ));
                })->field(function (DiscordEmbedField $field) use ($type) {
                    $field->name('Structure')
                        ->value($this->zKillBoardToDiscordLink('ship', $type->typeID, $type->typeName));
                });
            })
            ->embed(function (DiscordEmbed $embed) {
                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Reinforcement Exit (UTC)')
                        ->value($this->mssqlTimestampToDate($this->notification->text['reinforceExitTime'])
                            ->setTimezone('UTC')
                            ->format('Y.m.d H:i:s'))
                        ->long();
                })->color(DiscordMessage::WARNING);
            });
    }
}
