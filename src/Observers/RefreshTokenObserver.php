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

namespace Seat\Notifications\Observers;

use Seat\Eveapi\Models\RefreshToken;
use Seat\Notifications\Models\NotificationGroup;
use Seat\Notifications\Traits\NotificationDispatchTool;

/**
 * Class RefreshTokenObserver.
 *
 * @package Seat\Notifications\Observers
 */
class RefreshTokenObserver
{
    use NotificationDispatchTool;

    /**
     * @param  \Seat\Eveapi\Models\RefreshToken  $token
     */
    public function deleted(RefreshToken $token)
    {
        logger()->debug(
            sprintf('[Notifications][%d] Access Token - Queuing job due to removed access token.', $token->character_id));

        $this->dispatch($token, 'disabled_token');
    }

    /**
     * @param  \Seat\Eveapi\Models\RefreshToken  $token
     */
    public function restored(RefreshToken $token)
    {
        logger()->debug(
            sprintf('[Notifications][%d] Access Token - Queuing job due to restored access token.', $token->character_id));

        $this->dispatch($token, 'enabled_token');
    }

    /**
     * @param  \Seat\Eveapi\Models\RefreshToken  $token
     * @param  string  $type
     */
    private function dispatch(RefreshToken $token, string $type)
    {
        $groups = NotificationGroup::with('alerts', 'affiliations')
            ->whereHas('alerts', function ($query) use ($type) {
                $query->where('alert', $type);
            })->whereHas('affiliations', function ($query) use ($token) {
                $query->where('affiliation_id', $token->character_id);
                $query->orWhere('affiliation_id', $token->affiliation->corporation_id);
            })->get();

        $this->dispatchNotifications($type, $groups, function ($notificationClass) use ($token) {
            return new $notificationClass($token);
        });
    }
}
