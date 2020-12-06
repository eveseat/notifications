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

namespace Seat\Notifications\Notifications\Corporations\Discord;

use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Notifications\Jobs\AbstractCharacterNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class CorpAllBillMsg.
 *
 * @package Seat\Notifications\Notifications\Corporations\Discord
 */
class CorpAllBillMsg extends AbstractCharacterNotification
{
    use NotificationTools;

    /**
     * @inheritDoc
     */
    public function via($notifiable)
    {
        return ['discord'];
    }

    /**
     * @param $notifiable
     *
     * @return \Seat\Notifications\Services\Discord\Messages\DiscordMessage
     */
    public function toDiscord($notifiable)
    {
        return (new DiscordMessage())
            ->content('A new corporation bill has been issued!')
            ->embed(function (DiscordEmbed $embed) {
                $embed->timestamp($this->notification->timestamp);
                $embed->author('SeAT Corporation Accountant', asset('web/img/favico/apple-icon-180x180.png'));

                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Amount')
                        ->value(number_format($this->notification->text['amount'], 2));
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Due Date')
                        ->value($this->mssqlTimestampToDate($this->notification->text['dueDate'])->toRfc7231String());
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $entity = UniverseName::find($this->notification->text['debtorID']) ?? trans('web::seat.unknown');

                    $field->name('Due By')
                        ->value($entity->name)
                        ->long();
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $entity = UniverseName::find($this->notification->text['creditorID']) ?? trans('web::seat.unknown');

                    $field->name('Due To')
                        ->value($entity->name)
                        ->long();
                });
            })
            ->warning();
    }
}
