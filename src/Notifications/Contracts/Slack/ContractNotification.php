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

namespace Seat\Notifications\Notifications\Contracts\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Eveapi\Models\Contracts\ContractDetail;
use Seat\Notifications\Notifications\AbstractNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class ContractNotification.
 *
 * @package Seat\Notifications\Notifications\Characters
 */
class ContractNotification extends AbstractNotification
{
    use NotificationTools;

    /**
     * @var ContractDetail
     */
    private ContractDetail $contract;

    /**
     * Create a new notification instance.
     *
     * @param  ContractDetail  $contract
     */
    public function __construct(ContractDetail $contract)
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
        $message = (new SlackMessage)
            ->content('A new contract has been created!')
            ->from('SeAT Contract Monitor')
            ->attachment(function ($attachment) {
                $type = $this->contract->type;
                if($type == 'item_exchange'){
                    $type = 'item exchange';
                }

                $attachment
                    ->fields([
                        'Issuer' => $this->contract->issuer->name,
                        'Assignee'  => $this->contract->assignee->name,
                        'Acceptor' => $this->contract->acceptor()->exists() ? $this->contract->acceptor->name : '-',
                        'Type'=> $type,
                        'Status'=> $this->contract->status,
                        'Description'=>$this->contract->title ?? '-',
                        'Issued'=>carbon($this->contract->date_issued)->toDayDateTimeString(),
                        'Completed'=>$this->contract->date_completed ? carbon($this->contract->date_completed)->toDayDateTimeString() : '-',
                    ]);
            });

        return $message;
    }
}
