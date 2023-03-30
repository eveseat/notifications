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
use Seat\Eveapi\Models\Contracts\CharacterContract;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Killmails\KillmailDetail;
use Seat\Notifications\Notifications\AbstractNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class CharacterContractNotification.
 *
 * @package Seat\Notifications\Notifications\Characters
 */
class CharacterContractNotification extends AbstractNotification
{
    use NotificationTools;

    /**
     * @var CharacterContract
     */
    private $contract;

    /**
     * Create a new notification instance.
     *
     * @param  CharacterContract  $contract
     */
    public function __construct(CharacterContract $contract)
    {

        $this->contract = $contract;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {

        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $detail = $this->contract->detail;

        $message = (new SlackMessage)
            ->content('A new contract has been created!')
            ->from('SeAT Contract Monitor')
            ->attachment(function ($attachment) use ($detail) {

                $attachment
                    ->timestamp(carbon($detail->date_issued))
                    ->fields([
                        'Issuer' => $detail->issuer->name,
                        'Assignee'  => $detail->assignee->name,
                        'Acceptor' => $detail->acceptor()->exists() ? $detail->acceptor->name : "-",
                        'Type'=> $detail->type,
                        'Status'=> $detail->status,
                        'Description'=>$detail->title ?? "-",
                        'Issued'=>carbon($detail->date_issued)
                    ]);
            });

        return $message;
    }
}
