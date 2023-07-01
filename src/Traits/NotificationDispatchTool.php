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

use Illuminate\Support\Facades\Notification;

trait NotificationDispatchTool
{
    public function mapGroups($groups) {
        return $groups
            ->map(function ($group) {
                return $group->integrations->map(function ($channel) use ($group) {

                    // extract the route value from settings field
                    $setting = (array) $channel->settings;
                    $key = array_key_first($setting);
                    $route = $setting[$key];

                    // build a composite object build with channel and route
                    return (object) [
                        'channel' => $channel->type,
                        'route' => $route,
                        'mentions' => $group->mentions->filter(function ($mention) use ($channel) {
                            return $mention->getType()->type = $channel->type;
                        }),
                    ];
                });
            })
            ->flatten();
    }

    public function dispatchNotifications($alert_type, $groups, $notification_creation_callback)
    {
        // loop over each group candidate and collect available integrations
        $integrations = $this->mapGroups($groups);

        $handlers = config(sprintf('notifications.alerts.%s.handlers', $alert_type), []);
        // if the notification is unsupported (no handlers available), log and interrupt
        if (empty($handlers)) {
            logger()->debug('Unsupported notification type.', [
                'type' => $alert_type,
            ]);

            return;
        }

        // attempt to enqueue a notification for each routing candidate
        $integrations->each(function ($integration) use ($notification_creation_callback, $handlers) {
            if (array_key_exists($integration->channel, $handlers)) {

                // extract handler from the list
                $handler = $handlers[$integration->channel];

                $notification = $notification_creation_callback($handler);
                $notification->setMentions($integration->mentions);

                // enqueue the notification
                Notification::route($integration->channel, $integration->route)
                    ->notify($notification);
            }
        });
    }
}
