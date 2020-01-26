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
use Seat\Eveapi\Models\Corporation\CorporationStarbase;
use Seat\Notifications\Alerts\Base;
use Seat\Services\Repositories\Corporation\Starbases;
use Seat\Services\Repositories\Eve\EveRepository;

/**
 * Class StarbaseStateChange.
 * @package Seat\Notifications\Alerts\Corp
 * @deprecated 3.0.0
 */
class StarbaseStateChange extends Base
{
    use Starbases, EveRepository;

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

        // Get the corporations we know about.
        $corporation_ids = CorporationStarbase::select('corporationID')
            ->groupBy('corporationID')->pluck('corporationID');

        // Prepare the return collection.
        $starbases = collect();

        // Go over each corporation ...
        $corporation_ids->each(function ($corporation_id) use (&$starbases) {

            // .. and check the details of each starbase
            $this->getCorporationStarbases($corporation_id)->each(
                function ($starbase) use ($corporation_id, &$starbases) {

                    $starbases->push([
                        'corporation_id'  => $corporation_id,
                        'type'            => $starbase->starbaseTypeName,
                        'name'            => $starbase->starbaseName,
                        'location'        => $starbase->moonName,
                        'state'           => $starbase->state,
                        'state_name'      => $this->getEveStarbaseTowerStates()[$starbase->state],
                        'state_timestamp' => $starbase->stateTimeStamp,
                    ]);

                });

        });

        return $starbases;
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
     * The name of the alert. This is also the name
     * of the notifier to use.
     *
     * @return string
     */
    protected function getName(): string
    {

        return 'starbasestatechange';
    }

    /**
     * Fields in a collection row that make the alert
     * unique.
     *
     * @return array
     */
    protected function getUniqueFields(): array
    {

        return ['location', 'state', 'state_timestamp'];
    }
}
