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
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Notifications\Models\NotificationGroup;

/**
 * Class CharacterNotificationObserver.
 *
 * @package Seat\Notifications\Observers
 */
class CharacterNotificationObserver
{
    const EXPIRATION_DELAY = 3600;

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
     */
    public function created(CharacterNotification $notification)
    {
        logger()->debug(
            sprintf('[Notifications][%d] Character Notification - Queuing job due to registered in-game notification.', $notification->notification_id),
            $notification->toArray());

        $this->dispatch($notification);
    }

    /**
     * Queue notification based on notification kind.
     *
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
     */
    private function dispatch(CharacterNotification $notification)
    {
        // ignore any notification created since more than 60 minutes
        if (carbon()->diffInSeconds($notification->timestamp) > self::EXPIRATION_DELAY)
            return;

        // detect handlers setup for the current notification
        $handlers = config(sprintf('notifications.alerts.%s.handlers', $notification->type), []);

        // if the notification is unsupported (no handlers available), log and interrupt
        if (empty($handlers)) {
            logger()->debug('Unsupported notification type.', [
                'type' => $notification->type,
                'sender_type' => $notification->sender_type,
                'notification' => $notification->text,
            ]);

            return;
        }

        // retrieve routing candidates for the current notification
        $routes = $this->getRoutingCandidates($notification);

        // in case no routing candidates has been delivered, exit
        if ($routes->isEmpty())
            return;

        // attempt to enqueue a notification for each routing candidate
        $routes->each(function ($integration) use ($handlers, $notification) {
            if (array_key_exists($integration->channel, $handlers)) {

                // extract handler from the list
                $handler = $handlers[$integration->channel];

                // enqueue the notification
                Notification::route($integration->channel, $integration->route)
                    ->notify(new $handler($notification));
            }
        });
    }

    /**
     * Provide a unique list of notification channels (including driver and route).
     *
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
     * @return \Illuminate\Support\Collection
     */
    private function getRoutingCandidates(CharacterNotification $notification)
    {
        // collect notifications settings related to that notification
        $settings = NotificationGroup::with('alerts', 'affiliations')
            ->whereHas('alerts', function ($query) use ($notification) {
                $query->where('alert', $notification->type);
            })->whereHas('affiliations', function ($query) use ($notification) {
                $query->where('affiliation_id', $notification->character_id);
                $query->orWhere('affiliation_id', $notification->recipient->affiliation->corporation_id);
            })->get();

        // loop over each group candidate and collect available integrations
        $routes = $settings->map(function ($group) {
            return $group->integrations->map(function ($channel) {

                // extract the route value from settings field
                $setting = (array) $channel->settings;
                $key = array_key_first($setting);
                $route = $setting[$key];

                // build a composite object build with channel and route
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
