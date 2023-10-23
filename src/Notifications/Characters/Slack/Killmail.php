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

namespace Seat\Notifications\Notifications\Characters\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Killmails\KillmailDetail;
use Seat\Notifications\Notifications\AbstractSlackNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class Killmail.
 *
 * @package Seat\Notifications\Notifications\Characters
 */
class Killmail extends AbstractSlackNotification
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
     * Get the Slack representation of the notification.
     *
     * @param  $notifiable
     * @return SlackMessage
     */
    public function populateMessage(SlackMessage $message,$notifiable)
    {
        $message
            ->from('SeAT Kilometer', $this->typeIconUrl($this->killmail->victim->ship_type_id))
            ->attachment(function ($attachment) {

                $attachment
                    ->timestamp(carbon($this->killmail->killmail_time))
                    //title with zkb link
                    ->title(sprintf('%s destroyed in %s', $this->killmail->victim->ship->typeName, $this->killmail->solar_system->name))
                    ->field('ZKB','https://zkillboard.com/kill/' . $this->killmail->killmail_id . '/')
                    ->field(function ($field){
                        $field
                            ->title('Victim')
                            ->content(sprintf("Name: %s\nCorp: %s",
                                $this->zKillBoardToSlackLink('character',$this->killmail->victim->character_id,$this->killmail->victim->character->name),
                                $this->zKillBoardToSlackLink('corporation',$this->killmail->victim->corporation_id,$this->killmail->victim->corporation->name)
                            ))
                            ->long();
                    })
                    ->field(function ($field){
                        $final_blow = $this->killmail->attackers()->where('final_blow', true)->first();
                        $field
                            ->title('Final Blow')
                            ->content(sprintf("Name: %s\nCorp:%s",
                                $this->zKillBoardToSlackLink('character',$final_blow->character_id,$final_blow->character->name),
                                $this->zKillBoardToSlackLink('corporation',$final_blow->corporation_id,$final_blow->corporation->name)
                            ))
                            ->long();
                    })
                    ->field(function ($field){
                        $attacker_count = $this->killmail->attackers()->count();
                        $attackers = $this->killmail->attackers()
                            ->orderByDesc('damage_done')
                            ->limit(5)
                            ->get()
                            ->map(function ($attacker){
                                return sprintf('%s | %s dmg',
                                    $this->zKillBoardToSlackLink('character', $attacker->character_id, $attacker->character->name),
                                    number_format($attacker->damage_done),
                                );
                            });

                        $others = $attacker_count - $attackers->count();
                        if($others > 0){
                            $attackers = $attackers->push(sprintf('%d more',$others));
                        }

                        $field
                            ->title(sprintf('Attackers (%d)',$attacker_count))
                            ->content(implode("\n",$attackers->toArray()))
                            ->long();
                    })
                    ->field(function ($field){
                        $field
                            ->title('Details')
                            ->content(sprintf("Time: %s Eve Time\nISK Value: %s ISK",
                                carbon($this->killmail->killmail_time)->toTimeString(),
                                number_format($this->killmail->victim->getTotalEstimateAttribute())))
                            ->long();
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
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {

        return [
            'characterName' => $this->killmail->attacker->character->name,
            'corporationName' => $this->killmail->attacker->corporation->name,
            'typeName' => $this->killmail->victim->ship->typeName,
            'system' => $this->killmail->solar_system->name,
            'security' => $this->killmail->solar_system->security,
        ];
    }
}
