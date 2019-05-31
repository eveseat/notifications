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
use Illuminate\Notifications\Notification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Universe\UniverseStructure;

class StructureUnderAttack extends Notification
{

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * StructureUnderAttack constructor.
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
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

        $system = MapDenormalize::find($data['solarsystemID']);

        return (new MailMessage)
            ->subject('Structure Under Attack Notification')
            ->line('A structure is under attack!')
            ->line(
                sprintf('Citadel (%s, "%s" attacked')
            )
            ->line(
                sprintf('(%d shield, %d armor, %d hull)',
                    $data['shieldPercentage'],
                    $data['armorPercentage'],
                    $data['hullPercentage'])
            )
            ->line(
                sprintf('in %s by %s',
                    $system->itemName,
                    $data['corpName'])
            );
    }

    /**
     * @param $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {

        $data = yaml_parse($this->notification->text);

        return (new SlackMessage)
            ->content('A structure is under attack!')
            ->from('SeAT StructureUnderAttack')
            ->attachment(function ($attachment) use ($data) {
                $attachment->field(function ($field) use ($data) {
                    $field->title('Attacker')
                        ->content(
                            $this->zKillBoardToSlackLink(
                                'corporation',
                                $data['corpLinkData'][2],
                                $data['corpName']
                            ));
                    })
                    ->field(function ($field) use ($data) {

                        if (! array_key_exists('allianceID', $data))
                            return;

                        $field->title('Alliance')
                            ->content(
                                $this->zKillBoardToSlackLink(
                                    'alliance',
                                    $data['allianceID'],
                                    $data['allianceName']
                                ));
                    })
                    ->field(function ($field) use ($data) {

                        $system = MapDenormalize::find($data['solarsystemID']);

                        $field->title('System')
                            ->content(
                                $this->zKillBoardToSlackLink(
                                    'system',
                                    $system->itemID,
                                    $system->itemName . ' (' . number_format($system->security, 2) . ')'
                                ));
                    })
                    ->field(function ($field) use ($data) {

                        $structure = UniverseStructure::find($data['structureID']);

                        $type = InvType::find($data['structureShowInfoData'][1]);

                        $title = 'Structure';

                        if (! is_null($structure))
                            $title = $structure->name;

                        $field->title($title)
                            ->content($type->typeName);
                    });
            })
            ->attachment(function ($attachment) use ($data) {
                $attachment->field(function ($field) use ($data) {
                    $field->title('Shield')
                        ->content(number_format($data['shieldPercentage'], 2));
                })->color('good');

                if ($data['shieldPercentage'] < 70)
                    $attachment->color('warning');

                if ($data['shieldPercentage'] < 40)
                    $attachment->color('danger');
            })
            ->attachment(function ($attachment) use ($data) {
                $attachment->field(function ($field) use ($data) {
                    $field->title('Armor')
                        ->content(number_format($data['armorPercentage'], 2));
                })->color('good');

                if ($data['armorPercentage'] < 70)
                    $attachment->color('warning');

                if ($data['armorPercentage'] < 40)
                    $attachment->color('danger');
            })
            ->attachment(function ($attachment) use ($data) {
                $attachment->field(function ($field) use ($data) {
                    $field->title('Hull')
                        ->content(number_format($data['hullPercentage'], 2));
                })->color('good');

                if ($data['hullPercentage'] < 70)
                    $attachment->color('warning');

                if ($data['hullPercentage'] < 40)
                    $attachment->color('danger');
            });
    }

    /**
     * @param $notifiable
     * @return mixed
     */
    public function toArray($notifiable)
    {
        return yaml_parse($this->notification->text);
    }

    /**
     * Build a link to zKillboard using Slack message formatting.
     *
     * @param string $type (must be ship, character, corporation or alliance)
     * @param int    $id   the type entity ID
     * @param string $name the type name
     *
     * @return string
     */
    private function zKillBoardToSlackLink(string $type, int $id, string $name)
    {

        if (! in_array($type, ['ship', 'character', 'corporation', 'alliance', 'kill', 'system']))
            return '';

        return sprintf('<https://zkillboard.com/%s/%d/|%s>', $type, $id, $name);
    }
}
