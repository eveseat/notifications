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
 * Class StructureUnderAttack.
 *
 * @package Seat\Notifications\Notifications\Structures
 */
class StructureUnderAttack extends AbstractDiscordNotification implements ExposesRequiredUniverseIds
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
        ])->filter()->unique()->values();
    }

    public function populateMessage(DiscordMessage $message, $notifiable): void
    {
        $attacker = UniverseName::firstOrNew(
            ['entity_id' => $this->notification->text['charID']],
            ['category' => 'character', 'name' => trans('web::seat.unknown')]
        );

        $message
            ->content('A structure is under attack!')
            ->embed(function (DiscordEmbed $embed) use ($attacker) {
                $embed->timestamp($this->notification->timestamp);
                $embed->color(DiscordMessage::ERROR);
                $embed->author('SeAT Structure Monitor', asset('web/img/favicon/apple-icon-180x180.png'));

                $embed->field(function (DiscordEmbedField $field) use ($attacker) {
                    $field->name('Character')
                        ->value(
                            $this->zKillBoardToDiscordLink(
                                'character',
                                $this->notification->text['charID'],
                                $attacker->name
                            )
                        );
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Corporation')
                        ->value(
                            $this->zKillBoardToDiscordLink(
                                'corporation',
                                $this->notification->text['corpLinkData'][2],
                                $this->notification->text['corpName']
                            )
                        );
                });

                if (array_key_exists('allianceID', $this->notification->text) && ! is_null(
                    $this->notification->text['allianceID']
                    )) {
                    $embed->field(function (DiscordEmbedField $field) {
                        $field->name('Alliance')
                            ->value(
                                $this->zKillBoardToDiscordLink(
                                    'alliance',
                                    $this->notification->text['allianceID'],
                                    $this->notification->text['allianceName']
                                )
                            );
                    });
                }

                $embed->field(function (DiscordEmbedField $field) {
                    $system = MapDenormalize::find($this->notification->text['solarsystemID']);

                    $field->name('System')
                        ->value(
                            $this->zKillBoardToDiscordLink(
                                'system',
                                $system->itemID,
                                $system->itemName . ' (' . number_format($system->security, 2) . ')'
                            )
                        );
                })
                ->field(function (DiscordEmbedField $field) {
                    $structure = UniverseStructure::find($this->notification->text['structureID']);

                    $type = InvType::find($this->notification->text['structureShowInfoData'][1]);

                    $title = 'Structure';

                    if (! is_null($structure)) {
                        $title = $structure->name;
                    }

                    $field->name($title)
                        ->value($type->typeName);
                });
            })
            ->embed(function (DiscordEmbed $embed) {
                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Shield')
                        ->value(number_format($this->notification->text['shieldPercentage'], 2));
                })->color(DiscordMessage::SUCCESS);

                if ($this->notification->text['shieldPercentage'] < 70) {
                    $embed->color(DiscordMessage::WARNING);
                }

                if ($this->notification->text['shieldPercentage'] < 40) {
                    $embed->color(DiscordMessage::ERROR);
                }
            })
            ->embed(function (DiscordEmbed $embed) {
                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Armor')
                        ->value(number_format($this->notification->text['armorPercentage'], 2));
                })->color(DiscordMessage::SUCCESS);

                if ($this->notification->text['armorPercentage'] < 70) {
                    $embed->color(DiscordMessage::WARNING);
                }

                if ($this->notification->text['armorPercentage'] < 40) {
                    $embed->color(DiscordMessage::ERROR);
                }
            })
            ->embed(function (DiscordEmbed $embed) {
                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Hull')
                        ->value(number_format($this->notification->text['hullPercentage'], 2));
                })->color(DiscordMessage::SUCCESS);

                if ($this->notification->text['hullPercentage'] < 70) {
                    $embed->color(DiscordMessage::WARNING);
                }

                if ($this->notification->text['hullPercentage'] < 40) {
                    $embed->color(DiscordMessage::ERROR);
                }
            });
    }
}
