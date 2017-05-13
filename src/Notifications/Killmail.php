<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017  Leon Jacobs
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

use Carbon\Carbon;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Seat\Eveapi\Models\Corporation\CorporationSheet;
use Seat\Eveapi\Models\KillMail\Attacker;

/**
 * Class Killmail.
 * @package Seat\Notifications\Notifications
 */
class Killmail extends Notification
{
    /**
     * @var
     */
    private $killmail;

    /**
     * Create a new notification instance.
     *
     * @param $killmail
     */
    public function __construct($killmail)
    {

        $this->killmail = $killmail;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {

        return $notifiable->notificationChannels();
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        return (new MailMessage)
            ->subject('Killmail Notification')
            ->line(
                'A new killmail has been recorded!'
            )
            ->line(
                $this->killmail->characterName . ' in ' .
                $this->killmail->corporationName . ' lost a ' .
                $this->killmail->typeName . ' in ' .
                $this->killmail->itemName . ' (' .
                number_format($this->killmail->security, 2) . ')'
            )
            ->action(
                'Check it out on zKillboard',
                'https://zkillboard.com/kill/' . $this->killmail->killID . '/'
            );
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {

        return (new SlackMessage)
            ->content($this->toSlackContent())
            ->from('SeAT', 'https://imageserver.eveonline.com/Type/' . $this->killmail->shipTypeID . '_64.png')
            ->attachment(function ($attachment) use ($notifiable) {

                $attachment->timestamp(Carbon::parse($this->killmail->killTime))
                    ->field(function ($field) {
                        $this->toSlackVictimField($field);
                    })
                    ->field(function ($field) {
                        $this->toSlackFinalBlowField($field);
                    })
                    ->field(function ($field) {
                        $field->title('System')
                            ->content($this->zKillBoardToSlackLink(
                                'system',
                                $this->killmail->itemID,
                                $this->killmail->itemName . ' (' . number_format($this->killmail->security, 2) . ')'));
                    })
                    /* TODO : exploit ESI adding a Job on markets/prices | then we will be able to display a price
                    ->field(function($field){
                        $field->title('Price')
                            ->content('N/A');
                    })
                    */
                    ->color(($this->killmail->victimID == $this->killmail->ownerID) ? 'danger' : 'good')
                    ->fallback('Kill details')
                    ->footer('zKillboard')
                    ->footerIcon('https://zkillboard.com/img/wreck.png');

                // TODO : uncomment for Laravel 5.5 -> https://github.com/laravel/framework/pull/19200
                //$attachment->thumb('https://imageserver.eveonline.com/Type/'. $this->killmail->shipTypeID . '_64.png');

                logger()->debug($attachment->content);
            });

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {

        return [
            'characterName'   => $this->killmail->characterName,
            'corporationName' => $this->killmail->corporationName,
            'typeName'        => $this->killmail->typeName,
            'itemName'        => $this->killmail->itemName,
            'security'        => $this->killmail->security,
        ];
    }

    private function toSlackContent()
    {
        $attackers = Attacker::where('killID', $this->killmail->killID)
            ->get();

        $finalBlow = $attackers->where('finalBlow', true)->first();

        // the killmail refer to a loose
        if ($this->killmail->victimID == $this->killmail->ownerID) {

            // default, all kill have a corporation
            $content = sprintf('*%s* has lost a %s',
                $this->killmail->corporationName,
                $this->killmail->typeName);

            // special case for non NPC kill using the characterName instead
            if ($this->killmail->characterID != 0)
                $content = sprintf('*%s* has lost its %s',
                    $this->killmail->characterName,
                    $this->killmail->typeName);

            // exclude self kill and pods for custom flavor
            if ($attackers->count() == 1 && $this->killmail->shipTypeID != 670)
            {
                if ($finalBlow->characterID == 0)
                    $content .= ' against a NPC';

                // special case for non NPC kill using the ship name
                if ($finalBlow->characterID != 0)
                    $content .= ' in a versus against ' . $finalBlow->typeName;
            }
        // the killmail refer to a kill
        } else {
            $content = sprintf('*%s* has killed a %s in a versus',
                $finalBlow->characterName,
                $this->killmail->typeName);

            if ($attackers->count() > 1) {

                // filter killer based on corporations which are in SeAT
                $corporations = CorporationSheet::all()
                    ->pluck('corporationID')
                    ->toArray();

                $attackersContent = new Collection();

                foreach ($attackers as $attacker)
                    if (in_array($attacker->corporationID, $corporations))
                        $attackersContent->push($attacker->characterName);

                // by default, assume there is only one guy on the kill
                $content = sprintf('*%s* has killed a %s',
                    $attackersContent->first(),
                    $this->killmail->typeName);

                // if we have more than one character, merge them in a single string
                if ($attackersContent->count() > 1) {
                    $otherAttackers = $attackersContent->splice(1);

                    $content = sprintf('*%s* and *%s* have killed a %s',
                        $otherAttackers->implode('*, *'),
                        $attackersContent->first(),
                        $this->killmail->typeName);
                }
            }
        }

        return sprintf('%s !',
            $this->zKillBoardToSlackLink('kill', $this->killmail->killID, $content));
    }

    /**
     * Build a custom field which will be use as a field into the Slack Attachment message
     * for Victim information.
     *
     * @param \Illuminate\Notifications\Messages\SlackAttachmentField $field
     * @return \Illuminate\Notifications\Messages\SlackAttachmentField
     */
    private function toSlackVictimField($field)
    {
        $value = $this->zKillBoardToSlackLink('ship', $this->killmail->shipTypeID, $this->killmail->typeName);

        if ($this->killmail->characterID != 0) {
            $value = sprintf('%s (%s)',
                $this->zKillBoardToSlackLink('character', $this->killmail->characterID, $this->killmail->characterName),
                $value);
        }

        $value = sprintf('%s | %s',
            $value,
            $this->zKillBoardToSlackLink('corporation', $this->killmail->corporationID, $this->killmail->corporationName));

        if ($this->killmail->allianceID != 0)
        {
            $value = sprintf('%s | %s',
                $value,
                $this->zKillBoardToSlackLink('alliance', $this->killmail->allianceID, $this->killmail->allianceName));
        }

        return $field->title('Victim')
            ->content($value)
            ->long();
    }

    /**
     * Build a custom field which will be use as a field into the Slack Attachment message
     * for Final Blow information.
     *
     * @param \Illuminate\Notifications\Messages\SlackAttachmentField $field
     * @return \Illuminate\Notifications\Messages\SlackAttachmentField
     */
    private function toSlackFinalBlowField($field)
    {
        $attackers = Attacker::where('killID', $this->killmail->killID)
            ->leftJoin(
                'invTypes',
                'kill_mail_attackers.shipTypeID', '=',
                'invTypes.typeID')
            ->get();

        $finalBlow = $attackers->where('finalBlow', true)->first();

        $value = $this->zKillBoardToSlackLink('ship', $finalBlow->shipTypeID, $finalBlow->typeName);

        if ($finalBlow->characterID != 0)
        {
            $value = sprintf('%s (%s) | %s',
                $this->zKillBoardToSlackLink('character', $finalBlow->characterID, $finalBlow->characterName),
                $value,
                $this->zKillBoardToSlackLink('corporation', $finalBlow->corporationID, $finalBlow->corporationName));

            if ($finalBlow->allianceID != 0)
                $value = sprintf('%s | %s',
                    $value,
                    $this->zKillBoardToSlackLink('alliance', $finalBlow->allianceID, $finalBlow->allianceName));
        }

        return $field->title(sprintf('Final blow (%d attackers)', $attackers->count()))
            ->content($value)
            ->long();
    }

    /**
     * Build a link to zKillboard using Slack message formatting.
     *
     * @param string $type (must be ship, character, corporation or alliance)
     * @param int $id the type entity ID
     * @param string $name the type name
     * @return string
     */
    private function zKillBoardToSlackLink(string $type, int $id, string $name)
    {
        if (! in_array($type, ['ship', 'character', 'corporation', 'alliance', 'kill', 'system']))
            return '';

        return sprintf('<https://zkillboard.com/%s/%d/|%s>', $type, $id, $name);
    }
}
