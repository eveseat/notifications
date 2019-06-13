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
use Symfony\Component\Yaml\Yaml;

class StructureFuelAlert extends AbstractNotification
{
    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    private $content;

    /**
     * StructureFuelAlert constructor.
     *
     * @param $notification
     */
    public function __construct($notification)
    {
        $this->notification = $notification;
        $this->content = Yaml::parse($this->notification->text);
    }

    /**
     * @param $notifiable
     * @return mixed;
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
        $system = MapDenormalize::find($this->content['solarsystemID']);

        $type = InvType::find($this->content['structureShowInfoData'][1]);

        $mail = (new MailMessage)
            ->subject('Structure Fuel Alert Notification!')
            ->line(
                sprintf('A structure (%s) is running low in fuel in the system %s (%s).',
                    $type->typeName,
                    $system->itemName,
                    number_format($system->security, 2))
            )
            ->line('Find bellow the remaining items :');

        foreach ($this->content['listOfTypesAndQty'] as $item) {
            $type = InvType::find($item[1]);
            $quantity = $item[0];

            $mail->line(
                sprintf(' - %s : %d', $type->typeName, $quantity)
            );
        }

        return $mail;
    }

    /**
     * @param $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->content('A structure is running low in fuel!')
            ->from('SeAT StructureFuelAlert')
            ->attachment(function ($attachment) {
                $attachment->field(function ($field) {
                    $system = MapDenormalize::find($this->content['solarsystemID']);

                    $field->title('System')
                        ->content(
                            $this->zKillBoardToSlackLink(
                                'system',
                                $system->itemID,
                                sprintf('%s (%s)', $system->itemName, number_format($system->security, 2))
                            )
                        );
                });

                $attachment->field(function ($field) {
                    $type = InvType::find($this->content['structureShowInfoData'][1]);

                    $field->title('Structure')
                        ->content($type->typeName);
                });
            })
            ->attachment(function ($attachment) {

                foreach ($this->content['listOfTypesAndQty'] as $item) {

                    $attachment->field(function ($field) use ($item) {
                        $type = InvType::find($item[1]);
                        $quantity = $item[0];

                        $field->title($type->typeName)
                            ->content($quantity);
                    });
                }

                $attachment->color('#439fe0');
            });
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->content;
    }
}
