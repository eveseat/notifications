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

use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class TowerAlertMsg.
 *
 * @package Seat\Notifications\Notifications\Structures
 */
class TowerAlertMsg extends AbstractDiscordNotification
{
    use NotificationTools;

    private CharacterNotification $notification;

    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    public function populateMessage(DiscordMessage $message, $notifiable): void
    {
        $message
            ->content('A tower is under attack!')
            ->embed(function (DiscordEmbed $embed) {
                $embed->timestamp($this->notification->timestamp);
                $embed->color(DiscordMessage::ERROR);
                $embed->author('SeAT Structure Monitor', asset('web/img/favico/apple-icon-180x180.png'));

                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Character')
                        ->value(
                            $this->zKillBoardToDiscordLink(
                                'character',
                                $this->notification->text['aggressorID'],
                                "Link to Character"
                            )
                        );
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Corporation')
                        ->value(
                            $this->zKillBoardToDiscordLink(
                                'corporation',
                                $this->notification->text['aggressorCorpID'],
                                "Link to Corporation"
                            )
                        );
                });

                if (array_key_exists('aggressorAllianceID', $this->notification->text) && ! is_null(
                    $this->notification->text['aggressorAllianceID']
                )) {
                    $embed->field(function (DiscordEmbedField $field) {
                        $field->name('Alliance')
                            ->value(
                                $this->zKillBoardToDiscordLink(
                                    'alliance',
                                    $this->notification->text['aggressorAllianceID'],
                                    "Link to Alliance"
                                )
                            );
                    });
                }

                $embed->field(function (DiscordEmbedField $field) {
                    $system = MapDenormalize::find($this->notification->text['solarSystemID']);

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
                        $moon = MapDenormalize::find($this->notification->text['moonID']);

                        $field->name('Moon')
                            ->value($moon->itemName);
                    })
                    ->field(function (DiscordEmbedField $field) {
                        $type = InvType::find($this->notification->text['typeID']);

                        $field->name('Structure')
                            ->value($type->typeName);
                    });
            })
            ->embed(function (DiscordEmbed $embed) {
                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Shield')
                        ->value(number_format($this->notification->text['shieldValue'] * 100, 2));
                })->color(DiscordMessage::SUCCESS);

                if ($this->notification->text['shieldValue'] < 0.70) {
                    $embed->color(DiscordMessage::WARNING);
                }

                if ($this->notification->text['shieldValue'] < 0.40) {
                    $embed->color(DiscordMessage::ERROR);
                }
            })
            ->embed(function (DiscordEmbed $embed) {
                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Armor')
                        ->value(number_format($this->notification->text['armorValue'] * 100, 2));
                })->color(DiscordMessage::SUCCESS);

                if ($this->notification->text['armorValue'] < 0.70) {
                    $embed->color(DiscordMessage::WARNING);
                }

                if ($this->notification->text['armorValue'] < 0.40) {
                    $embed->color(DiscordMessage::ERROR);
                }
            })
            ->embed(function (DiscordEmbed $embed) {
                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Hull')
                        ->value(number_format($this->notification->text['hullValue'] * 100, 2));
                })->color(DiscordMessage::SUCCESS);

                if ($this->notification->text['hullValue'] < 0.70) {
                    $embed->color(DiscordMessage::WARNING);
                }

                if ($this->notification->text['hullValue'] < 0.40) {
                    $embed->color(DiscordMessage::ERROR);
                }
            });
    }
}
