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

namespace Seat\Notifications\Notifications\Corporations\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Notifications\Notifications\AbstractSlackNotification;

/**
 * Class CharLeftCorpMsg.
 *
 * @package Seat\Notifications\Notifications\Corporations
 */
class CharLeftCorpMsg extends AbstractSlackNotification
{

    /**
     * @var CharacterNotification
     */
    private $notification;

    /**
     * CharLeftCorpMsg constructor.
     *
     * @param  CharacterNotification  $notification
     */
    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @param  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $message = (new SlackMessage)
            ->content('A character has left corporation!')
            ->from('SeAT Corporation Supervisor');

        $character = CharacterInfo::find($this->notification->text['charID']);

        $corporation = CorporationInfo::find($this->notification->text['corpID']);

        if (! is_null($corporation) || ! is_null($character)) {

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
     * @param  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->notification->text;
    }
}
