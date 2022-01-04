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

namespace Seat\Notifications\Notifications\Characters\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Notifications\Notifications\AbstractNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class RaffleFinished.
 *
 * @package Seat\Notifications\Notifications\Characters\Slack
 */
class RaffleFinished extends AbstractNotification
{
    use NotificationTools;

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * RaffleFinished constructor.
     *
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
     */
    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

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
            ->content('A new raffle has been completed!')
            ->from('SeAT Raffle President')
            ->attachment(function ($attachment) {
                $attachment->field(function ($field) {
                    $location = MapDenormalize::firstOrNew(
                        ['itemID' => $this->notification->text['location_id']],
                        ['itemName' => trans('web::seat.unknown')]
                    );

                    $field->title('Location')
                        ->content(
                            $this->zKillBoardToSlackLink(
                                'location',
                                $this->notification->text['location_id'],
                                $location->itemName
                                )
                        );
                });

                $attachment->field(function ($field) {
                    $type = InvType::firstOrNew(
                        ['typeID' => $this->notification->text['typeID']],
                        ['typeName' => trans('web::seat.unknown')]
                    );

                    $field->title('Type')
                        ->content($type->typeName);
                });
            })
            ->attachment(function ($attachment) {
                $attachment->field(function ($field) {
                    $field->title('Ticket Count')
                        ->content($this->notification->text['ticket_count']);
                });

                $attachment->field(function ($field) {
                    $field->title('Ticket Price')
                        ->content(number_format($this->notification->text['ticket_price'], 2));
                });
            })
            ->error();
    }
}
