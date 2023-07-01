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

namespace Seat\Notifications\Notifications\Structures\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Notifications\Notifications\AbstractSlackNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class StructureLostArmor.
 *
 * @package Seat\Notifications\Notifications\Structures\Slack
 */
class StructureLostArmor extends AbstractSlackNotification
{
    use NotificationTools;

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * StructureLostArmor constructor.
     *
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
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
            ->content('A Structure lost Armor!')
            ->from('SeAT Structure Monitor')
            ->attachment(function ($attachment) {
                $attachment->field(function ($field) {
                    $system = MapDenormalize::firstOrNew(
                        ['itemID' => $this->notification->text['solarsystemID']],
                        ['itemName' => trans('web::seat.unknown')]
                    );

                    $field->title('System')
                        ->content($this->zKillBoardToSlackLink(
                            'system',
                            $system->itemID,
                            sprintf('%s (%s)', $system->itemName, number_format($system->security, 2))));
                });

                $attachment->field(function ($field) {
                    $type = InvType::firstOrNew(
                        ['typeID' => $this->notification->text['structureTypeID']],
                        ['typeName' => trans('web::seat.unknown')]
                    );

                    $field->title('Structure')
                        ->content($this->zKillBoardToSlackLink('ship', $type->typeID, $type->typeName));
                });
            })
            ->warning();
    }
}
