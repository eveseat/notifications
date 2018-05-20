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

return [

    'seat' => [
        'newaccount' => [
            'name'     => 'New SeAT Account',
            'alert'    => \Seat\Notifications\Alerts\Seat\NewAccount::class,
            'notifier' => \Seat\Notifications\Notifications\NewAccount::class,
        ],
    ],
    'char' => [
        'newmailmessage' => [
            'name'     => 'New Mail Message',
            'alert'    => \Seat\Notifications\Alerts\Char\NewMailMessage::class,
            'notifier' => \Seat\Notifications\Notifications\NewMailMessage::class,
        ],
    ],
    'corp' => [

        'inactivemember'      => [
            'name'     => 'Inactive Corp Members',
            'alert'    => \Seat\Notifications\Alerts\Corp\MemberInactivity::class,
            'notifier' => \Seat\Notifications\Notifications\InActiveCorpMember::class,
        ],
        'killmails'           => [
            'name'     => 'Killmails',
            'alert'    => \Seat\Notifications\Alerts\Corp\Killmails::class,
            'notifier' => \Seat\Notifications\Notifications\Killmail::class,
        ],
        'memberapistate'      => [
            'name'     => 'Member API State',
            'alert'    => \Seat\Notifications\Alerts\Corp\MemberTokenState::class,
            'notifier' => \Seat\Notifications\Notifications\MemberTokenState::class,
        ],
//        'starbasefuel'        => [
//            'name'     => 'Low Starbase Fuel',
//            'alert'    => \Seat\Notifications\Alerts\Corp\StarbaseFuel::class,
//            'notifier' => \Seat\Notifications\Notifications\StarbaseFuel::class,
//        ],
//        'starbasestatechange' => [
//            'name'     => 'Starbase State Change',
//            'alert'    => \Seat\Notifications\Alerts\Corp\StarbaseStateChange::class,
//            'notifier' => \Seat\Notifications\Notifications\StarbaseStateChange::class,
//        ],
//        'starbasesiphons'     => [
//            'name'     => 'Starbase Siphon Detection',
//            'alert'    => \Seat\Notifications\Alerts\Corp\StarbaseSiphons::class,
//            'notifier' => \Seat\Notifications\Notifications\StarbaseSiphons::class,
//        ],
    ],
];
