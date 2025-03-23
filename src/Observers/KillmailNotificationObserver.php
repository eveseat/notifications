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

use Seat\Eveapi\Models\Killmails\KillmailDetail;
use Seat\Notifications\Jobs\EnsureRequiredDataIsAvailable;
use Seat\Notifications\Models\NotificationGroup;
use Seat\Notifications\Traits\NotificationDispatchTool;

/**
 * Class KillmailNotificationObserver.
 *
 * @package Seat\Notifications\Observers
 */
class KillmailNotificationObserver
{
    use NotificationDispatchTool;

    const EXPIRATION_DELAY = 3600;

    /**
     * @param  \Seat\Eveapi\Models\Killmails\KillmailDetail  $killmail
     */
    public function updated(KillmailDetail $killmail)
    {
        logger()->debug(
            sprintf('[Notifications][%d] Killmail - Queuing job due to updated killmail.', $killmail->killmail_id),
            $killmail->toArray());

        $this->dispatch($killmail);
    }

    /**
     * @param  \Seat\Eveapi\Models\Killmails\KillmailDetail  $killmail
     */
    private function dispatch(KillmailDetail $killmail)
    {
        // ignore any notification created since more than 60 minutes
        if (carbon()->diffInSeconds($killmail->killmail_time) > self::EXPIRATION_DELAY)
            return;

        $groups = NotificationGroup::with('alerts', 'affiliations')
            ->whereHas('alerts', function ($query) {
                $query->where('alert', 'killmail');
            })->whereHas('affiliations', function ($query) use ($killmail) {
                $query->where('affiliation_id', $killmail->victim->character_id);
                $query->orWhere('affiliation_id', $killmail->victim->corporation_id);
                $query->orWhereIn('affiliation_id', $killmail->attackers->pluck('character_id'));
                $query->orWhereIn('affiliation_id', $killmail->attackers->pluck('corporation_id'));
            })->get();
        $when = now()->addMinutes(5);

        $this->dispatchNotificationsWhenDataAvailable('Killmail', $groups, function ($notificationClass) use ($when, $killmail) {
            return (new $notificationClass($killmail))->delay($when);
        }, new EnsureRequiredDataIsAvailable('Killmail', $killmail));
    }
}
