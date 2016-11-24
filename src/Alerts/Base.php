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
use Seat\Notifications\Exceptions\NullNotifierException;
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
     * @var Collection
     */
    protected $data;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * The required method to handle the Alert.
     *
     * @return mixed
     */
    abstract protected function getData(): Collection;

    /**
     * The type of notification.
     *
     * @return string
     */
    abstract protected function getType(): string;

    /**
     * The name of the alert. This is also the name
     * of the notifier to use.
     *
     * @return string
     */
    abstract protected function getName(): string;

    /**
     * Base constructor.
     */
    public function __construct()
    {

        $this->data = $this->getData();
        $this->name = $this->getName();
        $this->type = $this->getType();

        $this->notifier = $this->getNotifier();

    }

    /**
     * Return the full class of the notifier from the config.
     *
     * @return string
     * @throws \Seat\Notifications\Exceptions\NullNotifierException
     */
    public function getNotifier(): string
    {

        $notifier = config('notifications.alerts.' . $this->type . '.' . $this->name);

        // Ensure that we actual resolved a notifier. Failure to do so
        // could indicate a wrong type || name.
        if (is_null($notifier))
            throw new NullNotifierException(
                'Could not resolve the notifier. Check type and name.');

        return $notifier['notifier'];
    }

    /**
     * Dispatch the notifications.
     */
    public function handle()
    {

        // Let every notification group...
        foreach ($this->getNotificationGroups() as $group) {

            // Get each data element in a notification
            foreach ($this->data as $data)
                $group->notify(new $this->notifier($data));

        }

    }

    /**
     * If an affiliation needs to be taken into account,
     * specify which one.
     *
     * @return string
     */
    public function affiliationType()
    {

        return 'corp';
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getNotificationGroups(): Collection
    {

        // Get the groups that are applicable to this
        // notification type.
        return NotificationGroup::with('alerts')
            ->whereHas('alerts', function ($query) {

                $query->where('alert', $this->name);

            })->where('type', $this->type)
            ->get();

    }

}
