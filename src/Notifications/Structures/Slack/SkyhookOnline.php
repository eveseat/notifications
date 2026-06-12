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
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Notifications\Notifications\AbstractSlackNotification;
use Seat\Notifications\Notifications\Structures\Traits\SkyhookNotificationTools;
use Seat\Notifications\Traits\NotificationTools;

class SkyhookOnline extends AbstractSlackNotification
{
    use NotificationTools;
    use SkyhookNotificationTools;

    private $notification;

    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    public function toSlack($notifiable)
    {
        $system = $this->getSkyhookSystem();
        $planet = $this->getSkyhookPlanet();
        $type = $this->getSkyhookType();

        return (new SlackMessage)
            ->content('A Skyhook went online!')
            ->from('SeAT Structure Monitor')
            ->attachment(function ($attachment) use ($system, $planet) {
                $attachment->field(function ($field) use ($system) {
                    $field->title('System')
                        ->content($this->zKillBoardToSlackLink(
                            'system',
                            $system->itemID,
                            sprintf('%s (%s)', $system->itemName, number_format($system->security, 2))
                        ));
                });

                $attachment->field(function ($field) use ($planet) {
                    $field->title('Planet')
                        ->content($planet->itemName);
                });
            })
            ->attachment(function ($attachment) use ($type) {
                $attachment->field(function ($field) use ($type) {
                    $field->title($this->getSkyhookName())
                        ->content($this->zKillBoardToSlackLink('ship', $type->typeID, $type->typeName));
                });
            });
    }
}
