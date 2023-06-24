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

namespace Seat\Notifications\Services\Discord\Channels;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Notifications\Notification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;

/**
 * Class DiscordWebhookChannel.
 *
 * @package Seat\Notifications\Services\Discord\Channels
 */
class DiscordWebhookChannel
{
    /**
     * The HTTP client instance.
     *
     * @var \GuzzleHttp\Client
     */
    private $http;

    /**
     * DiscordWebhookChannel constructor.
     *
     * @param  \GuzzleHttp\Client  $http
     */
    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    /**
     * Send the given notification.
     *
     * @param  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return \Psr\Http\Message\ResponseInterface|void
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $url = $notifiable->routeNotificationFor('discord', $notification))
            return;

        logger()->debug('sending Discord Notification via webhook', [
            'data' => $this->buildJsonPayload($notification->toDiscord($notifiable)),
        ]);

        return $this->http->post($url, $this->buildJsonPayload(
            $notification->toDiscord($notifiable)
        ));
    }

    /**
     * Build up a JSON payload for the Slack webhook.
     *
     * @param  \Seat\Notifications\Services\Discord\Messages\DiscordMessage  $message
     * @return array
     */
    private function buildJsonPayload(DiscordMessage $message)
    {
        $optionalFields = array_filter([
            'username' => data_get($message, 'username'),
            'avatar_url' => data_get($message, 'icon'),
            'tts' => data_get($message, 'tts'),
            'allowed_mentions' => data_get($message, 'allowed_mentions'),
        ]);

        return array_merge([
            'json' => array_merge([
                'content' => $message->content,
                'embeds' => $this->embeds($message),
            ], $optionalFields),
        ], $message->http);
    }

    /**
     * Format the message's embeds.
     *
     * @param  \Seat\Notifications\Services\Discord\Messages\DiscordMessage  $message
     * @return array
     */
    private function embeds(DiscordMessage $message)
    {
        return collect($message->embeds)->map(function ($embed) use ($message) {
            return array_filter([
                'title' => $embed->title,
                'description' => $embed->description,
                'url' => $embed->url,
                'timestamp' => carbon($embed->timestamp)->toIso8601ZuluString(),
                'color' => $embed->color ?: $message->color(),
                'footer' => array_filter($embed->footer ? $embed->footer->toArray() : []),
                'image' => $embed->image ? ['url' => $embed->image] : null,
                'thumbnail' => $embed->thumbnail ? ['url' => $embed->thumbnail] : null,
                'author' => array_filter($embed->author ? $embed->author->toArray() : []),
                'fields' => $this->fields($embed),
            ]);
        })->all();
    }

    /**
     * Format the embed's fields.
     *
     * @param  \Seat\Notifications\Services\Discord\Messages\DiscordEmbed  $embed
     * @return array
     */
    private function fields(DiscordEmbed $embed)
    {
        return collect($embed->fields)->map(function ($value, $title) {
            if ($value instanceof DiscordEmbedField)
                return $value->toArray();

            return [
                'title' => $title,
                'value' => $value,
                'inline' => true,
            ];
        })->values()->all();
    }
}
