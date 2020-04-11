<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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
        'charleftcorpmsg' => [
            'name'     => 'Character Left Corporation',
            'alert'    => \Seat\Notifications\Alerts\Char\CharLeftCorpMsg::class,
            'notifier' => \Seat\Notifications\Notifications\CharLeftCorpMsg::class,
        ],
        'structureservicesofflines' => [
            'name'     => 'Structure Services Offline',
            'alert'    => \Seat\Notifications\Alerts\Char\StructureServicesOffline::class,
            'notifier' => \Seat\Notifications\Notifications\StructureServicesOffline::class,
        ],
        'moonminingextractionfinished' => [
            'name'     => 'Moon Extraction Finished',
            'alert'    => \Seat\Notifications\Alerts\Char\MoonMiningExtractionFinished::class,
            'notifier' => \Seat\Notifications\Notifications\MoonMiningExtractionFinished::class,
        ],
        'structurefuelalert' => [
            'name' => 'Structure Fuel Alert',
            'alert' => \Seat\Notifications\Alerts\Char\StructureFuelAlert::class,
            'notifier' => \Seat\Notifications\Notifications\StructureFuelAlert::class,
        ],
        'corpallbillmsg' => [
            'name' => 'Corporation Bill',
            'alert' => \Seat\Notifications\Alerts\Char\CorpAllBillMsg::class,
            'notifier' => \Seat\Notifications\Notifications\CorpAllBillMsg::class,
        ],
        'sovstructurereinforced' => [
            'name'     => 'Sovereignty Structure Reinforced',
            'alert'    => \Seat\Notifications\Alerts\Char\SovStructureReinforced::class,
            'notifier' => \Seat\Notifications\Notifications\SovStructureReinforced::class,
        ],
        'sovstructuredestroyed' => [
            'name'     => 'Sovereignty Structure Destroyed',
            'alert'    => \Seat\Notifications\Alerts\Char\SovStructureDestroyed::class,
            'notifier' => \Seat\Notifications\Notifications\SovStructureDestroyed::class,
        ],
        'structureunderattack' => [
            'name' => 'Structure Under Attack',
            'alert' => \Seat\Notifications\Alerts\Char\StructureUnderAttack::class,
            'notifier' => \Seat\Notifications\Notifications\StructureUnderAttack::class,
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
