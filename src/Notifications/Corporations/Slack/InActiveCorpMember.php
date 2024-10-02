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

namespace Seat\Notifications\Notifications\Corporations\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Eveapi\Models\Corporation\CorporationMemberTracking;
use Seat\Notifications\Notifications\AbstractSlackNotification;

/**
 * Class InActiveCorpMember.
 *
 * @package Seat\Notifications\Notifications\Corporations
 */
class InActiveCorpMember extends AbstractSlackNotification
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
     * @param  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $message = (new SlackMessage)
            ->content('A member has not logged in for some time! Check corp tracking.')
            ->from('SeAT Corporation Supervisor')
            ->attachment(function ($attachment) {

                $attachment->title('Tracking Details', route('seatcore::corporation.view.tracking', [
                    'corporation' => $this->member->corporation_id,
                ]))->fields([
                    'Last Logoff' => $this->member->logoff_date,
                    'Ship' => $this->member->ship->typeName,
                ]);
            });

        if (carbon()->diffInMonths($this->member->logon_date) > 1)
            $message->warning();

        if (carbon()->diffInMonths($this->member->logon_date) > 2)
            $message->error();

        return $message;
    }
}
