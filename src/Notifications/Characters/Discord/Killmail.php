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

namespace Seat\Notifications\Notifications\Characters\Discord;

use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Killmails\KillmailDetail;
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class Killmail.
 *
 * @package Seat\Notifications\Notifications\Characters\Discord
 */
class Killmail extends AbstractDiscordNotification
{
    use NotificationTools;

    /**
     * @var \Seat\Eveapi\Models\Killmails\KillmailDetail
     */
    private $killmail;

    /**
     * Killmail constructor.
     *
     * @param  \Seat\Eveapi\Models\Killmails\KillmailDetail  $killmail
     */
    public function __construct(KillmailDetail $killmail)
    {
        $this->killmail = $killmail;
    }

    /**
     * @param  DiscordMessage  $message
     * @param  $notifiable
     */
    public function populateMessage(DiscordMessage $message, $notifiable)
    {
        $message
            ->embed(function (DiscordEmbed $embed) {
                $embed->timestamp($this->killmail->killmail_time);
                $embed->author('SeAT Kilometer', asset('web/img/favico/apple-icon-180x180.png'));

                // title
                $embed->title(sprintf('%s destroyed in %s', $this->killmail->victim->ship->typeName, $this->killmail->solar_system->name));

                // zkb link
                $embed->field(function (DiscordEmbedField $field) {
                   $field
                       ->name('ZKB')
                       ->value('https://zkillboard.com/kill/' . $this->killmail->killmail_id . '/')
                       ->long();
                });

                // victim
                $embed->field(function (DiscordEmbedField $field) {
                    $field
                        ->name('Victim')
                        ->value(sprintf("Name: %s\nCorp: %s",
                            $this->zKillBoardToDiscordLink('character', $this->killmail->victim->character_id, $this->killmail->victim->character->name),
                            $this->zKillBoardToDiscordLink('corporation', $this->killmail->victim->corporation_id, $this->killmail->victim->corporation->name)
                        ))->long();
                });

                //final blow
                $final_blow = $this->killmail->attackers()->where('final_blow', true)->first();
                $embed->field(function (DiscordEmbedField $field) use ($final_blow) {
                    $field
                        ->name('Final Blow')
                        ->value(sprintf("Name: %s\nCorp:%s",
                            $this->zKillBoardToDiscordLink('character', $final_blow->character_id, $final_blow->character->name),
                            $this->zKillBoardToDiscordLink('corporation', $final_blow->corporation_id, $final_blow->corporation->name)
                        ))->long();
                });

                // attackers
                $attacker_count = $this->killmail->attackers()->count();
                $attackers = $this->killmail->attackers()
                    ->orderByDesc('damage_done')
                    ->limit(5)
                    ->get()
                    ->map(function ($attacker) {
                        return sprintf('%s | %s dmg',
                            $this->zKillBoardToDiscordLink('character', $attacker->character_id, $attacker->character->name),
                            number_format($attacker->damage_done),
                        );
                    });
                $others = $attacker_count - $attackers->count();
                if($others > 0){
                    $attackers = $attackers->push(sprintf('%d more', $others));
                }
                $embed->field(function (DiscordEmbedField $field) use ($attackers, $attacker_count) {
                    $field
                        ->name(sprintf('Attackers (%d)', $attacker_count))
                        ->value(implode("\n", $attackers->toArray()))
                        ->long();
                });

                // details
                $embed->field(function (DiscordEmbedField $field) {
                    $field
                        ->name('Details')
                        ->value(sprintf("Time: %s Eve Time\nISK Value: %s ISK",
                            carbon($this->killmail->killmail_time)->toTimeString(),
                            number_format($this->killmail->victim->getTotalEstimateAttribute())
                        ))->long();
                });

                //footer
                $embed->thumb($this->typeIconUrl($this->killmail->victim->ship_type_id));
                $embed->footer('zKillboard', 'https://zkillboard.com/img/wreck.png');

                (CorporationInfo::find($this->killmail->victim->corporation_id)) ?
                    $embed->color(DiscordMessage::ERROR) : $embed->color(DiscordMessage::SUCCESS);
            });
    }
}
