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

namespace Seat\Notifications\Notifications\Structures\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Notifications\Jobs\AbstractCharacterNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class AllAnchoringMsg.
 *
 * @package Seat\Notifications\Notifications\Structures\Slack
 */
class AllAnchoringMsg extends AbstractCharacterNotification
{
    use NotificationTools;

    /**
     * @param $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * @param $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->content('A structure is anchoring!')
            ->from('SeAT Structure Monitor')
            ->attachment(function ($attachment) {
                $attachment
                    ->field(function ($field) {
                        $system = MapDenormalize::find($this->notification->text['solarSystemID']);

                        $field->title('System')
                            ->content(
                                $this->zKillBoardToSlackLink(
                                    'system',
                                    $system->itemID,
                                    sprintf('%s (%s)', $system->itemName, number_format($system->security, 2))
                            ));
                    })
                    ->field(function ($field) {
                        $moon = MapDenormalize::find($this->notification->text['moonID']);

                        $field->title('Moon')
                            ->content($moon->itemName);
                    });
            })
            ->attachment(function ($attachment) {
                $attachment
                    ->field(function ($field) {
                        $corporation = UniverseName::firstOrNew(
                            ['entity_id' => $this->notification->text['corpID']],
                            ['name' => trans('web::seat.unkown')]
                        );

                        $field->title('Corporation')
                            ->content($corporation->name);
                    })
                    ->field(function ($field) {
                        $type = InvType::find($this->notification->text['typeID']);

                        $field->title('Structure')
                            ->content(
                                $this->zKillBoardToSlackLink(
                                    'ship',
                                    $type->typeID,
                                    $type->typeName
                                )
                            );
                    })
                    ->thumb($this->typeIconUrl($this->notification->text['typeID']));
            });
    }
}
