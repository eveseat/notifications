<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017  Leon Jacobs
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
use Illuminate\Notifications\Notification;

/**
 * Class EvePlayerCount.
 * @package Seat\Notifications\Notifications
 */
class EvePlayerCount extends Notification
{
    /**
     * The current playercount object.
     *
     * @var
     */
    private $player_count;

    /**
     * Create a new notification instance.
     *
     * @param $player_count
     */
    public function __construct($player_count)
    {

        $this->player_count = $player_count;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {

        return $notifiable->notificationChannels();
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        return (new MailMessage)
            ->success()
            ->line(
                'The player count is ' . $this->player_count->onlinePlayers .
                ' checked ' .
                carbon($this->player_count->currentTime)->diffForHumans() .
                ' at ' . $this->player_count->currentTime . '!'
            )
            ->action('Check it out on SeAT', route('home'));
    }

    /**
     * @param $notifiable
     *
     * @return $this
     */
    public function toSlack($notifiable)
    {

        return (new SlackMessage)
            ->content(
                'The player count is ' . $this->player_count->onlinePlayers .
                ' checked ' .
                carbon($this->player_count->currentTime)->diffForHumans() .
                ' at ' . $this->player_count->currentTime . '!'
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {

        return [
            'player_count' => $this->player_count->onlinePlayers,
            'calculated'   => carbon($this->player_count->currentTime)
                ->diffForHumans(),
        ];
    }
}
