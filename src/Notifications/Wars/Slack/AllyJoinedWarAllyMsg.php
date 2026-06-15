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

namespace Seat\Notifications\Notifications\Wars\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Support\Collection;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Notifications\Contracts\ExposesRequiredUniverseIds;
use Seat\Notifications\Jobs\Middleware\LoadRequiredUniverseIds;
use Seat\Notifications\Notifications\AbstractSlackNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class AllyJoinedWarAllyMsg.
 *
 * @package Seat\Notifications\Notifications\Wars\Slack
 */
class AllyJoinedWarAllyMsg extends AbstractSlackNotification implements ExposesRequiredUniverseIds
{
    use NotificationTools;

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * AllyJoinedWarAllyMsg constructor.
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
            $this->notification->text['aggressorID'] ?? $this->notification->text['declaredByID'] ?? null,
            $this->notification->text['defenderID'] ?? $this->notification->text['againstID'] ?? null,
        ])->filter()->unique()->values();
    }

    /**
     * @param  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->from('Slack War Observer')
            ->content('A new member has been enroll in a war!')
            ->attachment(function ($attachment) {
                $attachment
                    ->timestamp($this->mssqlTimestampToDate($this->notification->text['startTime']))
                    ->field(function ($field) {
                        $aggressor_id = $this->notification->text['aggressorID'] ?? $this->notification->text['declaredByID'];
                        $entity = UniverseName::firstOrNew(
                            ['entity_id' => $aggressor_id],
                            ['name' => trans('web::seat.unknown')]
                        );

                        $field->title('Aggressor')
                            ->content($entity->name);
                    })
                    ->field(function ($field) {
                        $defender_id = $this->notification->text['defenderID'] ?? $this->notification->text['againstID'];
                        $entity = UniverseName::firstOrNew(
                            ['entity_id' => $defender_id],
                            ['name' => trans('web::seat.unknown')]
                        );

                        $field->title('Defender')
                            ->content($entity->name);
                    });
            });
    }
}
