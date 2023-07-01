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

namespace Seat\Notifications\Notifications\Corporations\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Notifications\Notifications\AbstractSlackNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class CorpAllBillMsg.
 *
 * @package Seat\Notifications\Notifications\Corporations
 */
class CorpAllBillMsg extends AbstractSlackNotification
{
    use NotificationTools;

    /**
     * @var CharacterNotification
     */
    private $notification;

    /**
     * CorpAllBillMsg constructor.
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
            ->content('A new corporation bill has been issued!')
            ->from('SeAT Corporation Accountant')
            ->attachment(function ($attachment) {

                $attachment->field(function ($field) {

                    $field->title('Amount')
                        ->content(number_format($this->notification->text['amount'], 2));

                })
                ->field(function ($field) {

                    $field->title('Due Date')
                        ->content($this->mssqlTimestampToDate($this->notification->text['dueDate'])->toRfc7231String());

                });

                $entity = Alliance::find($this->notification->text['creditorID']);

                if (is_null($entity))
                    CorporationInfo::find($this->notification->text['creditorID']);

                if (! is_null($entity))
                    $attachment->field(function ($field) use ($entity) {

                        $field->title('Due To')
                            ->content($entity->name);

                    });

                $entity = Alliance::find($this->notification->text['debtorID']);

                if (is_null($entity))
                    CorporationInfo::find($this->notification->text['debtorID']);

                if (! is_null($entity))
                    $attachment->field(function ($field) use ($entity) {

                        $field->title('Due By')
                            ->content($entity->name);

                    });
            });
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
