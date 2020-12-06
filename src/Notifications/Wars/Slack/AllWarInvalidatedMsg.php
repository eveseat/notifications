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
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Notifications\Jobs\AbstractCharacterNotification;

/***
 * Class AllWarInvalidatedMsg.
 *
 * @package Seat\Notifications\Notifications\Corporations\Slack
 */
class AllWarInvalidatedMsg extends AbstractCharacterNotification
{
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
        return (new SlackMessage)
            ->from('SeAT War Observer')
            ->content('A war has been invalidated!')
            ->attachment(function ($attachment) {
                $attachment
                    ->field(function ($field) {
                        $entity = UniverseName::firstOrNew(
                            ['entity_id' => $this->notification->text['declaredByID']],
                            ['name' => trans('web::seat.unknown')]
                        );

                        $field->title('Agressor')
                            ->content($entity->name);
                    })
                    ->field(function ($field) {
                        $entity = UniverseName::firstOrNew(
                            ['entity_id' => $this->notification->text['againstID']],
                            ['name' => trans('web::seat.unknown')]
                        );

                        $field->title('Victim')
                            ->content($entity->name);
                    });
            })
            ->attachment(function ($attachment) {
                $attachment->field(function ($field) {
                    $field->title('Ends in')
                        ->content(sprintf('%d hours', $this->notification->text['delayHours']));
                });
            });
    }
}
