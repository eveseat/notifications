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

use Seat\Notifications\Models\NotificationGroup;
use Seat\Notifications\Traits\NotificationDispatchTool;
use Seat\Web\Models\Squads\SquadApplication;

/**
 * Class UserNotificationObserver.
 *
 * @package Seat\Notifications\Observers
 */
class SquadApplicationObserver
{
    use NotificationDispatchTool;

    /**
     * @param  \Seat\Web\Models\Squads\SquadApplication  $member
     */
    public function created(SquadApplication $member)
    {
        $this->dispatch($member);
    }

    /**
     * Queue notification based on User Creation.
     *
     * @param  \Seat\Web\Models\Squads\SquadApplication  $member
     */
    private function dispatch(SquadApplication $member)
    {
        $groups = $settings = NotificationGroup::with('alerts')
            ->whereHas('alerts', function ($query) {
                $query->where('alert', 'squad_member');
            })->get();

        $this->dispatchNotifications('squad_application', $groups, function ($notificationClass) use ($member) {
            return new $notificationClass($member);
        });

    }
}
