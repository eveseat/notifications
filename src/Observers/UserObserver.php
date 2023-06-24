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
use Seat\Web\Models\User;

/**
 * Class UserNotificationObserver.
 *
 * @package Seat\Notifications\Observers
 */
class UserObserver
{
    use NotificationDispatchTool;

    /**
     * @param  \Seat\Web\Models\User  $user
     */
    public function created(User $user)
    {
        $this->dispatch($user);
    }

    /**
     * Queue notification based on User Creation.
     *
     * @param  \Seat\Web\Models\User  $user
     */
    private function dispatch(User $user)
    {
        $groups = NotificationGroup::with('alerts')
            ->whereHas('alerts', function ($query) {
                $query->where('alert', 'created_user');
            })->get();

        $this->dispatchNotifications('created_user',$groups,function ($notificationClass) use ($user) {
            return new $notificationClass($user);
        });
    }
}
