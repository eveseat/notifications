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

namespace Seat\Notifications\Observers;

use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Notifications\Models\NotificationGroup;
use Seat\Notifications\Traits\NotificationDispatchTool;

/**
 * Class CharacterNotificationObserver.
 *
 * @package Seat\Notifications\Observers
 */
class CharacterNotificationObserver
{
    use NotificationDispatchTool;

    const EXPIRATION_DELAY = 3600;

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
     */
    public function created(CharacterNotification $notification)
    {
        logger()->debug(
            sprintf('[Notifications][%d] Character Notification - Queuing job due to registered in-game notification.', $notification->notification_id),
            $notification->toArray());

        $this->dispatch($notification);
    }

    /**
     * Queue notification based on notification kind.
     *
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
     */
    private function dispatch(CharacterNotification $notification)
    {
        // ignore any notification created since more than 60 minutes
        if (carbon()->diffInSeconds($notification->timestamp) > self::EXPIRATION_DELAY)
            return;

        $groups = NotificationGroup::with('alerts', 'affiliations')
            ->whereHas('alerts', function ($query) use ($notification) {
                $query->where('alert', $notification->type);
            })->whereHas('affiliations', function ($query) use ($notification) {
                $query->where('affiliation_id', $notification->character_id);
                $query->orWhere('affiliation_id', $notification->recipient->affiliation->corporation_id);
            })->get();

        $this->dispatchNotifications($notification->type, $groups, function ($notificationClass) use ($notification) {
            return new $notificationClass($notification);
        });
    }
}
