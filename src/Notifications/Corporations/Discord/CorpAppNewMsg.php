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

namespace Seat\Notifications\Notifications\Corporations\Discord;

use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class CorpAppNewMsg.
 *
 * @package Seat\Notifications\Notifications\Corporations\Discord
 */
class CorpAppNewMsg extends AbstractDiscordNotification
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
            ->content($this->notification->text['applicationText'])
            ->embed(function (DiscordEmbed $embed) {
                $embed->timestamp(carbon($this->notification->timestamp));

                $embed->author(
                    'SeAT - New Application',
                    asset('web/img/favico/apple-icon-180x180.png'),
                    route('seatcore::corporation.view.default', ['corporation' => $this->notification->text['corpID']])
                );

                $embed->field(function (DiscordEmbedField $field) {
                    $field
                        ->name('Character Name')
                        ->long();

                    $entity = UniverseName::find($this->notification->text['corpID']);
                    if($entity) {
                        $field->value($this->zKillBoardToDiscordLink('character', $entity->entity_id, $entity->name));
                    } else {
                        $field->value(trans('web::seat.unknown'));
                    }
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $field
                        ->name('Character Name')
                        ->long();

                    $entity = UniverseName::find($this->notification->text['charID']);
                    if($entity) {
                        $field->value($this->zKillBoardToDiscordLink('character', $entity->entity_id, $entity->name));
                    } else {
                        $field->value(trans('web::seat.unknown'));
                    }
                });
            })
            ->info();
    }
}
