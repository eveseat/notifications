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

namespace Seat\Notifications\Notifications\Seat\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Notifications\Notifications\AbstractSlackNotification;
use Seat\Web\Models\User;

/**
 * Class NewAccount.
 *
 * @package Seat\Notifications\Notifications\Seat
 */
class CreatedUser extends AbstractSlackNotification
{
    /**
     * @var \Seat\Web\Models\User
     */
    private $user;

    /**
     * CreatedUser constructor.
     *
     * @param  \Seat\Web\Models\User  $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->success()
            ->content('A new SeAT account was created!')
            ->from('SeAT State of Things')
            ->attachment(function ($attachment) {

                $attachment->title('Account Details', route('seatcore::configuration.users.edit', [
                    'user_id' => $this->user->id,
                ]))->fields([
                    'Account Name' => $this->user->name,
                    'Owner Last Login Source' => $this->user->last_login_source,
                    'Owner Last Login Time' => $this->user->last_login,
                ]);
            });
    }
}
