<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018  Leon Jacobs
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

namespace Seat\Notifications\Alerts;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Seat\Notifications\Exceptions\NullNotifierException;
use Seat\Notifications\Models\NotificationGroup;
use Seat\Notifications\Models\NotificationHistory;

/**
 * Class Base.
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
     * @var
     */
    protected $groups;

    /**
     * @var string
     */
    protected $cacheKey = 'notifications';

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
     * The required method to handle the Alert.
     *
     * @return mixed
     */
    abstract protected function getData(): Collection;

    /**
     * The name of the alert. This is also the name
     * of the notifier to use.
     *
     * @return string
     */
    abstract protected function getName(): string;

    /**
     * The type of notification.
     *
     * @return string
     */
    abstract protected function getType(): string;

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

        // Loop over every entry in the data collection.
        foreach ($this->data as $data) {

            // Check if the notification has been sent before.
            if ($this->isOldNotification($data))
                continue;

            foreach ($this->getNotificationGroups() as $group) {

                // Check that the affiliations are ok for
                // the group.
                if ($this->affiliationOk($group, $data)) {

                    // Run the notifier.
                    $group->notify(new $this->notifier($data));

                    // Once the notification has been sent, sleep
                    // for a second. This is to try and avoid HTTP 429's
                    // from services that use webhooks.
                    sleep(1);

                }
            }

            $this->markNotificationAsOld($data);
        }

    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function isOldNotification($data)
    {

        $hash = $this->getDataHash($data);

        if (cache($this->cacheKey . $hash))
            return true;

        // Check the database.
        $in_db = NotificationHistory::whereHash($hash)
            ->first();

        // If its in the db, add it to the cache
        if ($in_db) {

            Cache::forever($this->cacheKey . $hash, true);

            return true;
        }

        return false;
    }

    /**
     * @param $data
     *
     * @return string
     */
    public function getDataHash($data)
    {

        $hashable = collect($data)
            ->only($this->getUniqueFields())
            ->implode(',');

        return md5($this->type . $this->name . $hashable);
    }

    /**
     * Fields in a collection row that make the alert
     * unique.
     *
     * @return array
     */
    abstract protected function getUniqueFields(): array;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getNotificationGroups(): Collection
    {

        // Return the groups we already found if we have.
        if ($this->groups)
            return $this->groups;

        // Get the groups that are applicable to this
        // notification type.
        $this->groups = NotificationGroup::with('alerts')
            ->whereHas('alerts', function ($query) {

                $query->where('alert', $this->name);

            })->where('type', $this->type)
            ->get();

        return $this->groups;

    }

    /**
     * Check if the affiliation of the notification is ok.
     *
     * Defining a method getAffiliationField() will have it
     * used to retreive the property name to use when checking
     * the affiliation_id.
     *
     * @param $group
     * @param $data
     *
     * @return bool
     */
    public function affiliationOk($group, $data)
    {

        // If we are working with an alert that is not
        // a char or corp alert, then no affiliation
        // rules are applicable.
        if ($this->type == 'seat' || $this->type == 'eve')
            return true;

        // If the group has *no* affiliations, then we are
        // assuming it wants *all* of the notifications.
        if ($group->affiliations->count() <= 0)
            return true;

        // If a group *does* have affiliations, it implies that
        // a filter is needed. lets check that if getAffiliationField()
        // is defined.
        if (method_exists($this, 'getAffiliationField')) {

            if ($group->affiliations->where(
                    'affiliation_id', $data[$this->getAffiliationField()])->count() > 0
            )
                return true;
        }

        return false;

    }

    /**
     * @param $data
     */
    public function markNotificationAsOld($data)
    {

        NotificationHistory::firstOrCreate([
            'hash'         => $this->getDataHash($data),
            'type'         => $this->type,
            'name'         => $this->name,
            'notification' => collect($data)
                ->only($this->getUniqueFields())
                ->toJson(),
        ]);

        Cache::forever($this->cacheKey . $this->getDataHash($data), true);
    }
}
