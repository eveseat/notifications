<?php

declare(strict_types=1);

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

trait MoonMiningNotificationTrait
{
    const GAZ_MARKET_GROUP_ID = 2396;

    const R8_MARKET_GROUP_ID = 2397;

    const R16_MARKET_GROUP_ID = 2398;

    const R32_MARKET_GROUP_ID = 2400;

    const R64_MARKET_GROUP_ID = 2401;

    const ORE_COLOR = 12370112;

    const GAZ_COLOR = 5763719;

    const R8_COLOR = 9936031;

    const R16_COLOR = 10038562;

    const R32_COLOR = 11027200;

    const R64_COLOR = 15844367;

    /**
     * @return array<string, array<int|string, mixed>>
     */
    private function mapOreToColorsArray(): array
    {
        $category_color_map = [
            self::GAZ_MARKET_GROUP_ID => self::GAZ_COLOR,
            self::R8_MARKET_GROUP_ID => self::R8_COLOR,
            self::R16_MARKET_GROUP_ID => self::R16_COLOR,
            self::R32_MARKET_GROUP_ID => self::R32_COLOR,
            self::R64_MARKET_GROUP_ID => self::R64_COLOR,
        ];

        $ore_categories = [];

        foreach ($this->notification->text['oreVolumeByType'] as $type_id => $volume) {
            $type = InvType::find($type_id);

            $color = self::ORE_COLOR;

            if (array_key_exists($type->marketGroupID, $category_color_map)) {
                $color = $category_color_map[$type->marketGroupID];
            }

            $ore_categories[$color][$type_id] = $volume;
        }

        return $ore_categories;
    }
}
