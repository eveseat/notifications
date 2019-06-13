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

namespace Seat\Notifications\Notifications;

use Illuminate\Notifications\Notification;

abstract class AbstractNotification extends Notification
{
    /**
     * Build a link to zKillboard using Slack message formatting.
     *
     * @param string $type (must be ship, character, corporation or alliance)
     * @param int    $id   the type entity ID
     * @param string $name the type name
     *
     * @return string
     */
    protected function zKillBoardToSlackLink(string $type, int $id, string $name)
    {
        if (! in_array($type, ['ship', 'character', 'corporation', 'alliance', 'kill', 'system']))
            return '';

        return sprintf('<https://zkillboard.com/%s/%d/|%s>', $type, $id, $name);
    }
}
