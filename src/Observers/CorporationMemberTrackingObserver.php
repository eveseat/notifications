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

namespace Seat\Notifications\Observers;

use Seat\Eveapi\Models\Corporation\CorporationMemberTracking;
use Seat\Notifications\Models\NotificationGroup;
use Seat\Notifications\Traits\NotificationDispatchTool;

/**
 * Class CorporationMemberTrackingObserver.
 *
 * @package Seat\Notifications\Observers
 */
class CorporationMemberTrackingObserver
{
    use NotificationDispatchTool;

    const DELAY_THRESHOLD = 2629743;

    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationMemberTracking  $member
     */
    public function created(CorporationMemberTracking $member)
    {
        logger()->debug(
            sprintf('[Notifications][%d] Corporation Member Tracking - Queuing job due to created corporation member event.', $member->character_id),
            $member->toArray());

        $this->dispatch($member);
    }

    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationMemberTracking  $member
     */
    public function updated(CorporationMemberTracking $member)
    {
        logger()->debug(
            sprintf('[Notifications][%d] Corporation Member Tracking - Queuing job due to updated corporation member event.', $member->character_id),
            $member->toArray());

        $this->dispatch($member);
    }

    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationMemberTracking  $member
     */
    private function dispatch(CorporationMemberTracking $member)
    {
        if (carbon()->diffInSeconds($member->logoff_date) < self::DELAY_THRESHOLD)
            return;

        $groups = NotificationGroup::with('alerts', 'affiliations')
            ->whereHas('alerts', function ($query) {
                $query->where('alert', 'inactive_member');
            })->whereHas('affiliations', function ($query) use ($member) {
                $query->where('affiliation_id', $member->character_id);
                $query->orWhere('affiliation_id', $member->corporation_id);
            })->get();

        $this->dispatchNotifications('inactive_member', $groups, function ($notificationClass) use ($member) {
            return new $notificationClass($member);
        });
    }
}
