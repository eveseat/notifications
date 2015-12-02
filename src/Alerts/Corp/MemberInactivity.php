<?php
/*
This file is part of SeAT

Copyright (C) 2015  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace Seat\Notifications\Alerts\Corp;

use DB;
use Seat\Eveapi\Models\Corporation\MemberTracking;
use Seat\Notifications\Alerts\Base;

/**
 * Class MemberInactivity
 * @package Seat\Notifications\Alerts\Corp
 */
class MemberInactivity extends Base
{

    /**
     * Run the Notifications Job
     */
    function call()
    {

        foreach ($this->getInactivities() as $inactive_member)
            $this->processNotifications($inactive_member);

        return;
    }

    /**
     * @return mixed
     */
    public function getInactivities()
    {

        return MemberTracking::where(
            'logoffDateTime', '<', DB::raw('date_sub(NOW(), INTERVAL 3 MONTH)'))
            ->get();
    }

    /**
     * @param $inactive_member
     *
     * @throws \Seat\Notifications\Exceptions\TypeException
     */
    public function processNotifications($inactive_member)
    {

        // Get the users that should be notified about this
        // inactivity
        $recipients = $this->usersWithPermission(
            'corporation.tracking', null, $inactive_member->corporationID);

        foreach ($recipients as $recipient) {

            $message = $this->newMessage()
                ->set('recipient', $recipient)
                ->set('subject', 'Inactive Member Notification')
                ->set('message', $inactive_member->name . ' has been ' .
                    'inactive for more than 3 months. The last logoff was at: ' .
                    $inactive_member->logoffDateTime);

            $this->sendNotification($message);
        }

    }

}
