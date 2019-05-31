<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
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

namespace Seat\Notifications\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;

class SovStructureDestroyed extends AbstractNotification
{
    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    /**
     * @param $notifiable
     * @return mixed
     */
    public function via($notifiable)
    {
        return $notifiable->notificationChannels();
    }

    /**
     * @param $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $data = yaml_parse($this->notification->text);

        $type = InvType::find($data['structureTypeID']);

        $system = MapDenormalize::find($data['solarSystemID']);

        return (new MailMessage)
            ->subject('Sovereignty Structure Destroyed Notification!')
            ->line(
                sprintf('A sovereignty structure has been destroyed (%s)!', $type->typeName))
            ->action(
                sprintf('System : %s (%s)', $system->itemName, number_format($system->security, 2)),
                sprintf('https://zkillboard.com/%s/%d', 'system', $system->itemID));
    }

    /**
     * @param $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $data = yaml_parse($this->notification->text);

        return (new SlackMessage)
            ->content('A sovereignty structure has been destroyed!')
            ->from('SeAT SovStructureDestroyed')
            ->attachment(function ($attachment) use ($data) {

                $attachment->field(function ($field) use ($data) {

                    $system = MapDenormalize::find($data['solarSystemID']);

                    $field->title('System')
                        ->content(
                            $this->zKillBoardToSlackLink(
                                'system',
                                $system->itemID,
                                sprintf('%s (%s)', $system->itemName, number_format($system->security, 2))
                            )
                        );
                })
                ->field(function ($field) use ($data) {

                    $type = InvType::find($data['structureTypeID']);

                    $field->title('Structure')
                        ->content($type->typeName);
                });
            })->error();
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return yaml_parse($this->notification->text);
    }
}
