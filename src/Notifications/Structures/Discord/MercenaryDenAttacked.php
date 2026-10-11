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
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Notifications\Contracts\ExposesRequiredUniverseIds;
use Seat\Notifications\Jobs\Middleware\LoadRequiredUniverseIds;
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Notifications\Structures\Traits\MercenaryDenNotificationTools;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Notifications\Traits\NotificationTools;

class MercenaryDenAttacked extends AbstractDiscordNotification implements ExposesRequiredUniverseIds
{
    use NotificationTools;
    use MercenaryDenNotificationTools;

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
            $this->notification->text['aggressorCharacterID'] ?? null,
        ])->filter()->unique()->values();
    }

    public function populateMessage(DiscordMessage $message, $notifiable): void
    {
        $character_id = $this->notification->text['aggressorCharacterID'] ?? null;
        $attacker = is_null($character_id) ? null : UniverseName::firstOrNew(
            ['entity_id' => $character_id],
            ['category' => 'character', 'name' => trans('web::seat.unknown')]
        );

        $message
            ->content('A Mercenary Den is under attack!')
            ->embed(function (DiscordEmbed $embed) use ($attacker, $character_id) {
                $system = $this->getMercenaryDenSystem();
                $planet = $this->getMercenaryDenPlanet();
                $type = $this->getMercenaryDenType();

                $embed->timestamp($this->notification->timestamp);
                $embed->color(DiscordMessage::ERROR);
                $embed->author('SeAT Structure Monitor', asset('web/img/favicon/apple-icon-180x180.png'));

                if (! is_null($attacker) && ! is_null($character_id)) {
                    $embed->field(function (DiscordEmbedField $field) use ($attacker, $character_id) {
                        $field->name('Character')
                            ->value($this->zKillBoardToDiscordLink('character', $character_id, $attacker->name));
                    });
                }

                if (array_key_exists('aggressorCorporationName', $this->notification->text)) {
                    $embed->field(function (DiscordEmbedField $field) {
                        $field->name('Corporation')
                            ->value(clean_ccp_html($this->notification->text['aggressorCorporationName']));
                    });
                }

                if (array_key_exists('aggressorAllianceName', $this->notification->text)) {
                    $embed->field(function (DiscordEmbedField $field) {
                        $field->name('Alliance')
                            ->value(clean_ccp_html($this->notification->text['aggressorAllianceName']));
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
                    $field->name($this->getMercenaryDenName())
                        ->value($this->zKillBoardToDiscordLink('ship', $type->typeID, $type->typeName));
                });
            })
            ->embed(function (DiscordEmbed $embed) {
                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Shield')
                        ->value(number_format($this->notification->text['shieldPercentage'], 2));
                })->color(DiscordMessage::SUCCESS);

                if ($this->notification->text['shieldPercentage'] < 70)
                    $embed->color(DiscordMessage::WARNING);

                if ($this->notification->text['shieldPercentage'] < 40)
                    $embed->color(DiscordMessage::ERROR);
            })
            ->embed(function (DiscordEmbed $embed) {
                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Armor')
                        ->value(number_format($this->notification->text['armorPercentage'], 2));
                })->color(DiscordMessage::SUCCESS);

                if ($this->notification->text['armorPercentage'] < 70)
                    $embed->color(DiscordMessage::WARNING);

                if ($this->notification->text['armorPercentage'] < 40)
                    $embed->color(DiscordMessage::ERROR);
            })
            ->embed(function (DiscordEmbed $embed) {
                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Hull')
                        ->value(number_format($this->notification->text['hullPercentage'], 2));
                })->color(DiscordMessage::SUCCESS);

                if ($this->notification->text['hullPercentage'] < 70)
                    $embed->color(DiscordMessage::WARNING);

                if ($this->notification->text['hullPercentage'] < 40)
                    $embed->color(DiscordMessage::ERROR);
            });
    }
}
