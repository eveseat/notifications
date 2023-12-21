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
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class OrbitalAttacked.
 *
 * @package Seat\Notifications\Notifications\Structures\Slack
 */
class OrbitalAttacked extends AbstractDiscordNotification
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
            ->content('A customs office is under attack!')
            ->embed(function (DiscordEmbed $embed) {
                $embed->timestamp($this->notification->timestamp);
                $embed->color(DiscordMessage::ERROR);
                $embed->author('SeAT Structure Monitor', asset('web/img/favico/apple-icon-180x180.png'));

                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Attacker')
                        ->value(
                            $this->zKillBoardToDiscordLink(
                                'corporation',
                                $this->notification->text['aggressorCorpID'],
                                UniverseName::firstOrNew(
                                    ['entity_id' => $this->notification->text['aggressorCorpID']],
                                    ['category' => 'corporation', 'name' => trans('web::seat.unknown')]
                                )
                                    ->name
                            )
                        );
                })
                    ->field(function (DiscordEmbedField $field) {
                        if (! array_key_exists('aggressorAllianceID', $this->notification->text) || is_null(
                                $this->notification->text['aggressorAllianceID']
                            )) {
                            return;
                        }

                        $field->name('Alliance')
                            ->value(
                                $this->zKillBoardToDiscordLink(
                                    'alliance',
                                    $this->notification->text['aggressorAllianceID'],
                                    UniverseName::firstOrNew(
                                        ['entity_id' => $this->notification->text['aggressorAllianceID']],
                                        ['category' => 'alliance', 'name' => trans('web::seat.unknown')]
                                    )
                                        ->name
                                )
                            );
                    });
            })
            ->embed(function (DiscordEmbed $embed) {
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
                        $planet = MapDenormalize::find($this->notification->text['planetID']);

                        $field->name('Planet')
                            ->value(
                                $this->zKillBoardToDiscordLink(
                                    'location',
                                    $planet->itemID,
                                    $planet->itemName . ' (' . number_format($planet->security, 2) . ')'
                                )
                            );
                    });
            })
            ->embed(function (DiscordEmbed $embed) {
                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Shield')
                        ->value(number_format($this->notification->text['shieldLevel'] * 100, 2));
                })->color(DiscordMessage::SUCCESS);

                if ($this->notification->text['shieldLevel'] * 100 < 70) {
                    $embed->color(DiscordMessage::WARNING);
                }

                if ($this->notification->text['shieldLevel'] * 100 < 40) {
                    $embed->color(DiscordMessage::ERROR);
                }
            });
    }
}
