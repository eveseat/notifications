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

use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class StructureServicesOffline.
 *
 * @package Seat\Notifications\Notifications\Structures
 */
class StructureServicesOffline extends AbstractDiscordNotification
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
            ->content('A structure service has been shutdown!')
            ->embed(function (DiscordEmbed $embed) {
                $embed->timestamp($this->notification->timestamp);
                $embed->color($this->notification->text['hostileState'] ? 13632027 : 16098851);
                $embed->author('SeAT Structure Monitor', asset('web/img/favico/apple-icon-180x180.png'));

                $embed->field(function (DiscordEmbedField $field) {

                    $system = MapDenormalize::find($this->notification->text['solarsystemID']);

                    $field->name('System')
                        ->value(
                            $this->zKillBoardToDiscordLink(
                                'system',
                                $system->itemID,
                                sprintf('%s (%s)', $system->itemName, $system->security)
                            )
                        );
                })->field(function (DiscordEmbedField $field) {

                    $type = InvType::find($this->notification->text['structureShowInfoData'][1]);

                    $field->name('Structure')
                        ->value($type->typeName);

                });

            })->embed(function (DiscordEmbed $embed) {

                foreach ($this->notification->text['listOfServiceModuleIDs'] as $type_id) {
                    $embed->field(function ($field) use ($type_id) {

                            $type = InvType::find($type_id);

                            $field->value($type->typeName);

                    });
                }

                $embed->color('danger');
            });
    }
}
