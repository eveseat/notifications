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

namespace Seat\Notifications\Notifications\Structures\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Notifications\Notifications\AbstractSlackNotification;
use Seat\Notifications\Notifications\Structures\Traits\MoonMiningNotificationTrait;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class AbstractMoonMiningExtraction.
 *
 * @package Seat\Notifications\Notifications\Structures\Slack
 */
abstract class AbstractSlackMoonMiningExtraction extends AbstractSlackNotification
{
    use NotificationTools;
    use MoonMiningNotificationTrait;

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    protected $notification;

    /**
     * @param  \Illuminate\Notifications\Messages\SlackMessage  $message
     */
    protected function addOreAttachments(SlackMessage $message)
    {
        $ore_categories = $this->mapOreToColorsArray();

        foreach ($ore_categories as $color => $ore) {
            if (! empty($ore)) {

                $message->attachment(function ($attachment) use ($color, $ore) {

                    $attachment->color($color);

                    foreach ($ore as $type_id => $volume) {

                        $attachment->field(function ($field) use ($type_id, $volume) {
                            $type = InvType::find($type_id);

                            $field->title($type->typeName)
                                ->content(sprintf('%s m3', number_format($volume, 2)));
                        });
                    }
                });
            }
        }

        return $message;
    }
}
