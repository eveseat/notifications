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

namespace Seat\Notifications\Services\Discord\Messages;

use Closure;

/**
 * Class DiscordMessage.
 *
 * @package Seat\Notifications\Services\Discord\Messages
 */
class DiscordMessage
{
    public const INFO = 4886754;
    public const SUCCESS = 8311585;
    public const WARNING = 16098851;
    public const ERROR = 13632027;

    /**
     * The "level" of the notification (info, success, warning, error).
     *
     * @var string
     */
    public $level = 'default';

    /**
     * The username to send the message from.
     *
     * @var string|null
     */
    public $username;

    /**
     * The user emoji icon for the message.
     *
     * @var string|null
     */
    public $icon;

    /**
     * The channel to send the message on.
     *
     * @var string|null
     */
    public $channel;

    /**
     * The text content of the message.
     *
     * @var string
     */
    public $content;

    /**
     * The TTS status of the message.
     *
     * @var bool|null
     */
    public $tts;

    /**
     * The allowed mention of the message.
     *
     * @var array
     */
    public array $allowed_mentions = [
        'parse' => [],
        'users' => [],
        'roles' => [],
    ];

    /**
     * The message's embeds.
     *
     * @var array
     */
    public $embeds = [];

    /**
     * Additional request options for the Guzzle HTTP client.
     *
     * @var array
     */
    public $http = [];

    /**
     * Indicate that the notification gives information about an operation.
     *
     * @return $this
     */
    public function info(): DiscordMessage
    {
        $this->level = 'info';

        return $this;
    }

    /**
     * Indicate that the notification gives information about a successful operation.
     *
     * @return $this
     */
    public function success(): DiscordMessage
    {
        $this->level = 'success';

        return $this;
    }

    /**
     * Indicate that the notification gives information about a warning.
     *
     * @return $this
     */
    public function warning(): DiscordMessage
    {
        $this->level = 'warning';

        return $this;
    }

    /**
     * Indicate that the notification gives information about an error.
     *
     * @return $this
     */
    public function error(): DiscordMessage
    {
        $this->level = 'error';

        return $this;
    }

    /**
     * Set a custom username and optional emoji icon for the Discord message.
     *
     * @param  string  $username
     * @param  string|null  $icon
     * @return $this
     */
    public function from(string $username, ?string $icon = null): DiscordMessage
    {
        $this->username = $username;

        if (! is_null($icon))
            $this->icon = $icon;

        return $this;
    }

    /**
     * Set the Discord channel the message should be sent to.
     *
     * @param  string  $channel
     * @return $this
     */
    public function to(string $channel): DiscordMessage
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Appends text to the content of the discord message.
     *
     * @param  string  $content
     * @return $this
     */
    public function content(string $content): DiscordMessage
    {
        $this->content = "$this->content\n$content";

        return $this;
    }

    public function mention(DiscordMention $mention): DiscordMessage
    {
        $this->content($mention->formatPing());

        if($mention->type === DiscordMentionType::Everyone || $mention->type === DiscordMentionType::Here) {
            $this->allowed_mentions['parse'] = ['everyone'];
        }

        if($mention->type === DiscordMentionType::Role){
            $this->allowed_mentions['roles'][] = $mention->id;
        }

        if($mention->type === DiscordMentionType::User){
            $this->allowed_mentions['users'][] = $mention->id;
        }

        return $this;
    }

    /**
     * Add an embed to the Discord message.
     *
     * @param  \Closure  $callback
     * @return $this
     */
    public function embed(Closure $callback): DiscordMessage
    {
        $this->embeds[] = $embed = new DiscordEmbed();

        $callback($embed);

        return $this;
    }

    /**
     * Get the color for the message.
     *
     * @return int|null
     */
    public function color(): ?int
    {
        switch ($this->level) {
            case 'success':
                return self::SUCCESS;
            case 'warning':
                return self::WARNING;
            case 'error':
                return self::ERROR;
            case 'info':
                return self::INFO;
        }

        return null;
    }

    /**
     * Set additional request options for the Guzzle HTTP client.
     *
     * @param  array  $options
     * @return $this
     */
    public function http(array $options)
    {
        $this->http = $options;

        return $this;
    }
}
