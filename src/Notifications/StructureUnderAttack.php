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
use Symfony\Component\Yaml\Yaml;

class StructureUnderAttack extends Notification
{

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterNotification
     */
    private $notification;

    /**
     * @var mixed
     */
    private $content;

    /**
     * StructureUnderAttack constructor.
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    public function __construct($notification)
    {
        $this->notification = $notification;
        $this->content = Yaml::parse($this->notification->text);
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
        $system = MapDenormalize::find($this->content['solarsystemID']);

        return (new MailMessage)
            ->subject('Structure Under Attack Notification')
            ->line('A structure is under attack!')
            ->line(
                sprintf('Citadel (%s, "%s" attacked')
            )
            ->line(
                sprintf('(%d shield, %d armor, %d hull)',
                    $this->content['shieldPercentage'],
                    $this->content['armorPercentage'],
                    $this->content['hullPercentage'])
            )
            ->line(
                sprintf('in %s by %s',
                    $system->itemName,
                    $this->content['corpName'])
            );
    }

    /**
     * @param $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->content('A structure is under attack!')
            ->from('SeAT StructureUnderAttack')
            ->attachment(function ($attachment) {
                $attachment->field(function ($field) {
                    $field->title('Attacker')
                        ->content(
                            $this->zKillBoardToSlackLink(
                                'corporation',
                                $this->content['corpLinkData'][2],
                                $this->content['corpName']
                            ));
                    })
                    ->field(function ($field) {

                        if (! array_key_exists('allianceID', $this->content) || is_null($this->content['allianceID']))
                            return;

                        $field->title('Alliance')
                            ->content(
                                $this->zKillBoardToSlackLink(
                                    'alliance',
                                    $this->content['allianceID'],
                                    $this->content['allianceName']
                                ));
                    })
                    ->field(function ($field) {

                        $system = MapDenormalize::find($this->content['solarsystemID']);

                        $field->title('System')
                            ->content(
                                $this->zKillBoardToSlackLink(
                                    'system',
                                    $system->itemID,
                                    $system->itemName . ' (' . number_format($system->security, 2) . ')'
                                ));
                    })
                    ->field(function ($field) {

                        $structure = UniverseStructure::find($this->content['structureID']);

                        $type = InvType::find($this->content['structureShowInfoData'][1]);

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
                        ->content(number_format($this->content['shieldPercentage'], 2));
                })->color('good');

                if ($this->content['shieldPercentage'] < 70)
                    $attachment->color('warning');

                if ($this->content['shieldPercentage'] < 40)
                    $attachment->color('danger');
            })
            ->attachment(function ($attachment) {
                $attachment->field(function ($field) {
                    $field->title('Armor')
                        ->content(number_format($this->content['armorPercentage'], 2));
                })->color('good');

                if ($this->content['armorPercentage'] < 70)
                    $attachment->color('warning');

                if ($this->content['armorPercentage'] < 40)
                    $attachment->color('danger');
            })
            ->attachment(function ($attachment) {
                $attachment->field(function ($field) {
                    $field->title('Hull')
                        ->content(number_format($this->content['hullPercentage'], 2));
                })->color('good');

                if ($this->content['hullPercentage'] < 70)
                    $attachment->color('warning');

                if ($this->content['hullPercentage'] < 40)
                    $attachment->color('danger');
            });
    }

    /**
     * @param $notifiable
     * @return mixed
     */
    public function toArray($notifiable)
    {
        return $this->content;
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
