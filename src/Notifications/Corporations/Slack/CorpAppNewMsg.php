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

namespace Seat\Notifications\Notifications\Corporations\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Support\Collection;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Notifications\Contracts\ExposesRequiredUniverseIds;
use Seat\Notifications\Jobs\Middleware\LoadRequiredUniverseIds;
use Seat\Notifications\Notifications\AbstractSlackNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class CorpAppNewMsg.
 *
 * @package Seat\Notifications\Notifications\Corporations\Slack
 */
class CorpAppNewMsg extends AbstractSlackNotification implements ExposesRequiredUniverseIds
{
    use NotificationTools;

    /**
     * @var CharacterNotification
     */
    private $notification;

    /**
     * CorpAppNewMsg constructor.
     *
     * @param  CharacterNotification  $notification
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
            $this->notification->text['corpID'] ?? null,
            $this->notification->text['charID'] ?? null,
        ])->filter()->unique()->values();
    }

    /**
     * @param  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->from('SeAT - New Application')
            ->content($this->notification->text['applicationText'])
            ->attachment(function ($attachment) {
                $attachment
                    ->field(function ($field) {
                        $corporation = UniverseName::firstOrNew(
                            ['entity_id' => $this->notification->text['corpID']],
                            ['category' => 'corporation', 'name' => trans('web::seat.unknown')]
                        );

                        $field->title('Corporation')
                            ->content(
                                $this->zKillBoardToSlackLink(
                                    'corporation',
                                    $corporation->entity_id,
                                    $corporation->name
                                ));
                    })
                    ->field(function ($field) {
                        $character = UniverseName::firstOrNew(
                            ['entity_id' => $this->notification->text['charID']],
                            ['category' => 'character', 'name' => trans('web::seat.unknown')]
                        );

                        $field->title('Character')
                            ->content(
                                $this->zKillBoardToSlackLink(
                                    'character',
                                    $character->entity_id,
                                    $character->name
                                ));
                    });
            });
    }
}
