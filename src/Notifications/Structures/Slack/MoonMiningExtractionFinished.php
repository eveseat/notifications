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

namespace Seat\Notifications\Notifications\Structures\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Notifications\Notifications\AbstractNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class MoonMiningExtractionFinished.
 *
 * @package Seat\Notifications\Notifications\Structures
 */
class MoonMiningExtractionFinished extends AbstractNotification
{
    use NotificationTools;

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * MoonMiningExtractionFinished constructor.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @param $notifiable
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
        $ore_categories = [
            '#00a65a' => [],  // Gaz
            '#3c8dbc' => [],  // R8
            '#00c0ef' => [],  // R16
            '#f39c12' => [],  // R32
            '#dd4b39' => [],  // R64
            '#d2d6de' => [],  // Ore
        ];

        // build a color per category array
        foreach ($this->notification->text['oreVolumeByType'] as $type_id => $volume) {
            $type = InvType::find($type_id);

            switch ($type->marketGroupID) {
                // Gaz
                case 2396:
                    $ore_categories['#00a65a'][$type_id] = $volume;
                    break;
                // R8
                case 2397:
                    $ore_categories['#3c8dbc'][$type_id] = $volume;
                    break;
                // R16
                case 2398:
                    $ore_categories['#00c0ef'][$type_id] = $volume;
                    break;
                // R32
                case 2400:
                    $ore_categories['#f39c12'][$type_id] = $volume;
                    break;
                // R64
                case 2401:
                    $ore_categories['#dd4b39'][$type_id] = $volume;
                    break;
                // Ore
                default:
                    $ore_categories['#d2d6de'][$type_id] = $volume;
            }
        }

        $message = (new SlackMessage)
            ->content('A Moon Mining Extraction has been successfully completed.')
            ->from('SeAT MoonMiningExtractionFinished')
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

        foreach ($ore_categories as $color => $ore) {
            if (! empty($ore)) {

                $message->attachment(function ($attachment) use ($color, $ore) {

                    $attachment->color($color);

                    foreach ($ore as $type_id => $volume) {

                        $attachment->field(function ($field) use ($type_id, $volume) {

                            $type = InvType::find($type_id);

                            $field->title($type->typeName)
                                ->content(
                                    sprintf('%s m3', number_format($volume, 2)));

                        });
                    }
                });
            }
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
