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

namespace Seat\Notifications\Notifications\Seat\Discord;

use Seat\Eveapi\Models\RefreshToken;
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Web\Models\User;

/**
 * Class EnabledToken.
 *
 * @package Seat\Notifications\Notifications\Seat\Discord
 */
class EnabledToken extends AbstractDiscordNotification
{
    /**
     * @var \Seat\Eveapi\Models\RefreshToken
     */
    private $token;

    public function __construct(RefreshToken $token)
    {
        $this->token = $token;
    }

    /**
     * @param  DiscordMessage  $message
     * @param  $notifiable
     */
    public function populateMessage(DiscordMessage $message, $notifiable)
    {
        $message
            ->content('A corporation members token has been enabled!')
            ->from('SeAT State of Things')
            ->embed(function (DiscordEmbed $embed) {
                $embed->timestamp(carbon());
                $embed->author(
                    'SeAT State of Things',
                    asset('web/img/favico/apple-icon-180x180.png'),
                    route('seatcore::corporation.view.tracking', [
                        'corporation_id' => $this->token->affiliation->corporation_id,
                    ]));

                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Character Name')
                        ->value($this->token->affiliation->name)
                        ->long();
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $owner = User::find($this->token->user_id);

                    $field->name('Main Character')
                        ->value($owner->name)
                        ->long();
                });
            })
            ->success();
    }
}
