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

namespace Seat\Notifications\Mentions;

use Seat\Notifications\Services\Discord\Messages\DiscordMention;
use Seat\Notifications\Services\Discord\Messages\DiscordMentionType;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;

class DiscordMentionsAdapter
{
    public static function populateAtEveryone(DiscordMessage $message, array $data): void
    {
        $message->mention(new DiscordMention(DiscordMentionType::Everyone));
    }

    public static function populateAtHere(DiscordMessage $message, array $data): void
    {
        $message->mention(new DiscordMention(DiscordMentionType::Here));
    }

    public static function populateAtRole(DiscordMessage $message, array $data) {
        $message->mention(new DiscordMention(DiscordMentionType::Role, $data['role']));
    }

    public static function populateAtUser(DiscordMessage $message, array $data) {
        $message->mention(new DiscordMention(DiscordMentionType::User, $data['user']));
    }
}
