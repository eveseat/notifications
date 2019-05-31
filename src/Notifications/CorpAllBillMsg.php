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
use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Eveapi\Models\Corporation\CorporationInfo;

class CorpAllBillMsg extends AbstractNotification
{
    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * CorpAllBillMsg constructor.
     *
     * @param $notification
     */
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

        $mail = (new MailMessage)
            ->subject('Corporation Bill Notification!')
            ->line('A new corporation bill has been issued!')
            ->line(
                sprintf('Amount: %s - Due on %s',
                    number_format($data['amount'], 2),
                    $this->mssqlTimestampToDate($data['dueDate'])->toRfc7231String())
            );

        $entity = Alliance::find($data['creditorID']);

        if (is_null($entity))
            CorporationInfo::find($data['creditorID']);

        if (! is_null($entity))
            $mail->action(
                sprintf('Due to: %s', $entity->name),
                sprintf('https://zkillboard.com/%s/%d', 'corporation', $entity->id));

        $entity = Alliance::find($data['debtorID']);

        if (is_null($entity))
            CorporationInfo::find($data['debtorID']);

        if (! is_null($entity))
            $mail->action(
                sprintf('Due by: %s', $entity->name),
                sprintf('https://zkillboard.com/%s/%d', 'corporation', $entity->id));

        return $mail;
    }

    /**
     * @param $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->content('A new corporation bill has been issued!')
            ->from('SeAT CorpAllBillMsg')
            ->attachment(function ($attachment) {

                $data = yaml_parse($this->notification->text);

                $attachment->field(function ($field) use ($data) {

                    $field->title('Amount')
                        ->content(number_format($data['amount'], 2));

                })
                ->field(function ($field) use ($data) {

                    $field->title('Due Date')
                        ->content($this->mssqlTimestampToDate($data['dueDate'])->toRfc7231String());

                });

                $entity = Alliance::find($data['creditorID']);

                if (is_null($entity))
                    CorporationInfo::find($data['creditorID']);

                if (! is_null($entity))
                    $attachment->field(function ($field) use ($entity) {

                        $field->title('Due To')
                            ->content($entity->name);

                    });

                $entity = Alliance::find($data['debtorID']);

                if (is_null($entity))
                    CorporationInfo::find($data['debtorID']);

                if (! is_null($entity))
                    $attachment->field(function ($field) use ($entity) {

                        $field->title('Due By')
                            ->content($entity->name);

                    });
            });
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
