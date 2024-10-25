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

namespace Seat\Notifications\Notifications\Structures\Discord;

use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class StructureWentHighPower.
 *
 * @package Seat\Notifications\Notifications\Structures\Slack
 */
class StructureWentHighPower extends AbstractDiscordNotification
{
    use NotificationTools;

    private CharacterNotification $notification;

    public function __construct(CharacterNotification $notification)
    {
        $this->notification = $notification;
    }

    public function populateMessage(DiscordMessage $message, $notifiable): void
    {
        $message
            ->content('A Structure went into High Power!')
            ->from('SeAT Structure Monitor')
            ->embed(function (DiscordEmbed $embed) {
                $embed->timestamp($this->notification->timestamp);
                $embed->color(DiscordMessage::SUCCESS);
                $embed->author('SeAT Structure Monitor', asset('web/img/favico/apple-icon-180x180.png'));

                $embed->field(function (DiscordEmbedField $field) {
                    $system = MapDenormalize::firstOrNew(
                        ['itemID' => $this->notification->text['solarsystemID']],
                        ['itemName' => trans('web::seat.unknown')]
                    );

                    $field->name('System')
                        ->value(
                            $this->zKillBoardToDiscordLink(
                                'system',
                                $system->itemID,
                                sprintf('%s (%s)', $system->itemName, number_format($system->security, 2))
                            )
                        );
                });

                $embed->field(function (DiscordEmbedField $field) {
                    // Find the structure by its ID from the notification data.
                    // If the structure ID exists in the notification, retrieve it from the UniverseStructure model.
                    $structure = UniverseStructure::find($this->notification->text['structureID']);

                    // Retrieve the structure's type information using the structureShowInfoData from the notification.
                    // The second index ([1]) contains the type ID which is used to look up the structure type from the InvType model.
                    $type = InvType::find($this->notification->text['structureShowInfoData'][1]);

                    // Initialize a default title for the structure field as 'Structure'.
                    $title = 'Structure';

                    // If a structure is found (i.e., it's not null), set the title to the name of the structure.
                    if (! is_null($structure)) {
                        $title = $structure->name;
                    }

                    // Set the field's name to the title (either 'Structure' or the structure's actual name).
                    // Set the field's value to a zKillboard link, formatted for Discord. This uses the structure's type ID and name.
                    $field->name($title)
                        ->value($this->zKillBoardToDiscordLink('ship', $type->typeID, $type->typeName));
                });
            });
    }
}
