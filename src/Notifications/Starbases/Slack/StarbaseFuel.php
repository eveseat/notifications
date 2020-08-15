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

namespace Seat\Notifications\Notifications\Starbases\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Notifications\Notifications\AbstractNotification;

/**
 * Class StarbaseFuel.
 *
 * @package Seat\Notifications\Notifications\Starbases
 * @deprecated 4.0.0
 */
class StarbaseFuel extends AbstractNotification
{
    /**
     * @var
     */
    private $starbase;

    /**
     * Create a new notification instance.
     *
     * @param $starbase
     */
    public function __construct($starbase)
    {

        $this->starbase = $starbase;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
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
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {

        return (new SlackMessage)
            ->error()
            ->content('A starbase is low on fuel!')
            ->from('SeAT Death Star')
            ->attachment(function ($attachment) {

                $attachment->title('Starbase Details', route('corporation.view.starbases', [
                    'corporation_id' => $this->starbase['corporation_id'],
                ]))->fields([
                    'Type'             => $this->starbase['type'],
                    'Location'         => $this->starbase['location'],
                    'Name'             => $this->starbase['name'],
                    'Fuel Block Count' => $this->starbase['fuel_blocks'],
                    'Hours Left'       => $this->starbase['hours_left'],
                ]);
            });
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {

        return [
            'type'             => $this->starbase['type'],
            'location'         => $this->starbase['location'],
            'name'             => $this->starbase['name'],
            'fuel_block_count' => $this->starbase['fuel_blocks'],
            'hours_left'       => $this->starbase['hours_left'],
        ];
    }
}
