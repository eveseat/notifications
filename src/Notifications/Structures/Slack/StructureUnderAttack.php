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
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Notifications\Notifications\AbstractNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class StructureUnderAttack.
 *
 * @package Seat\Notifications\Notifications\Structures
 */
class StructureUnderAttack extends AbstractNotification
{
    use NotificationTools;

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * StructureUnderAttack constructor.
     *
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
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

        return ['slack'];
    }

    /**
     * @param $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->content('A structure is under attack!')
            ->from('SeAT Structure Monitor')
            ->attachment(function ($attachment) {
                $attachment->field(function ($field) {
                    $field->title('Attacker')
                        ->content(
                            $this->zKillBoardToSlackLink(
                                'corporation',
                                $this->notification->text['corpLinkData'][2],
                                $this->notification->text['corpName']
                            ));
                    })
                    ->field(function ($field) {

                        if (! array_key_exists('allianceID', $this->notification->text) || is_null($this->notification->text['allianceID']))
                            return;

                        $field->title('Alliance')
                            ->content(
                                $this->zKillBoardToSlackLink(
                                    'alliance',
                                    $this->notification->text['allianceID'],
                                    $this->notification->text['allianceName']
                                ));
                    })
                    ->field(function ($field) {

                        $system = MapDenormalize::find($this->notification->text['solarsystemID']);

                        $field->title('System')
                            ->content(
                                $this->zKillBoardToSlackLink(
                                    'system',
                                    $system->itemID,
                                    $system->itemName . ' (' . number_format($system->security, 2) . ')'
                                ));
                    })
                    ->field(function ($field) {

                        $structure = UniverseStructure::find($this->notification->text['structureID']);

                        $type = InvType::find($this->notification->text['structureShowInfoData'][1]);

                        $title = 'Structure';

                        if (! is_null($structure))
                            $title = $structure->name;

                        $field->title($title)
                            ->content($type->typeName);
                    });
            })
            ->attachment(function ($attachment) {
                $attachment->field(function ($field) {
                    $field->title('Shield')
                        ->content(number_format($this->notification->text['shieldPercentage'], 2));
                })->color('good');

                if ($this->notification->text['shieldPercentage'] < 70)
                    $attachment->color('warning');

                if ($this->notification->text['shieldPercentage'] < 40)
                    $attachment->color('danger');
            })
            ->attachment(function ($attachment) {
                $attachment->field(function ($field) {
                    $field->title('Armor')
                        ->content(number_format($this->notification->text['armorPercentage'], 2));
                })->color('good');

                if ($this->notification->text['armorPercentage'] < 70)
                    $attachment->color('warning');

                if ($this->notification->text['armorPercentage'] < 40)
                    $attachment->color('danger');
            })
            ->attachment(function ($attachment) {
                $attachment->field(function ($field) {
                    $field->title('Hull')
                        ->content(number_format($this->notification->text['hullPercentage'], 2));
                })->color('good');

                if ($this->notification->text['hullPercentage'] < 70)
                    $attachment->color('warning');

                if ($this->notification->text['hullPercentage'] < 40)
                    $attachment->color('danger');
            });
    }

    /**
     * @param $notifiable
     * @return mixed
     */
    public function toArray($notifiable)
    {
        return $this->notification->text;
    }
}
