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

namespace Seat\Notifications\Notifications\Contracts\Discord;

use Seat\Eveapi\Models\Contracts\ContractDetail;
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class ContractNotification.
 *
 * @package Seat\Notifications\Notifications\Characters
 */
class ContractNotification extends AbstractDiscordNotification
{
    use NotificationTools;

    private ContractDetail $contract;

    public function __construct(ContractDetail $contract)
    {
        $this->contract = $contract;
    }

    public function populateMessage(DiscordMessage $message, $notifiable): void
    {
        $message
            ->content('A new event related to a contract has been recorded!')
            ->from('SeAT Contract Monitor')
            ->embed(function (DiscordEmbed $embed) {
                $embed->author('SeAT Contract Monitor', asset('web/img/favico/apple-icon-180x180.png'));

                $type = $this->contract->type;
                if ($type == 'item_exchange') {
                    $type = 'item exchange';
                }

                $embed
                    ->fields([
                        'Issuer' => $this->contract->issuer->name,
                        'Assignee' => $this->contract->assignee->name,
                        'Acceptor' => $this->contract->acceptor()->exists() ? $this->contract->acceptor->name : '-',
                        'Type' => $type,
                        'Status' => $this->contract->status,
                        'Description' => $this->contract->title ?? '-',
                        'Issued' => carbon($this->contract->date_issued)->toDayDateTimeString(),
                        'Completed' => $this->contract->date_completed ? carbon(
                            $this->contract->date_completed
                        )->toDayDateTimeString() : '-',
                    ]);
            });
    }
}
