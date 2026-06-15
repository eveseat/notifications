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

namespace Seat\Notifications\Notifications\Corporations\Discord;

use Illuminate\Support\Collection;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Notifications\Contracts\ExposesRequiredUniverseIds;
use Seat\Notifications\Jobs\Middleware\LoadRequiredUniverseIds;
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class CorpAllBillMsg.
 *
 * @package Seat\Notifications\Notifications\Corporations\Discord
 */
class CorpAllBillMsg extends AbstractDiscordNotification implements ExposesRequiredUniverseIds
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
            $this->notification->text['debtorID'] ?? null,
            $this->notification->text['creditorID'] ?? null,
        ])->filter()->unique()->values();
    }

    /**
     * @param  DiscordMessage  $message
     * @param  $notifiable
     */
    public function populateMessage(DiscordMessage $message, $notifiable)
    {
        $message
            ->content('A new corporation bill has been issued!')
            ->embed(function (DiscordEmbed $embed) {
                $embed->timestamp($this->notification->timestamp);
                $embed->author('SeAT Corporation Accountant', asset('web/img/favicon/apple-icon-180x180.png'));

                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Amount')
                        ->value(number_format($this->notification->text['amount'], 2));
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Due Date')
                        ->value($this->mssqlTimestampToDate($this->notification->text['dueDate'])->toRfc7231String());
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $entity = UniverseName::firstOrNew(
                        ['entity_id' => $this->notification->text['debtorID']],
                        ['name' => trans('web::seat.unknown')]
                    );

                    $field->name('Due By')
                        ->value(is_null($entity->category) ?
                            $entity->name :
                            $this->zKillBoardToDiscordLink($entity->category, $entity->entity_id, $entity->name))
                        ->long();
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $entity = UniverseName::firstOrNew(
                        ['entity_id' => $this->notification->text['creditorID']],
                        ['name' => trans('web::seat.unknown')]
                    );

                    $field->name('Due To')
                        ->value(is_null($entity->category) ?
                            $entity->name :
                            $this->zKillBoardToDiscordLink($entity->category, $entity->entity_id, $entity->name))
                        ->long();
                });
            })
            ->warning();
    }
}
