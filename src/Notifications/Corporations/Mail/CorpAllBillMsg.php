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

namespace Seat\Notifications\Notifications\Corporations\Mail;

use Illuminate\Notifications\Messages\MailMessage;
use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Notifications\Notifications\AbstractMailNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class CorpAllBillMsg.
 *
 * @package Seat\Notifications\Notifications\Corporations
 */
class CorpAllBillMsg extends AbstractMailNotification
{
    use NotificationTools;

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * Constructor.
     *
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
     */
    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @param  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Corporation Bill Notification!')
            ->line('A new corporation bill has been issued!')
            ->line(
                sprintf('Amount: %s - Due on %s',
                    number_format($this->notification->text['amount'], 2),
                    $this->mssqlTimestampToDate($this->notification->text['dueDate'])->toRfc7231String())
            );

        $entity = Alliance::find($this->notification->text['creditorID']);

        if (is_null($entity))
            CorporationInfo::find($this->notification->text['creditorID']);

        if (! is_null($entity))
            $mail->action(
                sprintf('Due to: %s', $entity->name),
                sprintf('https://zkillboard.com/%s/%d', 'corporation', $entity->id));

        $entity = Alliance::find($this->notification->text['debtorID']);

        if (is_null($entity))
            CorporationInfo::find($this->notification->text['debtorID']);

        if (! is_null($entity))
            $mail->action(
                sprintf('Due by: %s', $entity->name),
                sprintf('https://zkillboard.com/%s/%d', 'corporation', $entity->id));

        return $mail;
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
