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

namespace Seat\Notifications\Services\Discord\Messages;

use Illuminate\Support\InteractsWithTime;

/**
 * Class DiscordEmbed.
 *
 * @package Seat\Notifications\Services\Discord\Messages
 */
class DiscordEmbed
{
    use InteractsWithTime;

    /**
     * The embed's title.
     *
     * @var string
     */
    public $title;

    /**
     * The embed's description.
     *
     * @var string
     */
    public $description;

    /**
     * The embed's url.
     *
     * @var string|null
     */
    public $url;

    /**
     * The embed's timestamp.
     *
     * @var int|null
     */
    public $timestamp;

    /**
     * The embed's color.
     *
     * @var int|null
     */
    public $color;

    /**
     * The embed's footer.
     *
     * @var \Seat\Notifications\Services\Discord\Messages\DiscordEmbedFooter|null
     */
    public $footer;

    /**
     * The embed's image.
     *
     * @var string|null
     */
    public $image;

    /**
     * The embed's thumbnail.
     *
     * @var string|null
     */
    public $thumbnail;

    /**
     * The embed's author.
     *
     * @var \Seat\Notifications\Services\Discord\Messages\DiscordEmbedAuthor|null
     */
    public $author;

    /**
     * The embed's fields.
     *
     * @var array
     */
    public $fields;

    /**
     * Set the title of the embed.
     *
     * @param  string  $title
     * @param  string|null  $url
     * @return $this
     */
    public function title(string $title, ?string $url = null): DiscordEmbed
    {
        $this->title = $title;
        $this->url = $url;

        return $this;
    }

    /**
     * Set the description of the embed.
     *
     * @param  string  $description
     * @return $this
     */
    public function description(string $description): DiscordEmbed
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set the embed's timestamp.
     *
     * @param  \DateInterval|\DateTimeInterface|int|string  $timestamp
     * @return $this
     */
    public function timestamp(\DateInterval|\DateTimeInterface|int|string $timestamp): DiscordEmbed
    {
        // many notifications directly pass a datetime string from a model into this,
        // but $this->availableAt doesn't handle strings. Since it's from the laravel
        // InteractsWithTime trait, we also can't fix this. Therefore, we special-case
        // strings here.
        if(is_string($timestamp)){
            $timestamp = carbon($timestamp);
        }

        $this->timestamp = $this->availableAt($timestamp);

        return $this;
    }

    /**
     * Set the embed's color.
     *
     * @param  int  $color
     * @return $this
     */
    public function color(int $color): DiscordEmbed
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Set the embed's footer.
     *
     * @param  string  $text
     * @param  string|null  $icon
     * @return $this
     */
    public function footer(string $text, ?string $icon = null): DiscordEmbed
    {
        $this->footer = new DiscordEmbedFooter();
        $this->footer->text = $text;
        $this->footer->icon_url = $icon;

        return $this;
    }

    /**
     * Set the embed's image.
     *
     * @param  string  $url
     * @return $this
     */
    public function image(string $url): DiscordEmbed
    {
        $this->image = $url;

        return $this;
    }

    /**
     * Set the embed's thumbnail.
     *
     * @param  string  $url
     * @param  int|null  $height
     * @param  int|null  $width
     * @return $this
     */
    public function thumb(string $url): DiscordEmbed
    {
        $this->thumbnail = $url;

        return $this;
    }

    /**
     * Set the embed's author.
     *
     * @param  string  $name
     *                        * @param string|null $icon
     * @param  string|null  $link
     * @return $this
     */
    public function author(string $name, ?string $icon = null, ?string $link = null): DiscordEmbed
    {
        $this->author = new DiscordEmbedAuthor();
        $this->author->name = $name;
        $this->author->url = $link;
        $this->author->icon_url = $icon;

        return $this;
    }

    /**
     * Add a field to the embed.
     *
     * @param  \Closure|string  $title
     * @param  string  $value
     * @return $this
     */
    public function field($title, $value = ''): DiscordEmbed
    {
        if (is_callable($title)) {
            $callback = $title;

            $callback($embed_field = new DiscordEmbedField);

            $this->fields[] = $embed_field;

            return $this;
        }

        $this->fields[$title] = $value;

        return $this;
    }

    /**
     * Set the embed's fields.
     *
     * @param  \Seat\Notifications\Services\Discord\Messages\DiscordEmbedField[]  $fields
     * @return $this
     */
    public function fields(array $fields): DiscordEmbed
    {
        $this->fields = $fields;

        return $this;
    }
}
