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

use Illuminate\Support\Facades\Notification;
use Seat\Notifications\Models\NotificationGroup;
use Seat\Notifications\Traits\NotificationDispatchTool;
use Seat\Web\Models\Squads\SquadMember;

/**
 * Class UserNotificationObserver.
 *
 * @package Seat\Notifications\Observers
 */
class SquadMemberObserver
{
    use NotificationDispatchTool;

    /**
     * @param  \Seat\Web\Models\Squads\SquadMember  $member
     */
    public function created(SquadMember $member)
    {
        $this->dispatch($member, 'squad_member');
    }

    /**
     * @param  \Seat\Web\Models\Squads\SquadMember  $member
     */
    public function deleted(SquadMember $member)
    {
        $this->dispatch($member, 'squad_member_removed');
    }

    /**
     * Queue notification based on User Creation.
     *
     * @param  \Seat\Web\Models\Sqauds\SquadMember  $member
     * @param  string  $type
     */
    private function dispatch(SquadMember $member, string $type)
    {
        $group = NotificationGroup::with('alerts')
            ->whereHas('alerts', function ($query) use ($type) {
                $query->where('alert', $type);
            })->get();

        $this->dispatchNotifications($type,$group,function ($notificationClass) use ($member) {
            return new $notificationClass($member);
        });
    }
}
