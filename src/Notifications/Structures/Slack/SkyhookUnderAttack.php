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
use Seat\Notifications\Notifications\Structures\Traits\SkyhookNotificationTools;
use Seat\Notifications\Traits\NotificationTools;

class SkyhookUnderAttack extends AbstractSlackNotification implements ExposesRequiredUniverseIds
{
    use NotificationTools;
    use SkyhookNotificationTools;

    private $notification;

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

    public function toSlack($notifiable)
    {
        $attacker = UniverseName::firstOrNew(
            ['entity_id' => $this->notification->text['charID']],
            ['category' => 'character', 'name' => trans('web::seat.unknown')]
        );
        $system = $this->getSkyhookSystem();
        $planet = $this->getSkyhookPlanet();
        $type = $this->getSkyhookType();

        return (new SlackMessage)
            ->content('A Skyhook is under attack!')
            ->from('SeAT Structure Monitor')
            ->attachment(function ($attachment) use ($attacker, $system, $planet, $type) {
                $attachment->field(function ($field) use ($attacker) {
                    $field->title('Character')
                        ->content($this->zKillBoardToSlackLink(
                            'character',
                            $this->notification->text['charID'],
                            $attacker->name
                        ));
                });

                $attachment->field(function ($field) {
                    $field->title('Corporation')
                        ->content($this->zKillBoardToSlackLink(
                            'corporation',
                            $this->notification->text['corpLinkData'][2],
                            $this->notification->text['corpName']
                        ));
                });

                if (array_key_exists('allianceID', $this->notification->text) && ! is_null($this->notification->text['allianceID'])) {
                    $attachment->field(function ($field) {
                        $field->title('Alliance')
                            ->content($this->zKillBoardToSlackLink(
                                'alliance',
                                $this->notification->text['allianceID'],
                                $this->notification->text['allianceName']
                            ));
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
                    $field->title($this->getSkyhookName())
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
