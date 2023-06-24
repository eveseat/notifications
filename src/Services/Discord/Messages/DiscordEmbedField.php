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

/**
 * Class DiscordEmbedField.
 *
 * @package Seat\Notifications\Services\Discord\Messages
 */
class DiscordEmbedField
{
    /**
     * The name of the embed field.
     *
     * @var string
     */
    private $name;

    /**
     * The value of the embed field.
     *
     * @var string
     */
    private $value;

    /**
     * Whether the content must be displayed inline.
     *
     * @var bool
     */
    private $inline = true;

    /**
     * Set the name of the field.
     *
     * @param  string  $name
     * @return $this
     */
    public function name(string $name): DiscordEmbedField
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the value of the field.
     *
     * @param  string  $value
     * @return $this
     */
    public function value(string $value): DiscordEmbedField
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Indicate that the content should not be displayed side-by-side with other fields.
     *
     * @return $this
     */
    public function long(): DiscordEmbedField
    {
        $this->inline = false;

        return $this;
    }

    /**
     * Get the array representation of the embed field.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
            'inline' => $this->inline,
        ];
    }
}
