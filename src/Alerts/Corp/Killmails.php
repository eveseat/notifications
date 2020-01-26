<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
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

namespace Seat\Notifications\Alerts\Corp;

use Illuminate\Support\Collection;
use Seat\Notifications\Alerts\Base;
use Seat\Services\Repositories\Corporation\Corporation;
use Seat\Services\Repositories\Corporation\Killmails as KillmailsRepository;

/**
 * Class Killmails.
 * @package Seat\Notifications\Alerts\Corp
 * @deprecated 4.0.0
 */
class Killmails extends Base
{
    use Corporation, KillmailsRepository;

    /**
     * The field to use from the data when trying
     * to infer an affiliation.
     *
     * @return string
     */
    public function getAffiliationField()
    {

        return 'corporation_id';
    }

    /**
     * The required method to handle the Alert.
     *
     * @return mixed
     */
    protected function getData(): Collection
    {

        $killmails = collect();

        foreach ($this->getAllCorporations()->unique('corporation_id') as $corporation) {

            $this->getCorporationKillmails($corporation->corporation_id)
                ->orderBy('killmail_id', 'asc')
                ->each(function ($killmail) use (&$killmails) {

                    // Add the killmail to the collection.
                    if (! $killmails->contains($killmail)) $killmails->push($killmail);

                });
        }

        return $killmails->filter(function ($killmail, $key) {
            return carbon('now')->subWeek()->lte($killmail->killmail_detail->killmail_time);
        });
    }

    /**
     * The name of the alert. This is also the name
     * of the notifier to use.
     *
     * @return string
     */
    protected function getName(): string
    {

        return 'killmails';
    }

    /**
     * The type of notification.
     *
     * @return string
     */
    protected function getType(): string
    {

        return 'corp';
    }

    /**
     * Fields in a collection row that make the alert
     * unique.
     *
     * @return array
     */
    protected function getUniqueFields(): array
    {

        return ['killmail_id'];
    }
}
