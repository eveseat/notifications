<?php

namespace Seat\Notifications\Services\Discord\Messages;

enum DiscordMentionType
{
    case Everyone;
    case Here;
    case User;
    case Role;
}
