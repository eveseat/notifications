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

return [

    'notifications' => [
        'name'          => 'notifications',
        'label'         => 'Notifications',
        'icon'          => 'fas fa-bell',
        'route_segment' => 'notifications',
        'entries'       => [
            [
                'name'       => 'integrations',
                'label'      => 'Integrations',
                'permission' => 'notifications.setup',
                'icon'       => 'fas fa-toggle-on',
                'route'      => 'seatcore::notifications.integrations.list',
            ],
            [
                'name'  => 'notifications',
                'label' => 'My Notifications',
                'icon'  => 'fas fa-envelope-square',
                'route' => 'seatcore::notifications.list',
            ],
            [
                'name'       => 'notification.groups',
                'label'      => 'Notifications Groups',
                'permission' => 'notifications.setup',
                'icon'       => 'fas fa-object-group',
                'route'      => 'seatcore::notifications.groups.list',
            ],
        ],
    ],

];
