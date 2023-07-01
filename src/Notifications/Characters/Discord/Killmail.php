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
     * @var KillmailDetail
     */
    private KillmailDetail $killmail;

    /**
     * Killmail constructor.
     *
     * @param KillmailDetail $killmail
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
            ->content('A kill has been recorded for your corporation!')
            ->embed(function (DiscordEmbed $embed) {
                $embed->timestamp($this->killmail->killmail_time);
                $embed->author('SeAT Kilometer', asset('web/img/favico/apple-icon-180x180.png'));

                $embed->field('Ship Type', $this->killmail->victim->ship->typeName);
                $embed->field('zKB Link', sprintf('https://zkillboard.com/kill/%d/', $this->killmail->killmail_id));
                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('System');
                    $field->value(
                        $this->zKillBoardToDiscordLink(
                            'system',
                            $this->killmail->solar_system_id,
                            sprintf('%s (%s)',
                                $this->killmail->solar_system->name,
                                number_format($this->killmail->solar_system->security, 2))));
                });

                $embed->thumb($this->typeIconUrl($this->killmail->victim->ship_type_id));
                $embed->footer('zKillboard', 'https://zkillboard.com/img/wreck.png');

                (CorporationInfo::find($this->killmail->victim->corporation_id)) ?
                    $embed->color(DiscordMessage::ERROR) : $embed->color(DiscordMessage::SUCCESS);
            });
    }
}
