<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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

use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Eveapi\Models\Sde\SolarSystem;
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
class AllianceCapitalChanged extends AbstractDiscordNotification
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

    /**
     * @param  DiscordMessage  $message
     * @param  $notifiable
     */
    public function populateMessage(DiscordMessage $message, $notifiable)
    {
        $message
            ->content('Capital has been modified! :white_sun_small_cloud:')
            ->embed(function (DiscordEmbed $embed) {
                $embed->timestamp($this->notification->timestamps);
                $embed->author('SeAT Alliance Weather', asset('web/img/favico/apple-icon-180x180.png'));

                $embed->field(function (DiscordEmbedField $field) {
                    $alliance = Alliance::firstOrNew(
                        ['alliance_id' => $this->notification->text['allianceID']],
                        ['name' => trans('web::seat.unknown')],
                    );

                    $field->name('Alliance');
                    $field->value($this->zKillBoardToDiscordLink('alliance', $alliance->alliance_id, $alliance->name));
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $system = SolarSystem::find($this->notification->text['solarSystemID']);

                    $field->name('System');
                    $field->value($this->zKillBoardToDiscordLink(
                        'system',
                        $system->system_id,
                        sprintf('%s (%s)', $system->name, number_format($system->security, 2))
                    ));
                });
            })
            ->info();
    }
}
