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

trait SkyhookNotificationTools
{
    protected function getSkyhookId(): ?int
    {
        return $this->notification->text['itemID']
            ?? ($this->notification->text['skyhookShowInfoData'][2] ?? null);
    }

    protected function getSkyhookType(): InvType
    {
        $type_id = $this->notification->text['typeID']
            ?? ($this->notification->text['skyhookShowInfoData'][1] ?? null);

        return InvType::firstOrNew(
            ['typeID' => $type_id],
            ['typeName' => trans('web::seat.unknown')]
        );
    }

    protected function getSkyhookStructure(): ?UniverseStructure
    {
        $skyhook_id = $this->getSkyhookId();

        return is_null($skyhook_id) ? null : UniverseStructure::find($skyhook_id);
    }

    protected function getSkyhookName(): string
    {
        $structure = $this->getSkyhookStructure();

        if (! is_null($structure) && ! empty($structure->name))
            return $structure->name;

        return 'Skyhook';
    }

    protected function getSkyhookPlanet(): MapDenormalize
    {
        $planet_id = $this->notification->text['planetID']
            ?? ($this->notification->text['planetShowInfoData'][2] ?? 0);

        return MapDenormalize::firstOrNew(
            ['itemID' => $planet_id],
            ['itemName' => trans('web::seat.unknown')]
        );
    }

    protected function getSkyhookSystem(): MapDenormalize
    {
        return MapDenormalize::firstOrNew(
            ['itemID' => $this->notification->text['solarsystemID']],
            ['itemName' => trans('web::seat.unknown'), 'security' => 0]
        );
    }
}
