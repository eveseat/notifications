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

use Illuminate\Support\Facades\Notification;
use Seat\Eveapi\Models\Corporation\CorporationMemberTracking;
use Seat\Notifications\Models\NotificationGroup;

/**
 * Class CorporationMemberTrackingObserver.
 *
 * @package Seat\Notifications\Observers
 */
class CorporationMemberTrackingObserver
{
    const DELAY_THRESHOLD = 2629743;

    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationMemberTracking  $member
     */
    public function created(CorporationMemberTracking $member)
    {
        logger()->debug(
            sprintf('[Notifications][%d] Corporation Member Tracking - Queuing job due to created corporation member event.', $member->character_id),
            $member->toArray());

        $this->dispatch($member);
    }

    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationMemberTracking  $member
     */
    public function updated(CorporationMemberTracking $member)
    {
        logger()->debug(
            sprintf('[Notifications][%d] Corporation Member Tracking - Queuing job due to updated corporation member event.', $member->character_id),
            $member->toArray());

        $this->dispatch($member);
    }

    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationMemberTracking  $member
     */
    private function dispatch(CorporationMemberTracking $member)
    {
        if (carbon()->diffInSeconds($member->logoff_date) < self::DELAY_THRESHOLD)
            return;

        // detect handlers setup for the current notification
        $handlers = config('notifications.alerts.inactive_member.handlers', []);

        // retrieve routing candidates for the current notification
        $routes = $this->getRoutingCandidates($member);

        // in case no routing candidates has been delivered, exit
        if ($routes->isEmpty())
            return;

        // attempt to enqueue a notification for each routing candidates
        $routes->each(function ($integration) use ($handlers, $member) {
            if (array_key_exists($integration->channel, $handlers)) {

                // extract handler from the list
                $handler = $handlers[$integration->channel];

                // enqueue the notification
                Notification::route($integration->channel, $integration->route)
                    ->notify(new $handler($member));
            }
        });
    }

    /**
     * Provide a unique list of notification channels (including driver and route).
     *
     * @param  \Seat\Eveapi\Models\Corporation\CorporationMemberTracking  $member
     * @return \Illuminate\Support\Collection
     */
    public function getRoutingCandidates(CorporationMemberTracking $member)
    {
        $settings = NotificationGroup::with('alerts', 'affiliations')
            ->whereHas('alerts', function ($query) {
                $query->where('alert', 'inactive_member');
            })->whereHas('affiliations', function ($query) use ($member) {
                $query->where('affiliation_id', $member->character_id);
                $query->orWhere('affiliation_id', $member->corporation_id);
            })->get();

        $routes = $settings->map(function ($group) {
            return $group->integrations->map(function ($channel) {

                // extract the route value from settings field
                $settings = (array) $channel->settings;
                $key = array_key_first($settings);
                $route = $settings[$key];

                // build a composite object built with channel and route
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
