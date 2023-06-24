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

namespace Seat\Notifications\Notifications\Seat\Discord;

use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Web\Models\User;

/**
 * Class CreatedUser.
 *
 * @package Seat\Notifications\Notifications\Seat\Discord
 */
class CreatedUser extends AbstractDiscordNotification
{
    /**
     * @var \Seat\Web\Models\User
     */
    private $user;

    /**
     * CreatedUser constructor.
     *
     * @param  \Seat\Web\Models\User  $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param  DiscordMessage  $message
     * @param  $notifiable
     */
    public function populateMessage(DiscordMessage $message, $notifiable)
    {
        $message
            ->content('A new SeAT account was created!')
            ->embed(function (DiscordEmbed $embed) {
                $embed->timestamp(carbon());
                $embed->author(
                    'SeAT State of Things',
                    asset('web/img/favico/apple-icon-180x180.png'),
                    route('seatcore::character.view.default', [$this->user->main_character_id]));

                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Account Name')
                        ->value($this->user->name)
                        ->long();
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Owner Last Login Source')
                        ->value($this->user->last_login_source)
                        ->long();
                });

                $embed->field(function (DiscordEmbedField $field) {
                    $field->name('Owner Last Login Time')
                        ->value(carbon($this->user->last_login)->toRfc7231String())
                        ->long();
                });
            })
            ->success();
    }
}
