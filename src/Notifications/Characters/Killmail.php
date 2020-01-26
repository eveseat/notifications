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

namespace Seat\Notifications\Notifications\Characters;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Killmails\KillmailDetail;
use Seat\Notifications\Notifications\AbstractNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class Killmail.
 *
 * @package Seat\Notifications\Notifications\Characters
 */
class Killmail extends AbstractNotification
{
    use NotificationTools;

    /**
     * @var \Seat\Eveapi\Models\Killmails\KillmailDetail
     */
    private $killmail;

    /**
     * Create a new notification instance.
     *
     * @param \Seat\Eveapi\Models\Killmails\KillmailDetail $killmail
     */
    public function __construct(KillmailDetail $killmail)
    {

        $this->killmail = $killmail;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {

        return ['mail', 'slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
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
                'Lost a ' .
                $this->killmail->victim->ship->typeName . ' in ' .
                $this->killmail->system->itemName . ' (' .
                number_format($this->killmail->system->security, 2) . ')'
            )
            ->action(
                'Check it out on zKillboard',
                'https://zkillboard.com/kill/' . $this->killmail->killmail_id . '/'
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

        $message = (new SlackMessage)
            ->content('A kill has been recorded for your corporation!')
            ->from('SeAT Killmails', $this->typeIconUrl($this->killmail->victim->ship_type_id))
            ->attachment(function ($attachment) {

                $attachment
                    ->timestamp(carbon($this->killmail->killmail_time))
                    ->fields([
                        'Ship Type' => $this->killmail->victim->ship->typeName,
                        'zKB Link'  => 'https://zkillboard.com/kill/' . $this->killmail->killmail_id,
                    ])
                    ->field(function ($field) {

                        $field->title('System')
                            ->content($this->zKillBoardToSlackLink(
                                'system',
                                $this->killmail->solar_system_id,
                                $this->killmail->system->itemName . ' (' .
                                number_format($this->killmail->system->security, 2) . ')'));
                    })
                    ->thumb($this->typeIconUrl($this->killmail->victim->ship_type_id))
                    ->fallback('Kill details')
                    ->footer('zKillboard')
                    ->footerIcon('https://zkillboard.com/img/wreck.png');
            });

        $allied_corporation_ids = CorporationInfo::select('corporation_id')->get()->pluck('corporation_id')->toArray();

        (in_array($this->killmail->victim->corporation_id, $allied_corporation_ids)) ?
            $message->error() : $message->success();

        return $message;
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
            'characterName'   => $this->killmail->attacker->character->name,
            'corporationName' => $this->killmail->attacker->corporation->name,
            'typeName'        => $this->killmail->victim->ship->typeName,
            'itemName'        => $this->killmail->system->itemName,
            'security'        => $this->killmail->system->security,
        ];
    }
}
