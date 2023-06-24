<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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

namespace Seat\Notifications\Notifications\Sovereignties\Discord;

use Seat\Eveapi\Models\Sde\SolarSystem;
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class SovStructureReinforced.
 *
 * @package Seat\Notifications\Notifications\Sovereignties\Discord
 */
class SovStructureReinforced extends AbstractDiscordNotification
{
    use NotificationTools;

    /**
     * @param $notifiable
     * @return mixed
     */
    public function via($notifiable)
    {
        return ['discord'];
    }

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * Constructor
     *
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
     */
    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @param $notifiable
     *
     * @return \Seat\Notifications\Services\Discord\Messages\DiscordMessage
     */
    public function toDiscord($notifiable)
    {
        return (new DiscordMessage())
            ->content('A sovereignty structure has been reinforced! :broken_heart:')
            ->embed(function (DiscordEmbed $embed) {
                $embed->timestamp($this->notification->timestamp);
                $embed->author('SeAT Sovereignty Health', asset('web/img/favico/apple-icon-180x180.png'));

                $embed->field(function (DiscordEmbedField $field) {
                    $system = SolarSystem::find($this->notification->text['solarSystemID']);

                    $field->name('System')
                        ->value($this->zKillBoardToDiscordLink(
                            'system',
                            $system->itemID,
                            sprintf('%s (%s)', $system->itemName, number_format($system->security, 2))));
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Structure')
                        ->value($this->campaignEventType($this->notification->text['campaignEventType']));
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Node Decloak')
                        ->value($this->mssqlTimestampToDate($this->notification->text['decloakTime'])->toRfc7231String())
                        ->long();
                });
            })
            ->warning();
    }
}
