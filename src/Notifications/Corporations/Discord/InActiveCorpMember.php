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

namespace Seat\Notifications\Notifications\Corporations\Discord;

use Seat\Eveapi\Models\Corporation\CorporationMemberTracking;
use Seat\Notifications\Jobs\AbstractNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;

class InActiveCorpMember extends AbstractNotification
{
     /**
      * @var \Seat\Eveapi\Models\Corporation\CorporationMemberTracking
      */
     private $member;

    /**
     * InActiveCorpMember constructor.
     *
     * @param  \Seat\Eveapi\Models\Corporation\CorporationMemberTracking  $member
     */
    public function __construct(CorporationMemberTracking $member)
    {
        $this->member = $member;
    }

    /**
     * @inheritDoc
     */
    public function via($notifiable)
    {
        return ['discord'];
    }

    /**
     * @param  $notifiable
     * @return \Seat\Notifications\Services\Discord\Messages\DiscordMessage
     */
    public function toDiscord($notifiable)
    {
        $message = (new DiscordMessage())
            ->content('A member has not logged in for some time! Check corp tracking.')
            ->embed(function (DiscordEmbed $embed) {
                $embed->timestamp(carbon());
                $embed->author(
                    'SeAT Corporation Supervisor',
                    asset('web/img/favico/apple-icon-180x180.png'),
                    route('corporation.view.default', ['corporation' => $this->member->corporation_id])
                );

                $embed->field('Last Logoff', $this->member->logoff_date);
                $embed->field('Ship', $this->member->ship->typeName);
            });

        if (carbon()->diffInMonths($this->member->logon_date) > 1)
            $message->warning();

        if (carbon()->diffInMonths($this->member->logon_date) > 2)
            $message->error();

        return $message;
    }
}
