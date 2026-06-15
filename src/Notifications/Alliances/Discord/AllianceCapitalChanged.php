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

namespace Seat\Notifications\Notifications\Alliances\Discord;

use Illuminate\Support\Collection;
use Seat\Eveapi\Models\Character\CharacterNotification;
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
 * Class AllianceCapitalChanged.
 *
 * @package Seat\Notifications\Notifications\Alliances\Discord
 */
class AllianceCapitalChanged extends AbstractDiscordNotification implements ExposesRequiredUniverseIds
{
    use NotificationTools;

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * Constructor.
     *
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
     */
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
            $this->notification->text['allianceID'] ?? null,
        ])->filter()->unique()->values();
    }

    /**
     * @param  DiscordMessage  $message
     * @param  $notifiable
     */
    public function populateMessage(DiscordMessage $message, $notifiable)
    {
        $message
            ->content('Capital has been modified! :white_sun_small_cloud:')
            ->embed(function (DiscordEmbed $embed) {
                $embed->timestamp($this->notification->timestamp);
                $embed->author('SeAT Alliance Weather', asset('web/img/favicon/apple-icon-180x180.png'));

                $embed->field(function (DiscordEmbedField $field) {
                    $alliance = UniverseName::firstOrNew(
                        ['entity_id' => $this->notification->text['allianceID']],
                        ['category' => 'alliance', 'name' => trans('web::seat.unknown')],
                    );

                    $field->name('Alliance');
                    $field->value($this->zKillBoardToDiscordLink('alliance', $alliance->entity_id, $alliance->name));
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $system = MapDenormalize::firstOrNew(
                        ['itemID' => $this->notification->text['solarSystemID']],
                        ['itemName' => trans('web::seat.unknown'), 'security' => 0]
                    );

                    $field->name('System');
                    $field->value($this->zKillBoardToDiscordLink(
                        'system',
                        $system->itemID,
                        sprintf('%s (%s)', $system->itemName, number_format($system->security, 2))
                    ));
                });
            })
            ->info();
    }
}
