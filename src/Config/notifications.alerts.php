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
    // Core Notifications
    'created_user' => [
        'label' => 'notifications::alerts.created_user',
        'handlers' => [
            'mail' => \Seat\Notifications\Notifications\Seat\Mail\CreatedUser::class,
            'slack' => \Seat\Notifications\Notifications\Seat\Slack\CreatedUser::class,
        ],
    ],
    'disabled_token' => [
        'label' => 'notifications::alerts.disabled_token',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Seat\Slack\DisabledToken::class,
        ],
    ],
    'enabled_token' => [
        'label' => 'notifications::alerts.enabled_token',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Seat\Slack\EnabledToken::class,
        ],
    ],
    //
    // Squads
    //
    'squad_application' => [
        'label' => 'notifications::alerts.squad_application',
        'handlers' => [
            'mail' => \Seat\Notifications\Notifications\Seat\Mail\SquadApplicationNotification::class,
            'slack' => \Seat\Notifications\Notifications\Seat\Slack\SquadApplicationNotification::class,
        ],
    ],
    'squad_member' => [
        'label' => 'notifications::alerts.squad_member',
        'handlers' => [
            'mail' => \Seat\Notifications\Notifications\Seat\Mail\SquadMemberNotification::class,
            'slack' => \Seat\Notifications\Notifications\Seat\Slack\SquadMemberNotification::class,
        ],
    ],
    'squad_member_removed' => [
        'label' => 'notifications::alerts.squad_member_removed',
        'handlers' => [
            'mail' => \Seat\Notifications\Notifications\Seat\Mail\SquadMemberRemovedNotification::class,
            'slack' => \Seat\Notifications\Notifications\Seat\Slack\SquadMemberRemovedNotification::class,
        ],
    ],
    //
    // Killmails
    //
    'Killmail' => [
        'label' => 'notifications::alerts.killmails',
        'handlers' => [
            'mail' => \Seat\Notifications\Notifications\Characters\Mail\Killmail::class,
            'slack' => \Seat\Notifications\Notifications\Characters\Slack\Killmail::class,
        ],
    ],
    //
    // Esi Character Notifications
    //
    'AllAnchoringMsg' => [
        'label'   => 'notifications::alerts.alliance_anchoring',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Structures\Slack\AllAnchoringMsg::class,
        ],
    ],
    'AllWarDeclaredMsg' => [
        'label'   => 'notifications::alerts.alliance_war_declared',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Wars\Slack\AllWarDeclaredMsg::class,
        ],
    ],
    'AllWarInvalidatedMsg' => [
        'label'   => 'notifications::alerts.alliance_war_invalidated',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Wars\Slack\AllWarInvalidatedMsg::class,
        ],
    ],
    'AllianceCapitalChanged' => [
        'label'   => 'notifications::alerts.alliance_capital_changed',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Alliances\Slack\AllianceCapitalChanged::class,
        ],
    ],
    'AllyJoinedWarAggressorMsg' => [
        'label'   => 'notifications::alerts.all_joined_war_aggressor',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Wars\Slack\AllyJoinedWarAggressorMsg::class,
        ],
    ],
    'AllyJoinedWarAllyMsg' => [
        'label'   => 'notifications::alerts.ally_joined_war_ally',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Wars\Slack\AllyJoinedWarAllyMsg::class,
        ],
    ],
    'AllyJoinedWarDefenderMsg' => [
        'label'   => 'notifications::alerts.ally_joined_war_defender',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Wars\Slack\AllyJoinedWarDefenderMsg::class,
        ],
    ],
    'BillPaidCorpAllMsg' => [
        'label'   => 'notifications::alerts.bill_paid_corporation_alliance',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Corporations\Slack\BillPaidCorpAllMsg::class,
        ],
    ],
    'CharLeftCorpMsg' => [
        'label'   => 'notifications::alerts.character_left_corporation',
        'handlers' => [
            'mail' => \Seat\Notifications\Notifications\Corporations\Mail\CharLeftCorpMsg::class,
            'slack' => \Seat\Notifications\Notifications\Corporations\Slack\CharLeftCorpMsg::class,
        ],
    ],
    'CorpAllBillMsg' => [
        'label'   => 'notifications::alerts.corporation_alliance_bill',
        'handlers' => [
            'mail' => \Seat\Notifications\Notifications\Corporations\Mail\CorpAllBillMsg::class,
            'slack' => \Seat\Notifications\Notifications\Corporations\Slack\CorpAllBillMsg::class,
        ],
    ],
    'CorpAppNewMsg' => [
        'label'   => 'notifications::alerts.corporation_application_new',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Corporations\Slack\CorpAppNewMsg::class,
        ],
    ],
    'MoonminingExtractionFinished' => [
        'label'   => 'notifications::alerts.moon_mining_extraction_finished',
        'handlers' => [
            'mail' => \Seat\Notifications\Notifications\Structures\Mail\MoonMiningExtractionFinished::class,
            'slack' => \Seat\Notifications\Notifications\Structures\Slack\MoonMiningExtractionFinished::class,
        ],
    ],
    'MoonminingExtractionStarted' => [
        'label'   => 'notifications::alerts.moon_mining_extraction_started',
        'handlers' => [
            'mail' => \Seat\Notifications\Notifications\Structures\Mail\MoonMiningExtractionStarted::class,
            'slack' => \Seat\Notifications\Notifications\Structures\Slack\MoonMiningExtractionStarted::class,
        ],
    ],
    'OrbitalAttacked' => [
        'label'   => 'notifications::alerts.orbital_attacked',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Structures\Slack\OrbitalAttacked::class,
        ],
    ],
    'OwnershipTransferred' => [
        'label'   => 'notifications::alerts.ownership_transferred',
        'handlers' => [
            'mail' => \Seat\Notifications\Notifications\Structures\Mail\OwnershipTransferred::class,
            'slack' => \Seat\Notifications\Notifications\Structures\Slack\OwnershipTransferred::class,
        ],
    ],
    'RaffleCreated' => [
        'label'   => 'notifications::alerts.raffle_created',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Characters\Slack\RaffleCreated::class,
        ],
    ],
    'RaffleExpired' => [
        'label'   => 'notifications::alerts.raffle_expired',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Characters\Slack\RaffleExpired::class,
        ],
    ],
    'RaffleFinished' => [
        'label'   => 'notifications::alerts.raffle_finished',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Characters\Slack\RaffleFinished::class,
        ],
    ],
    'ResearchMissionAvailableMsg' => [
        'label'   => 'notifications::alerts.research_mission_available',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Characters\Slack\ResearchMissionAvailableMsg::class,
        ],
    ],
    'SovStructureDestroyed' => [
        'label'   => 'notifications::alerts.sovereignty_structure_destroyed',
        'handlers' => [
            'mail' => \Seat\Notifications\Notifications\Sovereignties\Mail\SovStructureDestroyed::class,
            'slack' => \Seat\Notifications\Notifications\Sovereignties\Slack\SovStructureDestroyed::class,
        ],
    ],
    'SovStructureReinforced' => [
        'label'   => 'notifications::alerts.sovereignty_structure_reinforced',
        'handlers' => [
            'mail' => \Seat\Notifications\Notifications\Sovereignties\Mail\SovStructureReinforced::class,
            'slack' => \Seat\Notifications\Notifications\Sovereignties\Slack\SovStructureReinforced::class,
        ],
    ],
    'StoryLineMissionAvailableMsg' => [
        'label'   => 'notifications::alerts.story_line_mission_available',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Characters\Slack\StoryLineMissionAvailableMsg::class,
        ],
    ],
    'StructureAnchoring' => [
        'label'   => 'notifications::alerts.structure_anchoring',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureAnchoring::class,
        ],
    ],
    'StructureDestroyed' => [
        'label'   => 'notifications::alerts.structure_destroyed',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureDestroyed::class,
        ],
    ],
    'StructureFuelAlert' => [
        'label'   => 'notifications::alerts.structure_fuel_alert',
        'handlers' => [
            'mail' => \Seat\Notifications\Notifications\Structures\Mail\StructureFuelAlert::class,
            'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureFuelAlert::class,
        ],
    ],
    'StructureLostArmor' => [
        'label'   => 'notifications::alerts.structure_lost_armor',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureLostArmor::class,
        ],
    ],
    'StructureLostShields' => [
        'label'   => 'notifications::alerts.structure_lost_shield',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureLostShields::class,
        ],
    ],
    'StructureServicesOffline' => [
        'label'   => 'notifications::alerts.structure_services_offline',
        'handlers' => [
            'mail' => \Seat\Notifications\Notifications\Structures\Mail\StructureServicesOffline::class,
            'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureServicesOffline::class,
        ],
    ],
    'StructureUnanchoring' => [
        'label'   => 'notifications::alerts.structure_unanchoring',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureUnanchoring::class,
        ],
    ],
    'StructureUnderAttack' => [
        'label'   => 'notifications::alerts.structure_under_attack',
        'handlers' => [
            'mail' => \Seat\Notifications\Notifications\Structures\Mail\StructureUnderAttack::class,
            'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureUnderAttack::class,
        ],
    ],
    'StructureWentHighPower' => [
        'label'   => 'notifications::alerts.structure_went_high_power',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureWentHighPower::class,
        ],
    ],
    'StructureWentLowPower' => [
        'label'   => 'notifications::alerts.structure_went_low_power',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureWentLowPower::class,
        ],
    ],
    'inactive_member' => [
        'label' => 'notifications::alerts.war_inactive_member',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Corporations\Slack\InActiveCorpMember::class,
        ],
    ],
    'contract_created' => [
        'label' => 'notifications::alerts.contract_created',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Contracts\Slack\ContractNotification::class,
        ],
    ],
    // even though the test notification can't be added to a notification group, it is here for consistency
    'test_integration' => [
        'label' => 'notifications::notifications.test_integration', // Since it's hidden, it shouldn't really matter
        'handlers' => [
            'mail' => \Seat\Notifications\Notifications\Seat\Mail\TestNotification::class,
            'slack' => \Seat\Notifications\Notifications\Seat\Slack\TestNotification::class,
        ],
        'hidden' => true
    ]
];
