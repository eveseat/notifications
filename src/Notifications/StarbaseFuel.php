<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace Seat\Notifications\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

/**
 * Class StarbaseFuel
 * @package Seat\Notifications\Notifications
 */
class StarbaseFuel extends Notification
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
            ->error()
            ->greeting('Heads up!')
            ->line(
                'The starbase at ' . $this->starbase['location'] . ' is low on fuel!'
            )
            ->line(
                'The ' . $this->starbase['type'] .
                (count($this->starbase['name']) > 0 ? ' ( ' . $this->starbase['name'] . ' )' : '') .
                ' has ' . $this->starbase['fuel_blocks'] . ' fuel blocks left and is estimated to ' .
                'go offline in ' . $this->starbase['hours_left'] . ' hours.'
            )
            ->action('Check it out on SeAT', route('corporation.view.starbases', [
                'corporation_id' => $this->starbase['corporation_id']
            ]));
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param $notifiable
     *
     * @return $this
     */
    public function toSlack($notifiable)
    {

        return (new SlackMessage)
            ->error()
            ->content('A starbase is low on fuel!')
            ->attachment(function ($attachment) {

                $attachment->title('Starbase Details', route('corporation.view.starbases', [
                    'corporation_id' => $this->starbase['corporation_id']
                ]))->fields([
                    'Type'             => $this->starbase['type'],
                    'Location'         => $this->starbase['location'],
                    'Name'             => $this->starbase['name'],
                    'Fuel Block Count' => $this->starbase['fuel_blocks'],
                    'Hours Left'       => $this->starbase['hours_left']
                ]);
            });
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
            'type'             => $this->starbase['type'],
            'location'         => $this->starbase['location'],
            'name'             => $this->starbase['name'],
            'fuel_block_count' => $this->starbase['fuel_blocks'],
            'hours_left'       => $this->starbase['hours_left']
        ];
    }
}
