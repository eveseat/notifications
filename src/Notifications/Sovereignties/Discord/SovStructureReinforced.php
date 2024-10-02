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

namespace Seat\Notifications\Notifications\Sovereignties\Discord;

use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\MapDenormalize;
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
            ->content('A sovereignty structure has been reinforced! :broken_heart:')
            ->embed(function (DiscordEmbed $embed) {
                $embed->timestamp($this->notification->timestamp);
                $embed->author('SeAT Sovereignty Health', asset('web/img/favico/apple-icon-180x180.png'));

                $embed->field(function (DiscordEmbedField $field) {
                    $system = MapDenormalize::find($this->notification->text['solarSystemID']);

                    $field->name('System')
                        ->value($this->zKillBoardToDiscordLink(
                            'system',
                            $system->itemId,
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
