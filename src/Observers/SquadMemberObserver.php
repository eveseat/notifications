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
use Seat\Notifications\Models\NotificationGroup;
use Seat\Web\Models\Squads\SquadMember;

/**
 * Class UserNotificationObserver.
 *
 * @package Seat\Notifications\Observers
 */
class SquadMemberObserver
{
    /**
     * @param  \Seat\Web\Models\Squads\SquadMember  $member
     */
    public function created(SquadMember $member)
    {
        logger()->debug(
            sprintf('[Notifications][%d] Squad Member - Queuing job due to joining squad member.', $member->user_id),
            $member->toArray());

        $this->dispatch($member, 'squad_member');
    }

    /**
     * @param  \Seat\Web\Models\Squads\SquadMember  $member
     */
    public function deleted(SquadMember $member)
    {
        logger()->debug(
            sprintf('[Notifications][%d] Corporation Member Tracking - Queuing job due to leaving squad member.', $member->user_id),
            $member->toArray());

        $this->dispatch($member, 'squad_member_removed');
    }

    /**
     * Queue notification based on User Creation.
     *
     * @param  \Seat\Web\Models\Sqauds\SquadMember  $member
     * @param  string  $type
     */
    private function dispatch(SquadMember $member, string $type)
    {
        // detect handlers setup for the current notification
        $handlers = config(sprintf('notifications.alerts.%s.handlers', $type), []);

        // retrieve routing candidates for the current notification
        $routes = $this->getRoutingCandidates($type);

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
     * @param  string  $type
     * @return \Illuminate\Support\Collection
     */
    private function getRoutingCandidates(string $type)
    {
        $settings = NotificationGroup::with('alerts')
            ->whereHas('alerts', function ($query) use ($type) {
                $query->where('alert', $type);
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
