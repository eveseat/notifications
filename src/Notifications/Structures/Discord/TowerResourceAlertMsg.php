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
use Seat\Notifications\Contracts\ExposesRequiredUniverseIds;
use Seat\Notifications\Jobs\Middleware\LoadRequiredUniverseIds;
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class TowerResourceAlertMsg.
 *
 * @package Seat\Notifications\Notifications\Structures
 */
class TowerResourceAlertMsg extends AbstractDiscordNotification implements ExposesRequiredUniverseIds
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
            $this->notification->text['corpID'] ?? null,
            $this->notification->text['allianceID'] ?? null,
        ])->filter()->unique()->values();
    }

    public function populateMessage(DiscordMessage $message, $notifiable): void
    {
        $message
            ->content('A tower requires additional resources!')
            ->embed(function (DiscordEmbed $embed) {
                $embed->timestamp($this->notification->timestamp);
                $embed->color(DiscordMessage::WARNING);
                $embed->author('SeAT Structure Monitor', asset('web/img/favicon/apple-icon-180x180.png'));

                $embed->field(function (DiscordEmbedField $field) {
                    $system = MapDenormalize::firstOrNew(
                        ['itemID' => $this->notification->text['solarSystemID']],
                        ['itemName' => trans('web::seat.unknown'), 'security' => 0]
                    );

                    $field->name('System')
                        ->value(
                            $this->zKillBoardToDiscordLink(
                                'system',
                                $system->itemID,
                                sprintf('%s (%s)', $system->itemName, number_format($system->security, 2))
                            )
                        );
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $moon = MapDenormalize::firstOrNew(
                        ['itemID' => $this->notification->text['moonID']],
                        ['itemName' => trans('web::seat.unknown')]
                    );

                    $field->name('Moon')
                        ->value($moon->itemName);
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $type = InvType::firstOrNew(
                        ['typeID' => $this->notification->text['typeID']],
                        ['typeName' => trans('web::seat.unknown')]
                    );

                    $field->name('Structure')
                        ->value($this->zKillBoardToDiscordLink('ship', $type->typeID, $type->typeName));
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $corporation = UniverseName::firstOrNew(
                        ['entity_id' => $this->notification->text['corpID']],
                        ['category' => 'corporation', 'name' => trans('web::seat.unknown')]
                    );

                    $field->name('Corporation')
                        ->value($corporation->name);
                });

                if (array_key_exists('allianceID', $this->notification->text) && ! is_null($this->notification->text['allianceID'])) {
                    $embed->field(function (DiscordEmbedField $field) {
                        $alliance = UniverseName::firstOrNew(
                            ['entity_id' => $this->notification->text['allianceID']],
                            ['category' => 'alliance', 'name' => trans('web::seat.unknown')]
                        );

                        $field->name('Alliance')
                            ->value($alliance->name);
                    });
                }
            })
            ->embed(function (DiscordEmbed $embed) {
                foreach ($this->notification->text['wants'] ?? [] as $item) {
                    $embed->field(function (DiscordEmbedField $field) use ($item) {
                        $type = InvType::firstOrNew(
                            ['typeID' => $item['typeID']],
                            ['typeName' => trans('web::seat.unknown')]
                        );

                        $field->name($type->typeName)
                            ->value((string) $item['quantity']);
                    });
                }

                $embed->color(DiscordMessage::INFO);
            });
    }
}
