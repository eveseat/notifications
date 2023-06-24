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

namespace Seat\Notifications\Notifications\Wars\Discord;

use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class AllyJoinedWarDefenderMsg.
 *
 * @package Seat\Notifications\Notifications\Wars\Discord
 */
class AllyJoinedWarDefenderMsg extends AbstractDiscordNotification
{
    use NotificationTools;

    /**
     * @param  $notifiable
     * @return array
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
     * Constructor.
     *
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
     */
    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @param DiscordMessage $message
     * @param $notifiable
     */
    public function populateMessage(DiscordMessage $message, $notifiable)
    {
        $message
            ->content('A new member has been enroll in a war! :boom:')
            ->embed(function (DiscordEmbed $embed) {
                $embed->timestamp($this->notification->timestamp);
                $embed->author('SeAT War Observer', asset('web/img/favico/apple-icon-180x180.png'));

                $embed->field(function (DiscordEmbedField $field) {
                    $aggressor = UniverseName::firstOrNew(
                        ['entity_id' => $this->notification->text['declaredByID']],
                        ['name' => trans('web::seat.unknown')]
                    );

                    $field->name('Aggressor')
                        ->value($this->zKillBoardToDiscordLink(
                            $aggressor->category, $aggressor->entity_id, $aggressor->name));
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $defender = UniverseName::firstOrNew(
                        ['entity_id' => $this->notification->text['defenderID']],
                        ['name' => trans('web::seat.unknown')]
                    );

                    $field->name('Defender')
                        ->value($this->zKillBoardToDiscordLink(
                            $defender->category, $defender->entity_id, $defender->name));
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Start In')
                        ->value($this->mssqlTimestampToDate($this->notification->text['startTime'])->diffForHumans())
                        ->long();
                });
            })
            ->info();
    }
}
