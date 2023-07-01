<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;

/**
 * Class MoonMiningExtractionFinished.
 *
 * @package Seat\Notifications\Notifications\Structures
 */
class MoonMiningExtractionFinished extends AbstractSlackMoonMiningExtraction
{
    /**
     * MoonMiningExtractionFinished constructor.
     *
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
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
            ->content('A Moon Mining Extraction has been successfully completed.')
            ->from('SeAT Moon Tracker')
            ->attachment(function ($attachment) {

                $attachment->field(function ($field) {

                    $system = MapDenormalize::find($this->notification->text['solarSystemID']);

                    $field->title('System')
                        ->content(
                            $this->zKillBoardToSlackLink(
                                'system',
                                $system->itemID,
                                sprintf('%s (%s)', $system->itemName, number_format($system->security, 2))
                            ));

                })->field(function ($field) {

                    $moon = MapDenormalize::find($this->notification->text['moonID']);

                    $field->title('Moon')
                        ->content($moon->itemName);

                })->field(function ($field) {

                    $type = InvType::find($this->notification->text['structureTypeID']);

                    $field->title('Structure')
                        ->content(
                            sprintf('%s (%s)', $this->notification->text['structureName'], $type->typeName));

                })->field(function ($field) {

                    $field->title('Self Fractured')
                        ->content($this->mssqlTimestampToDate($this->notification->text['autoTime'])->toRfc7231String());

                });
            });

        $this->addOreAttachments($message);

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
