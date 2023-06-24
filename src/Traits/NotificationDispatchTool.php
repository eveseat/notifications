<?php

namespace Seat\Notifications\Traits;

trait NotificationDispatchTool
{
    public function mapGroups($groups){
        return $groups
            ->map(function ($group) {
                return $group->integrations->map(function ($channel) {

                    // extract the route value from settings field
                    $setting = (array)$channel->settings;
                    $key = array_key_first($setting);
                    $route = $setting[$key];

                    // build a composite object build with channel and route
                    return (object)[
                        'channel' => $channel->type,
                        'route' => $route,
                    ];
                });
            })
            ->flatten()->unique(function ($integration) {
                return $integration->channel . $integration->route;
            });
    }

    public function dispatchNotifications($alert_type,$groups,$notification_creation_callback)
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

                // enqueue the notification
                Notification::route($integration->channel, $integration->route)
                    ->notify($notification);
            }
        });
    }
}