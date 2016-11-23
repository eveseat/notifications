<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace Seat\Notifications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class NotificationGroup
 * @package Seat\Notifications\Models
 */
class NotificationGroup extends Model
{

    use Notifiable;

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'type'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function integrations()
    {

        return $this->belongsToMany(Integration::class);
    }

    /**
     * Get all of the configured notification channels.
     *
     * @return mixed
     */
    public function notificationChannels() : array
    {

        return $this->integrations()
            ->pluck('type')->unique()->all();
    }

    /**
     * Return the URL used to route Slack Notifications.
     *
     * @return mixed
     */
    public function routeNotificationForSlack() : string
    {

        return $this->integrations
                   ->where('type', 'slack')
                   ->first()->settings['url'];
    }

    /**
     * Return the email address used to route Mail Notifications.
     *
     * @return mixed
     */
    public function routeNotificationForMail() : string
    {

        return $this->integrations
                   ->where('type', 'mail')
                   ->first()->settings['email'];
    }

}
