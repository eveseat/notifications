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

namespace Seat\Notifications\Notifications\Characters\Discord;

use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class RaffleCreated.
 *
 * @package Seat\Notifications\Notifications\Characters\Slack
 */
class RaffleCreated extends AbstractDiscordNotification
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
            ->content('A new raffle has been created!')
            ->embed(function (DiscordEmbed $embed) {
                $embed->author('SeAT Raffle President', asset('web/img/favico/apple-icon-180x180.png'));

                $embed->field(function (DiscordEmbedField $field) {
                    $location = MapDenormalize::firstOrNew(
                        ['itemID' => $this->notification->text['location_id']],
                        ['itemName' => trans('web::seat.unknown')]
                    );

                    $field->name('Location')
                        ->value(
                            $this->zKillBoardToSlackLink(
                                'location',
                                $this->notification->text['location_id'],
                                $location->itemName
                                )
                        );
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $type = InvType::firstOrNew(
                        ['typeID' => $this->notification->text['typeID']],
                        ['typeName' => trans('web::seat.unknown')]
                    );

                    $field->name('Type')
                        ->value($type->typeName);
                });
            })
            ->embed(function (DiscordEmbed $embed) {
                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Ticket Count')
                        ->value($this->notification->text['ticket_count']);
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Ticket Price')
                        ->value(number_format($this->notification->text['ticket_price'], 2));
                });
            })
            ->success();
    }
}
