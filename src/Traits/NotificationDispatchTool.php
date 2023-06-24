<?php

namespace Seat\Notifications\Traits;

use Illuminate\Support\Facades\Notification;

trait NotificationDispatchTool
{
    public function mapGroups($groups){
        return $groups
            ->map(function ($group) {
                return $group->integrations->map(function ($channel) use ($group) {

                    // extract the route value from settings field
                    $setting = (array)$channel->settings;
                    $key = array_key_first($setting);
                    $route = $setting[$key];

                    // build a composite object build with channel and route
                    return (object)[
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
                $notification->setMentions($integration->mentions);

                // enqueue the notification
                Notification::route($integration->channel, $integration->route)
                    ->notify($notification);
            }
        });
    }
}