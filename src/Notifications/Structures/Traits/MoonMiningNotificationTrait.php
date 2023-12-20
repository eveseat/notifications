<?php

declare(strict_types=1);

namespace Seat\Notifications\Notifications\Structures\Traits;

use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;

trait MoonMiningNotificationTrait
{
    const GAZ_MARKET_GROUP_ID = 2396;

    const R8_MARKET_GROUP_ID = 2397;

    const R16_MARKET_GROUP_ID = 2398;

    const R32_MARKET_GROUP_ID = 2400;

    const R64_MARKET_GROUP_ID = 2401;

    const ORE_COLOR = '#d2d6de';

    const GAZ_COLOR = '#00a65a';

    const R8_COLOR = '#3c8dbc';

    const R16_COLOR = '#00c0ef';

    const R32_COLOR = '#f39c12';

    const R64_COLOR = '#dd4b39';

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