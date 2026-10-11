<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
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

namespace Seat\Notifications\Notifications\Structures\Traits;

use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Universe\UniverseStructure;

trait MercenaryDenNotificationTools
{
    protected function getMercenaryDenId(): ?int
    {
        return $this->notification->text['itemID']
            ?? ($this->notification->text['mercenaryDenShowInfoData'][2] ?? null);
    }

    protected function getMercenaryDenType(): InvType
    {
        $type_id = $this->notification->text['typeID']
            ?? ($this->notification->text['mercenaryDenShowInfoData'][1] ?? null);

        return InvType::firstOrNew(
            ['typeID' => $type_id],
            ['typeName' => trans('web::seat.unknown')]
        );
    }

    protected function getMercenaryDenStructure(): ?UniverseStructure
    {
        $den_id = $this->getMercenaryDenId();

        return is_null($den_id) ? null : UniverseStructure::find($den_id);
    }

    protected function getMercenaryDenName(): string
    {
        $structure = $this->getMercenaryDenStructure();

        if (! is_null($structure) && ! empty($structure->name))
            return $structure->name;

        return 'Mercenary Den';
    }

    protected function getMercenaryDenPlanet(): MapDenormalize
    {
        $planet_id = $this->notification->text['planetID']
            ?? ($this->notification->text['planetShowInfoData'][2] ?? 0);

        return MapDenormalize::firstOrNew(
            ['itemID' => $planet_id],
            ['itemName' => trans('web::seat.unknown')]
        );
    }

    protected function getMercenaryDenSystem(): MapDenormalize
    {
        return MapDenormalize::firstOrNew(
            ['itemID' => $this->notification->text['solarsystemID']],
            ['itemName' => trans('web::seat.unknown'), 'security' => 0]
        );
    }
}
