<?php

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

    public static function populateAtRole(DiscordMessage $message, array $data){
        $message->mention(new DiscordMention(DiscordMentionType::Role, $data['role']));
    }

    public static function populateAtUser(DiscordMessage $message, array $data){
        $message->mention(new DiscordMention(DiscordMentionType::User, $data['user']));
    }
}