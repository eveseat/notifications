<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

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

namespace Seat\Notifications\Alerts;

use Illuminate\Support\Collection;
use Seat\Notifications\Models\NotificationGroup;

/**
 * Class Base
 * @package Seat\Notifications\Alerts
 */
abstract class Base
{

    /**
     * @var string
     */
    protected $notifier;

    /**
     * The required method to handle the Alert.
     *
     * @return mixed
     */
    abstract protected function handle();

    /**
     * Define the notifier to use.
     *
     * @return string
     */
    abstract protected function notifier() : string;

    /**
     * Base constructor.
     */
    public function __construct()
    {

        $this->notifier = $this->getNotifier();
    }

    /**
     * Return the full class of the notifier from the config.
     *
     * @return string
     */
    public function getNotifier() : string
    {

        return config('notifications.notifiers.' . $this->notifier());
    }

    /**
     * Dispatch the notifications from the data returned
     * in the handle() method.
     */
    public function notify()
    {

        $data = $this->handle();

        foreach ($this->getNotificationGroups() as $group)
            $group->notify(new $this->notifier($data));

    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getNotificationGroups() : Collection
    {

        return NotificationGroup::all();
    }

}
