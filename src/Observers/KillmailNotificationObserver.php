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

namespace Seat\Notifications\Observers;

use Illuminate\Notifications\AnonymousNotifiable;
use Seat\Eveapi\Models\Killmails\KillmailDetail;
use Seat\Notifications\Models\NotificationGroup;
use Seat\Notifications\Notifications\Characters\Slack\Killmail;

/**
 * Class KillmailNotificationObserver.
 *
 * @package Seat\Notifications\Observers
 */
class KillmailNotificationObserver
{
    const EXPIRATION_DELAY = 3600;

    /**
     * @param  \Seat\Eveapi\Models\Killmails\KillmailDetail  $killmail
     */
    public function updated(KillmailDetail $killmail)
    {
        logger()->debug(
            sprintf('[Notifications][%d] Killmail - Queuing job due to updated killmail.', $killmail->killmail_id),
            $killmail->toArray());

        $this->dispatch($killmail);
    }

    /**
     * @param  \Seat\Eveapi\Models\Killmails\KillmailDetail  $killmail
     */
    private function dispatch(KillmailDetail $killmail)
    {
        // ignore any notification created since more than 60 minutes
        if (carbon()->diffInSeconds($killmail->killmail_time) > self::EXPIRATION_DELAY)
            return;

        // ask Laravel to enqueue the notification
        $manager = new AnonymousNotifiable();

        // retrieve routing candidates for the current notification
        $routes = $this->getRoutingCandidates($killmail);

        // in case no routing candidates has been delivered, exit
        if ($routes->isEmpty())
            return;

        // append each routing candidate to the notification process
        $routes->each(function ($integration) use ($manager) {
            $manager->route($integration->channel, $integration->route);
        });

        // enqueue the notification - delay by 5 minutes to leave time to SeAT to pull complete killmail from ESI
        $when = now()->addMinutes(5);
        $manager->notify((new Killmail($killmail))->delay($when));
    }

    /**
     * Provide a unique list of notification channels (including driver and route).
     *
     * @param  \Seat\Eveapi\Models\Killmails\KillmailDetail  $killmail
     * @return \Illuminate\Support\Collection
     */
    private function getRoutingCandidates(KillmailDetail $killmail)
    {
        $settings = NotificationGroup::with('alerts', 'affiliations')
            ->whereHas('alerts', function ($query) {
                $query->where('alert', 'killmail');
            })->whereHas('affiliations', function ($query) use ($killmail) {
                $query->where('affiliation_id', $killmail->victim->character_id);
                $query->orWhere('affiliation_id', $killmail->victim->corporation_id);
                $query->orWhereIn('affiliation_id', $killmail->attackers->pluck('character_id'));
                $query->orWhereIn('affiliation_id', $killmail->attackers->pluck('corporation_id'));
            })->get();

        $routes = $settings->map(function ($group) {
            return $group->integrations->map(function ($channel) {
                $setting = (array) $channel->settings;
                $key = array_key_first($setting);
                $route = $setting[$key];

                return (object) [
                    'channel' => $channel->type,
                    'route' => $route,
                ];
            });
        });

        return $routes->flatten()->unique(function ($integration) {
            return $integration->channel . $integration->route;
        });
    }
}
