<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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

namespace Seat\Notifications\Notifications\Wars\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Notifications\Notifications\AbstractNotification;

/**
 * Class AllWarDeclaredMsg.
 *
 * @package Seat\Notifications\Notifications\Corporations\Slack
 */
class AllWarDeclaredMsg extends AbstractNotification
{
    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * AllWarDeclaredMsg constructor.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * @param $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $message = (new SlackMessage())
            ->from('SeAT War Observer')
            ->content('A new War has been declared !')
            ->attachment(function ($attachment) {
                $attachment
                    ->field(function ($field) {
                        $entity = UniverseName::firstOrNew(
                            ['entity_id' => $this->notification->text['declaredByID']],
                            ['name' => trans('web::seat.unknown')]
                        );

                        $field->title('Aggressor')
                            ->content($entity->name);
                    })
                    ->field(function ($field) {
                        $entity = UniverseName::firstOrNew(
                            ['entity_id' => $this->notification->text['againstID']],
                            ['name' => trans('web::seat.unknown')]
                        );

                        $field->title('Defender')
                            ->content($entity->name);
                    });
            })
            ->attachment(function ($attachment) {
                $attachment->field(function ($field) {
                    $field->title('Start in')
                        ->content(sprintf('%d hours', $this->notification->text['delayHours']));
                });
            });

        ($this->notification->text['hostileState']) ?
            $message->error() : $message->warning();

        return $message;
    }
}
