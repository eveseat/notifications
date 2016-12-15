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
 * Class Killmail
 * @package Seat\Notifications\Notifications
 */
class Killmail extends Notification
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
                $this->killmail->characterName . ' in ' .
                $this->killmail->corporationName . ' lost a ' .
                $this->killmail->typeName . ' in ' .
                $this->killmail->itemName . ' (' .
                number_format($this->killmail->security, 2) . ')'
            )
            ->action(
                'Check it out on zKillboard',
                'https://zkillboard.com/kill/' . $this->killmail->killID . '/'
            );
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
            ->content('Boom! Someone exploded!')
            ->attachment(function ($attachment) {

                $attachment->title('Check out the details on zKillboard',
                    'https://zkillboard.com/kill/' . $this->killmail->killID . '/')
                    ->fields([
                        'Character Name'        => $this->killmail->characterName,
                        'Character Corporation' => $this->killmail->corporationName,
                        'Loss Ship Type'        => $this->killmail->typeName,
                        'System'                => $this->killmail->itemName,
                        'System Security'       => number_format($this->killmail->security, 2),
                        'Loss / Kill?'          => $this->killmail->victimID == $this->killmail->ownerID ?
                            'Loss' : 'Kill'
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
            'characterName'   => $this->killmail->characterName,
            'corporationName' => $this->killmail->corporationName,
            'typeName'        => $this->killmail->typeName,
            'itemName'        => $this->killmail->itemName,
            'security'        => $this->killmail->security
        ];
    }
}
