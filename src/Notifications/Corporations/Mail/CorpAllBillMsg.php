<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
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
use Illuminate\Support\Collection;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Notifications\Contracts\ExposesRequiredUniverseIds;
use Seat\Notifications\Jobs\Middleware\LoadRequiredUniverseIds;
use Seat\Notifications\Notifications\AbstractMailNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class CorpAllBillMsg.
 *
 * @package Seat\Notifications\Notifications\Corporations
 */
class CorpAllBillMsg extends AbstractMailNotification implements ExposesRequiredUniverseIds
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

    public function middleware(): array
    {
        return array_merge(
            parent::middleware(),
            [new LoadRequiredUniverseIds]
        );
    }

    public function getRequiredUniverseIds(): Collection
    {
        return collect([
            $this->notification->text['debtorID'] ?? null,
            $this->notification->text['creditorID'] ?? null,
        ])->filter()->unique()->values();
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

        $creditor = UniverseName::firstOrNew(
            ['entity_id' => $this->notification->text['creditorID']],
            ['name' => trans('web::seat.unknown')]
        );
        $debtor = UniverseName::firstOrNew(
            ['entity_id' => $this->notification->text['debtorID']],
            ['name' => trans('web::seat.unknown')]
        );

        $mail->line(sprintf('Due to: %s', $creditor->name));
        $mail->line(sprintf('Due by: %s', $debtor->name));

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
