<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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

namespace Seat\Notifications\Traits;

use Seat\Services\Exceptions\EveImageException;
use Seat\Services\Image\Eve;

/**
 * Trait NotificationTools.
 *
 * @package Seat\Notifications\Traits
 */
trait NotificationTools
{
    /**
     * Build a link to Eve Type.
     *
     * @param  int  $type_id
     * @return string
     */
    public function typeIconUrl(int $type_id): string
    {
        try {
            $icon = new Eve('types', 'icon', $type_id, 64, [], false);
        } catch (EveImageException $e) {
            logger()->error($e->getMessage());
            report($e);

            return '';
        }

        return sprintf('https:%s', $icon->url(64));
    }

    /**
     * Build a link to zKillboard using Slack message formatting.
     *
     * @param  string  $type  (must be ship, character, corporation or alliance)
     * @param  int  $id  the type entity ID
     * @param  string  $name  the type name
     * @return string
     */
    public function zKillBoardToSlackLink(string $type, int $id, string $name): string
    {
        if (! in_array($type, ['ship', 'character', 'corporation', 'alliance', 'kill', 'system', 'location']))
            return '';

        return sprintf('<https://zkillboard.com/%s/%d/|%s>', $type, $id, $name);
    }

    /**
     * @param  int  $timestamp
     * @return \Carbon\Carbon
     *
     * @author https://github.com/flakas/reconbot/blob/master/reconbot/notificationprinters/esi/printer.py#L317
     */
    public function mssqlTimestampToDate(int $timestamp)
    {
        // Convert microsoft epoch to unix epoch
        // Based on: http://www.wiki.eve-id.net/APIv2_Char_NotificationTexts_XML

        $seconds = $timestamp / 10000000 - 11644473600;

        return carbon()->createFromTimestamp($seconds, 'UTC');
    }

    /**
     * Convert a campaign event enum type into an Type Name.
     *
     * @param  int  $type
     * @return string
     */
    public function campaignEventType(int $type): string
    {
        switch ($type) {
            case 1:
                return 'Territorial Claim Unit';
            case 2:
                return 'Infrastructure Hub';
            case 3:
                return 'Outpost';
            default:
                return 'Unknown';
        }
    }
}
