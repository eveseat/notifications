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
use Illuminate\Notifications\Notification;

/**
 * Class Killmail.
 * @package Seat\Notifications\Notifications
 */
class Killmail extends AbstractNotification
{
    /**
     * @var
     */
    private $killmail;

    /**
     * Create a new notification instance.
     *
     * @param $killmail
     */
    public function __construct($killmail)
    {

        $this->killmail = $killmail;
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
            ->subject('Killmail Notification')
            ->line(
                'A new killmail has been recorded!'
            )
            ->line(
                'Lost a ' .
                $this->killmail->killmail_victim->ship_type->typeName . ' in ' .
                $this->killmail->killmail_victim->ship_type->itemName . ' (' .
                number_format($this->killmail->killmail_detail->solar_system->security, 2) . ')'
            )
            ->action(
                'Check it out on zKillboard',
                'https://zkillboard.com/kill/' . $this->killmail->killmail_id . '/'
            );
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param $notifiable
     *
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {

        return (new SlackMessage)
            ->content('A kill has been recorded for your corporation!')
            ->from('SeAT Killmails',
                'https://imageserver.eveonline.com/Type/' . $this->killmail->ship_type_id . '_64.png')
            ->attachment(function ($attachment) {

                $attachment
                    ->timestamp(carbon($this->killmail->killmail_time))
                    ->fields([
                        'Ship Type'   => $this->killmail->typeName,
                        'zKB Link' => 'https://zkillboard.com/kill/' . $this->killmail->killmail_id,
                    ])
                    ->field(function ($field) {

                        $field->title('System')
                            ->content($this->zKillBoardToSlackLink(
                                'system',
                                $this->killmail->killmail_detail->solar_system_id,
                                $this->killmail->killmail_detail->solar_system->itemName . ' (' .
                                number_format($this->killmail->security, 2) . ')'));
                    })
                    ->fallback('Kill details')
                    ->footer('zKillboard')
                    ->footerIcon('https://zkillboard.com/img/wreck.png');
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
            'characterName'   => $this->killmail->characterName,
            'corporationName' => $this->killmail->corporationName,
            'typeName'        => $this->killmail->typeName,
            'itemName'        => $this->killmail->itemName,
            'security'        => $this->killmail->security,
        ];
    }
}
