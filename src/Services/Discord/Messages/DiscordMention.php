<?php

namespace Seat\Notifications\Services\Discord\Messages;

class DiscordMention
{
    public DiscordMentionType $type;
    public int|null $id;

    /**
     * @param DiscordMentionType $type
     * @param int|null $id
     */
    public function __construct(DiscordMentionType $type, ?int $id=null)
    {
        $this->type = $type;
        $this->id = $id;
    }

    public function formatPing(): string {
        switch ($this->type){
            case DiscordMentionType::Everyone: {
                return "@everyone";
            }
            case  DiscordMentionType::Here: {
                return "@here";
            }
            case DiscordMentionType::Role: {
                return "<@&$this->id>";
            }
            case DiscordMentionType::User: {
                return "<@$this->id>";
            }
        }
        return "";
    }
}