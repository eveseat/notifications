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

namespace Seat\Notifications\Alerts\Corp;

use DB;
use Illuminate\Support\Collection;
use Seat\Eveapi\Models\Corporation\MemberTracking;
use Seat\Notifications\Alerts\Base;

/**
 * Class MemberInactivity
 * @package Seat\Notifications\Alerts\Corp
 */
class MemberInactivity extends Base
{

    /**
     * The field to use form the data when trying
     * to infer an affiliation.
     *
     * @return string
     */
    public function getAffiliationField()
    {

        return 'corporationID';
    }

    /**
     * The required method to handle the Alert.
     *
     * @return mixed
     */
    protected function getData(): Collection
    {

        return MemberTracking::where(
            'logoffDateTime', '<', DB::raw('date_sub(NOW(), INTERVAL 3 MONTH)'))
            ->get();
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

        return 'inactivemember';
    }

    /**
     * Fields in a collection row that make the alert
     * unique.
     *
     * @return array
     */
    protected function getUniqueFields(): array
    {

        return ['characterID', 'logoffDateTime'];
    }

}
