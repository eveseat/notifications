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

/**
 * Class StarbaseFuel.
 *
 * @package Seat\Notifications\Alerts\Corp
 * @deprecated 3.0.0
 */
class StarbaseFuel extends Base
{
    use Starbases;

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
        $low_fuel = collect();

        // Go over each corporation ...
        $corporation_ids->each(function ($corporation_id) use (&$low_fuel) {

            // .. and check the details of each starbase
            $this->getCorporationStarbases($corporation_id)->each(
                function ($starbase) use ($corporation_id, &$low_fuel) {

                    $info = [
                        'corporation_id'    => $corporation_id,
                        'type'              => $starbase->starbaseTypeName,
                        'name'              => $starbase->starbaseName,
                        'location'          => $starbase->moonName,
                        'hours_left'        => carbon('now')->diffInHours(
                            carbon('now')->addHours($starbase->fuelBlocks / ceil(
                                    $starbase->baseFuelUsage * ($starbase->inSovSystem ? 0.75 : 1)))),
                        'fuel_blocks'       => $starbase->fuelBlocks,
                        'estimated_offline' => carbon('now')->addHours(
                            $starbase->fuelBlocks / ceil($starbase->baseFuelUsage * ($starbase->inSovSystem ? 0.75 : 1)))
                            ->diffForHumans(),

                    ];

                    // If the fuel is low, add it to the collection!
                    if ($info['hours_left'] < 24)
                        $low_fuel->push($info);
                });

        });

        return $low_fuel;

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

        return 'starbasefuel';
    }

    /**
     * Fields in a collection row that make the alert
     * unique.
     *
     * @return array
     */
    protected function getUniqueFields(): array
    {

        return ['location', 'fuel_blocks'];
    }
}
