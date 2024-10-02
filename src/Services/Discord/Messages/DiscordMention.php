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

class DiscordMention
{
    public DiscordMentionType $type;
    public int|null $id;

    /**
     * @param  DiscordMentionType  $type
     * @param  int|null  $id
     */
    public function __construct(DiscordMentionType $type, ?int $id = null)
    {
        $this->type = $type;
        $this->id = $id;
    }

    public function formatPing(): string {
        switch ($this->type){
            case DiscordMentionType::Everyone:
                return '@everyone';

            case  DiscordMentionType::Here:
                return '@here';

            case DiscordMentionType::Role:
                return "<@&$this->id>";

            case DiscordMentionType::User:
                return "<@$this->id>";

        }

        return '';
    }
}
