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

namespace Seat\Notifications\Traits;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Seat\Notifications\Models\NotificationHistory;
use stdClass;

trait NotificationDispatchTool
{
    public function mapGroups($groups)
    {
        $groups->loadMissing('integrations', 'mentions');

        return $groups
            ->map(function ($group) {
                return $group->integrations->map(function ($channel) use ($group) {

                    // extract the route value from settings field
                    $setting = (array) $channel->settings;
                    $key = array_key_first($setting);
                    $route = $setting[$key];

                    // build a composite object build with channel and route
                    return (object) [
                        'group_id' => $group->id,
                        'group_name' => $group->name,
                        'suppress_duplicate_notifications' => $group->suppress_duplicate_notifications,
                        'channel' => $channel->type,
                        'route' => $route,
                        'mentions' => $group->mentions->filter(function ($mention) use ($channel) {
                            return $mention->getType()->type === $channel->type;
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
        $integrations->each(function ($integration) use ($alert_type, $notification_creation_callback, $handlers) {
            if (array_key_exists($integration->channel, $handlers)) {

                // extract handler from the list
                $handler = $handlers[$integration->channel];

                $notification = $notification_creation_callback($handler);
                $notification->setMentions($integration->mentions);

                if ($this->isDuplicateNotification($integration, $alert_type, $notification))
                    return;

                Notification::route($integration->channel, $integration->route)
                    ->notify($notification);
            }
        });
    }

    /**
     * @param  object  $integration
     * @param  string  $alert_type
     * @param  mixed  $notification
     * @return bool
     *
     * @throws \Illuminate\Database\QueryException
     */
    protected function isDuplicateNotification(object $integration, string $alert_type, $notification): bool
    {
        if (! $integration->suppress_duplicate_notifications)
            return false;

        $dedupe_key = $notification->duplicateSuppressionKey();

        if (is_null($dedupe_key))
            return false;

        $claim = [
            'hash' => hash('sha256', implode('|', [
                $integration->group_id,
                $integration->channel,
                (string) $integration->route,
                $alert_type,
                $this->hashDedupeKey($dedupe_key),
            ])),
            'type' => $alert_type,
            'name' => $integration->group_name ?: sprintf('group:%d', $integration->group_id),
            'notification' => json_encode([
                'group_id' => $integration->group_id,
                'channel' => $integration->channel,
                'route' => $integration->route,
                'dedupe_source' => get_class($notification),
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        try {
            DB::table((new NotificationHistory)->getTable())->insert($claim);

            return false;
        } catch (QueryException $exception) {
            if (! $this->isDuplicateClaimException($exception))
                throw $exception;

            logger()->debug('[Notifications] Suppressed duplicate notification.', [
                'group_id' => $integration->group_id,
                'channel' => $integration->channel,
                'route' => $integration->route,
                'type' => $alert_type,
                'notification' => get_class($notification),
            ]);

            return true;
        }
    }

    /**
     * @param  \Illuminate\Database\QueryException  $exception
     * @return bool
     */
    protected function isDuplicateClaimException(QueryException $exception): bool
    {
        $sql_state = $exception->errorInfo[0] ?? (string) $exception->getCode();

        if (in_array($sql_state, ['23000', '23505']))
            return true;

        return str_contains(strtolower($exception->getMessage()), 'duplicate')
            || str_contains(strtolower($exception->getMessage()), 'unique constraint');
    }

    /**
     * @param  mixed  $key
     * @return string
     */
    protected function hashDedupeKey(array $key): string
    {
        return hash('sha256', json_encode(
            $this->normalizeDedupeArray($key),
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        ));
    }

    /**
     * @param  array  $value
     * @return array
     */
    protected function normalizeDedupeArray(array $value): array
    {
        if ($this->isAssoc($value))
            ksort($value);

        foreach ($value as $key => $item) {
            if (is_array($item)) {
                $value[$key] = $this->normalizeDedupeArray($item);

                continue;
            }

            if ($item instanceof DateTimeInterface) {
                $value[$key] = $item->format(DateTimeInterface::ATOM);

                continue;
            }

            if ($item instanceof Collection) {
                $value[$key] = $this->normalizeDedupeArray($item->all());

                continue;
            }

            if ($item instanceof Model) {
                $value[$key] = $item->getKey();

                continue;
            }

            if ($item instanceof stdClass) {
                $value[$key] = $this->normalizeDedupeArray((array) $item);

                continue;
            }
        }

        return $value;
    }

    /**
     * @param  array  $value
     * @return bool
     */
    protected function isAssoc(array $value): bool
    {
        if (empty($value))
            return false;

        return array_keys($value) !== range(0, count($value) - 1);
    }
}
