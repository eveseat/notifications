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

namespace Seat\Notifications\Notifications\Sovereignties\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Notifications\Notifications\AbstractSlackNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class SovStructureDestroyed.
 *
 * @package Seat\Notifications\Notifications\Sovereignties
 */
class SovStructureDestroyed extends AbstractSlackNotification
{
    use NotificationTools;

    /**
     * @var CharacterNotification
     */
    private $notification;

    /**
     * SovStructureDestroyed constructor.
     *
     * @param  CharacterNotification  $notification
     */
    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @param  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->content('A sovereignty structure has been destroyed!')
            ->from('SeAT Sovereignty Health')
            ->attachment(function ($attachment) {

                $attachment->field(function ($field) {

                    $system = MapDenormalize::find($this->notification->text['solarSystemID']);

                    $field->title('System')
                        ->content(
                            $this->zKillBoardToSlackLink(
                                'system',
                                $system->itemID,
                                sprintf('%s (%s)', $system->itemName, number_format($system->security, 2))
                            )
                        );
                })
                ->field(function ($field) {

                    $type = InvType::find($this->notification->text['structureTypeID']);

                    $field->title('Structure')
                        ->content($type->typeName);
                });
            })->error();
    }

    /**
     * @param  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->notification->text;
    }
}
