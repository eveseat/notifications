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
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;

class MoonMiningExtractionFinished extends AbstractNotification
{
    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * MoonMiningExtractionFinished constructor.
     *
     * @param $notification
     */
    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    /**
     * @param $notifiable
     * @return mixed
     */
    public function via($notifiable)
    {
        return $notifiable->notificationChannels();
    }

    /**
     * @param $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $data = yaml_parse($this->notification->text);

        $system = MapDenormalize::find($data['solarSystemID']);

        $moon = MapDenormalize::find($data['moonID']);

        $type = InvType::find($data['structureTypeID']);

        $mail = (new MailMessage)
            ->subject('Moon Mining Extraction Finished Notification!')
            ->line(
                sprintf('A Moon Mining Extraction operated on %s (%s) - %s has been successfully completed.',
                    $system->itemName, number_format($system->security, 2), $moon->itemName)
            )
        ->line(
            sprintf('The structure %s (%s) has reported the content bellow:',
                $data['structureName'], $type->typeName)
        );

        foreach ($data['oreVolumeByType'] as $type_id => $volume) {
            $type = InvType::find($type_id);

            $mail->line(
                sprintf(' - %s : %s', $type->typeName, number_format($volume, 2))
            );
        }

        $mail->line(
            sprintf('Hurry up, you have until the %s to collect them!',
                $this->mssqlTimestampToDate($data['autoTime'])->toRfc7231String())
        );

        return $mail;
    }

    /**
     * @param $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $data = yaml_parse($this->notification->text);

        $ore_categories = [
            '#00a65a' => [],  // Gaz
            '#3c8dbc' => [],  // R8
            '#00c0ef' => [],  // R16
            '#f39c12' => [],  // R32
            '#dd4b39' => [],  // R64
            '#d2d6de' => [],  // Ore
        ];

        // build a color per category array
        foreach ($data['oreVolumeByType'] as $type_id => $volume) {
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
            ->attachment(function ($attachment) use ($data) {

                $attachment->field(function ($field) use ($data) {

                    $system = MapDenormalize::find($data['solarSystemID']);

                    $field->title('System')
                        ->content(
                            $this->zKillBoardToSlackLink(
                                'system',
                                $system->itemID,
                                sprintf('%s (%s)', $system->itemName, number_format($system->security, 2))
                            ));

                })->field(function ($field) use ($data) {

                    $moon = MapDenormalize::find($data['moonID']);

                    $field->title('Moon')
                        ->content($moon->itemName);

                })->field(function ($field) use ($data) {

                    $type = InvType::find($data['structureTypeID']);

                    $field->title('Structure')
                        ->content(
                            sprintf('%s (%s)', $data['structureName'], $type->typeName));

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
        return yaml_parse($this->notification->text);
    }
}
