<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018  Leon Jacobs
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

namespace Seat\Notifications\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

/**
 * Class NewApiKey.
 * @package Seat\Notifications\Notifications
 */
class NewRefreshToken extends Notification
{
    /**
     * @var
     */
    private $token;

    /**
     * Create a new notification instance.
     *
     * @param $token
     */
    public function __construct($token)
    {

        $this->token = $token;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {

        return $notifiable->notificationChannels();
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        return (new MailMessage)
            ->success()
            ->greeting('Heads up!')
            ->line('We have a new Refresh Token added to SeAT!')
            ->line(
                'The key was added by ' . $this->token->user->name . ' that last ' .
                'logged in from ' . $this->token->user->last_login_source . ' at ' .
                $this->token->owner->last_login . '.'
            )
            ->action('Check it out on SeAT', route('character.view.sheet', [
                'character_id' => $this->token->character_id,
            ]));
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param $notifiable
     *
     * @return $this
     */
    public function toSlack($notifiable)
    {

        return (new SlackMessage)
            ->success()
            ->content('A new Refresh Token was added!')
            ->attachment(function ($attachment) {

                $attachment->title('Refresh Token Details', route('character.view.sheet', [
                    'key_id' => $this->token->key_id,
                ]))->fields([
                    'Character ID'            => $this->token->character_id,
                    'Token Owner'             => $this->token->user->name,
                    'Owner Last Login Source' => $this->token->user->last_login_source,
                    'Owner Last Login Time'   => $this->token->user->last_login,
                ]);
            });
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {

        return [
            'character_id'           => $this->token->character_id,
            'token_owner'            => $this->token->user->name,
            'user_last_login_source' => $this->token->user->last_login_source,
            'user_last_login_time'   => $this->token->user->last_login,
        ];
    }
}
