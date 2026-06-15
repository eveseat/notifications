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

namespace Seat\Notifications\Notifications\Structures\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Support\Collection;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Notifications\Contracts\ExposesRequiredUniverseIds;
use Seat\Notifications\Jobs\Middleware\LoadRequiredUniverseIds;
use Seat\Notifications\Notifications\AbstractSlackNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class OwnershipTransferred.
 *
 * @package Seat\Notifications\Notifications\Structures
 */
class OwnershipTransferred extends AbstractSlackNotification implements ExposesRequiredUniverseIds
{
    use NotificationTools;

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * OwnershipTransferred constructor.
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
            $this->notification->text['oldOwnerCorpID'] ?? null,
            $this->notification->text['newOwnerCorpID'] ?? null,
        ])->filter()->unique()->values();
    }

    /**
     * @param  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->content('A structure has been transferred!')
            ->from('SeAT Structure Monitor')
            ->attachment(function ($attachment) {
                $attachment->field(function ($field) {
                    $system = MapDenormalize::firstOrNew(
                        ['itemID' => $this->notification->text['solarSystemID']],
                        ['itemName' => trans('web::seat.unknown'), 'security' => 0]
                    );

                    $field->title('System')
                        ->content(
                            $this->zKillBoardToSlackLink('system', $system->itemID, sprintf('%s (%s)', $system->itemName, number_format($system->security, 2)))
                        );
                });

                $attachment->field(function ($field) {
                    $type = InvType::firstOrNew(
                        ['typeID' => $this->notification->text['structureTypeID']],
                        ['typeName' => trans('web::seat.unknown')]
                    );

                    $field->title('Structure')
                        ->content(sprintf('%s | %s', $type->typeName, $this->notification->text['structureName']));
                });
            })
            ->attachment(function ($attachment) {
                $attachment->field(function ($field) {
                    $old = UniverseName::firstOrNew(
                        ['entity_id' => $this->notification->text['oldOwnerCorpID']],
                        ['category' => 'corporation', 'name' => trans('web::seat.unknown')]
                    );

                    $field->title('Old Corporation')
                        ->content($this->zKillBoardToSlackLink('corporation', $old->entity_id, $old->name));
                });

                $attachment->field(function ($field) {
                    $new = UniverseName::firstOrNew(
                        ['entity_id' => $this->notification->text['newOwnerCorpID']],
                        ['category' => 'corporation', 'name' => trans('web::seat.unknown')]
                    );

                    $field->title('New Corporation')
                        ->content($this->zKillBoardToSlackLink('corporation', $new->entity_id, $new->name));
                });
            });
    }
}
