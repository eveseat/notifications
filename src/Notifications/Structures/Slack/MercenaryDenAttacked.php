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
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Notifications\Contracts\ExposesRequiredUniverseIds;
use Seat\Notifications\Jobs\Middleware\LoadRequiredUniverseIds;
use Seat\Notifications\Notifications\AbstractSlackNotification;
use Seat\Notifications\Notifications\Structures\Traits\MercenaryDenNotificationTools;
use Seat\Notifications\Traits\NotificationTools;

class MercenaryDenAttacked extends AbstractSlackNotification implements ExposesRequiredUniverseIds
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

    public function toSlack($notifiable)
    {
        $character_id = $this->notification->text['aggressorCharacterID'] ?? null;
        $attacker = is_null($character_id) ? null : UniverseName::firstOrNew(
            ['entity_id' => $character_id],
            ['category' => 'character', 'name' => trans('web::seat.unknown')]
        );
        $system = $this->getMercenaryDenSystem();
        $planet = $this->getMercenaryDenPlanet();
        $type = $this->getMercenaryDenType();

        return (new SlackMessage)
            ->content('A Mercenary Den is under attack!')
            ->from('SeAT Structure Monitor')
            ->attachment(function ($attachment) use ($attacker, $character_id, $system, $planet, $type) {
                if (! is_null($attacker) && ! is_null($character_id)) {
                    $attachment->field(function ($field) use ($attacker, $character_id) {
                        $field->title('Character')
                            ->content($this->zKillBoardToSlackLink('character', $character_id, $attacker->name));
                    });
                }

                if (array_key_exists('aggressorCorporationName', $this->notification->text)) {
                    $attachment->field(function ($field) {
                        $field->title('Corporation')
                            ->content(clean_ccp_html($this->notification->text['aggressorCorporationName']));
                    });
                }

                if (array_key_exists('aggressorAllianceName', $this->notification->text)) {
                    $attachment->field(function ($field) {
                        $field->title('Alliance')
                            ->content(clean_ccp_html($this->notification->text['aggressorAllianceName']));
                    });
                }

                $attachment->field(function ($field) use ($system) {
                    $field->title('System')
                        ->content($this->zKillBoardToSlackLink(
                            'system',
                            $system->itemID,
                            sprintf('%s (%s)', $system->itemName, number_format($system->security, 2))
                        ));
                });

                $attachment->field(function ($field) use ($planet) {
                    $field->title('Planet')
                        ->content($planet->itemName);
                });

                $attachment->field(function ($field) use ($type) {
                    $field->title($this->getMercenaryDenName())
                        ->content($this->zKillBoardToSlackLink('ship', $type->typeID, $type->typeName));
                });
            })
            ->attachment(function ($attachment) {
                $attachment->field(function ($field) {
                    $field->title('Shield')
                        ->content(number_format($this->notification->text['shieldPercentage'], 2));
                })->color('good');

                if ($this->notification->text['shieldPercentage'] < 70)
                    $attachment->color('warning');

                if ($this->notification->text['shieldPercentage'] < 40)
                    $attachment->color('danger');
            })
            ->attachment(function ($attachment) {
                $attachment->field(function ($field) {
                    $field->title('Armor')
                        ->content(number_format($this->notification->text['armorPercentage'], 2));
                })->color('good');

                if ($this->notification->text['armorPercentage'] < 70)
                    $attachment->color('warning');

                if ($this->notification->text['armorPercentage'] < 40)
                    $attachment->color('danger');
            })
            ->attachment(function ($attachment) {
                $attachment->field(function ($field) {
                    $field->title('Hull')
                        ->content(number_format($this->notification->text['hullPercentage'], 2));
                })->color('good');

                if ($this->notification->text['hullPercentage'] < 70)
                    $attachment->color('warning');

                if ($this->notification->text['hullPercentage'] < 40)
                    $attachment->color('danger');
            });
    }
}
