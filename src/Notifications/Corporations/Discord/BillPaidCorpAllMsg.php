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
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class BillPaidCorpAllMsg.
 *
 * @package Seat\Notifications\Notifications\Corporations\Discord
 */
class BillPaidCorpAllMsg extends AbstractDiscordNotification
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
            ->content('A bill has been honored!')
            ->embed(function (DiscordEmbed $embed) {
                $embed->timestamp(carbon($this->notification->timestamp));
                $embed->author('SeAT Corporation Accountant', asset('web/img/favico/apple-icon-180x180.png'));

                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Amount')
                        ->value(number_format($this->notification->text['amount'], 2));
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Due Date')
                        ->value($this->mssqlTimestampToDate($this->notification->text['dueDate']));
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Character Name')
                        ->value($this->notification->recipient->name)
                        ->long();
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Corporation Name')
                        ->value($this->notification->recipient->affiliation->corporation->name)
                        ->long();
                });
            })
            ->success();
    }
}
