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

namespace Seat\Notifications\Notifications\Corporations\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Notifications\Notifications\AbstractNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class BillPaidCorpAllMsg.
 *
 * @package Seat\Notifications\Notifications\Corporations\Slack
 */
class BillPaidCorpAllMsg extends AbstractNotification
{
    use NotificationTools;

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * BillPaidCorpAllMsg constructor.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
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
            ->from('SeAT Corporation Accounting')
            ->content('A bill has been honored!')
            ->attachment(function ($attachment) {
                $attachment
                    ->field(function ($field) {
                        $field->title('Amount')
                            ->content(number_format($this->notification->text['amount'], 2));
                    })
                    ->field(function ($field) {
                        $field->title('Due Date')
                            ->content($this->mssqlTimestampToDate($this->notification->text['dueDate']));
                    });
            })
            ->attachment(function ($attachment) {
                $attachment->field(function ($field) {
                    $field->title('Corporation')
                        ->content($this->notification->recipient->name);
                });
            });
    }
}
