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
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Notifications\Structures\Traits\SkyhookNotificationTools;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Notifications\Traits\NotificationTools;

class SkyhookUnderAttack extends AbstractDiscordNotification
{
    use NotificationTools;
    use SkyhookNotificationTools;

    private CharacterNotification $notification;

    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    public function populateMessage(DiscordMessage $message, $notifiable): void
    {
        $message
            ->content('A Skyhook is under attack!')
            ->embed(function (DiscordEmbed $embed) {
                $system = $this->getSkyhookSystem();
                $planet = $this->getSkyhookPlanet();
                $type = $this->getSkyhookType();

                $embed->timestamp($this->notification->timestamp);
                $embed->color(DiscordMessage::ERROR);
                $embed->author('SeAT Structure Monitor', asset('web/img/favico/apple-icon-180x180.png'));

                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Attacker')
                        ->value($this->zKillBoardToDiscordLink(
                            'corporation',
                            $this->notification->text['corpLinkData'][2],
                            $this->notification->text['corpName']
                        ));
                });

                if (array_key_exists('allianceID', $this->notification->text) && ! is_null($this->notification->text['allianceID'])) {
                    $embed->field(function (DiscordEmbedField $field) {
                        $field->name('Alliance')
                            ->value($this->zKillBoardToDiscordLink(
                                'alliance',
                                $this->notification->text['allianceID'],
                                $this->notification->text['allianceName']
                            ));
                    });
                }

                $embed->field(function (DiscordEmbedField $field) use ($system) {
                    $field->name('System')
                        ->value($this->zKillBoardToDiscordLink(
                            'system',
                            $system->itemID,
                            sprintf('%s (%s)', $system->itemName, number_format($system->security, 2))
                        ));
                });

                $embed->field(function (DiscordEmbedField $field) use ($planet) {
                    $field->name('Planet')
                        ->value($planet->itemName);
                });

                $embed->field(function (DiscordEmbedField $field) use ($type) {
                    $field->name($this->getSkyhookName())
                        ->value($this->zKillBoardToDiscordLink('ship', $type->typeID, $type->typeName));
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
