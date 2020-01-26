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

namespace Seat\Notifications\Notifications\Corporations;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Notifications\Notifications\AbstractNotification;

/**
 * Class CharLeftCorpMsg.
 *
 * @package Seat\Notifications\Notifications\Corporations
 */
class CharLeftCorpMsg extends AbstractNotification
{
    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * CharLeftCorpMsg constructor.
     *
     * @param $notification
     */
    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @param $notifiable
     * @return mixed
     */
    public function via($notifiable)
    {
        return ['mail', 'slack'];
    }

    /**
     * @param $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Character Left Corp Notification!')
            ->line('A character has left the corporation!');

        $character = CharacterInfo::find($this->notification->text['charID']);

        $corporation = CorporationInfo::find($this->notification->text['corpID']);

        if (! is_null($corporation) && ! is_null($character)) {

            if (! is_null($corporation)) {

                $mail->line(
                    sprintf('Corporation: %s', $corporation->name)
                );
            }

            if (! is_null($character)) {

                $mail->line(
                    sprintf('Character: %s', $character->name)
                );
            }
        }

        return $mail;
    }

    /**
     * @param $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $message = (new SlackMessage)
            ->content('A character has left corporation!')
            ->from('SeAT CharLeftCorpMsg');

        $character = CharacterInfo::find($this->notification->text['charID']);

        $corporation = CorporationInfo::find($this->notification->text['corpID']);

        if (! is_null($corporation) && ! is_null($character)) {

            $message->attachment(function ($attachment) use ($character, $corporation) {

                if (! is_null($corporation)) {

                    $attachment->field(function ($field) use ($corporation) {

                        $field->title('Corporation')
                            ->content($corporation->name);
                    });
                }

                if (! is_null($character)) {

                    $attachment->field(function ($field) use ($character) {

                        $field->title('Character')
                            ->content($character->name);

                    });
                }
            });

        }

        return $message;
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->notification->text;
    }
}
