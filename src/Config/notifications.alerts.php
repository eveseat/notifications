<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
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
        // Core Notifications
        'created_user' => [
            'label' => 'alerts.created_user',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Seat\Mail\CreatedUser::class,
                'slack' => \Seat\Notifications\Notifications\Seat\Slack\CreatedUser::class,
            ],
        ],
        'disabled_token' => [
            'label' => 'alerts.disabled_token',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Seat\Slack\DisabledToken::class,
            ],
        ],
        'enabled_token' => [
            'label' => 'alerts.enabled_token',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Seat\Slack\EnabledToken::class,
            ],
        ],
    ],
    'char' => [
        //
        // Esi Character Notifications
        //
        'AcceptedAlly' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'AcceptedSurrender' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'AllAnchoringMsg' => [
            'label'   => 'alerts.alliance_anchoring',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\AllAnchoringMsg::class,
            ],
        ],
        'AllMaintenanceBillMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'AllStrucInvulnerableMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'AllStructVulnerableMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'AllWarCorpJoinedAllianceMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'AllWarDeclaredMsg' => [
            'label'   => 'alerts.alliance_war_declared',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Wars\Slack\AllWarDeclaredMsg::class,
            ],
        ],
        'AllWarInvalidatedMsg' => [
            'label'   => 'alerts.alliance_war_invalidated',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Wars\Slack\AllWarInvalidatedMsg::class,
            ],
        ],
        'AllWarRetractedMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'AllWarSurrenderMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'AllianceCapitalChanged' => [
            'label'   => 'alerts.alliance_capital_changed',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Alliances\Slack\AllianceCapitalChanged::class,
            ],
        ],
        'AllianceWarDeclaredV2' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'AllyContractCancelled' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'AllyJoinedWarAggressorMsg' => [
            'label'   => 'alerts.ally_joined_war_aggressor',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Wars\Slack\AllyJoinedWarAggressorMsg::class,
            ],
        ],
        'AllyJoinedWarAllyMsg' => [
            'label'   => 'alerts.ally_joined_war_ally',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Wars\Slack\AllyJoinedWarAllyMsg::class,
            ],
        ],
        'AllyJoinedWarDefenderMsg' => [
            'label'   => 'alerts.ally_joined_war_defender',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Wars\Slack\AllyJoinedWarDefenderMsg::class,
            ],
        ],
        'BattlePunishFriendlyFire' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'BillOutOfMoneyMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'BillPaidCorpAllMsg' => [
            'label'   => 'alerts.bill_paid_corporation_alliance',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Corporations\Slack\BillPaidCorpAllMsg::class,
            ],
        ],
        'BountyClaimMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'BountyESSShared' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'BountyESSTaken' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'BountyPlacedAlliance' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'BountyPlacedChar' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'BountyPlacedCorp' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'BountyYourBountyClaimed' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'BuddyConnectContactAdd' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CharAppAcceptMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CharAppRejectMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CharAppWithdrawMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CharLeftCorpMsg' => [
            'label'   => 'alerts.character_left_corporation',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Corporations\Mail\CharLeftCorpMsg::class,
                'slack' => \Seat\Notifications\Notifications\Corporations\Slack\CharLeftCorpMsg::class,
            ],
        ],
        'CharMedalMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CharTerminationMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CloneActivationMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CloneActivationMsg2' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CloneMovedMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CloneRevokedMsg1' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CloneRevokedMsg2' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CombatOperationFinished' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'ContactAdd' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'ContactEdit' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'ContainerPasswordMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpAllBillMsg' => [
            'label'   => 'alerts.corporation_alliance_bill',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Corporations\Mail\CorpAllBillMsg::class,
                'slack' => \Seat\Notifications\Notifications\Corporations\Slack\CorpAllBillMsg::class,
            ],
        ],
        'CorpAppAcceptMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpAppInvitedMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpAppNewMsg' => [
            'label'   => 'alerts.corporation_application_new',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Corporations\Slack\CorpAppNewMsg::class,
            ],
        ],
        'CorpAppRejectCustomMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpAppRejectMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpBecameWarEligible' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpDividendMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpFriendlyFireDisableTimerCompleted' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpFriendlyFireDisableTimerStarted' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpFriendlyFireEnableTimerCompleted' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpFriendlyFireEnableTimerStarted' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpKicked' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpLiquidationMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpNewCEOMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpNewsMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpNoLongerWarEligible' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpOfficeExpirationMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpStructLostMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpTaxChangeMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpVoteCEORevokedMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpVoteMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpWarDeclaredMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpWarDeclaredV2' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpWarFightingLegalMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpWarInvalidatedMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpWarRetractedMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CorpWarSurrenderMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'CustomsMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'DeclareWar' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'DistrictAttacked' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'DustAppAcceptedMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'EntosisCaptureStarted' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'FWAllianceKickMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'FWAllianceWarningMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'FWCharKickMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'FWCharRankGainMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'FWCharRankLossMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'FWCharWarningMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'FWCorpJoinMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'FWCorpKickMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'FWCorpLeaveMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'FWCorpWarningMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'FacWarCorpJoinRequestMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'FacWarCorpJoinWithdrawMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'FacWarCorpLeaveRequestMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'FacWarCorpLeaveWithdrawMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'FacWarLPDisqualifiedEvent' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'FacWarLPDisqualifiedKill' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'FacWarLPPayoutEvent' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'FacWarLPPayoutKill' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'GameTimeAdded' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'GameTimeReceived' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'GameTimeSent' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'GiftReceived' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'IHubDestroyedByBillFailure' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'IncursionCompletedMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'IndustryOperationFinished' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'IndustryTeamAuctionLost' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'IndustryTeamAuctionWon' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'InfrastructureHubBillAboutToExpire' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'InsuranceExpirationMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'InsuranceFirstShipMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'InsuranceInvalidatedMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'InsuranceIssuedMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'InsurancePayoutMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'InvasionSystemLogin' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'JumpCloneDeletedMsg1' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'JumpCloneDeletedMsg2' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'KillReportFinalBlow' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'KillReportVictim' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'KillRightAvailable' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'KillRightAvailableOpen' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'KillRightEarned' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'KillRightUnavailable' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'KillRightUnavailableOpen' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'KillRightUsed' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'LocateCharMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'MadeWarMutual' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'MercOfferRetractedMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'MercOfferedNegotiationMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'MissionOfferExpirationMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'MissionTimeoutMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'MoonminingAutomaticFracture' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'MoonminingExtractionCancelled' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'MoonminingExtractionFinished' => [
            'label'   => 'alerts.moon_mining_extraction_finished',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Structures\Mail\MoonMiningExtractionFinished::class,
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\MoonMiningExtractionFinished::class,
            ],
        ],
        'MoonminingExtractionStarted' => [
            'label'   => 'alerts.moon_mining_extraction_started',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Structures\Mail\MoonMiningExtractionStarted::class,
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\MoonMiningExtractionStarted::class,
            ],
        ],
        'MoonminingLaserFired' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'MutualWarExpired' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'MutualWarInviteAccepted' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'MutualWarInviteRejected' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'MutualWarInviteSent' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'NPCStandingsGained' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'NPCStandingsLost' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'OfferToAllyRetracted' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'OfferedSurrender' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'OfferedToAlly' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'OldLscMessages' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'OperationFinished' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'OrbitalAttacked' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'OrbitalReinforced' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'OwnershipTransferred' => [
            'label'   => 'alerts.ownership_transferred',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Structures\Mail\OwnershipTransferred::class,
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\OwnershipTransferred::class,
            ],
        ],
        'RaffleCreated' => [
            'label'   => 'alerts.raffle_created',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Characters\Slack\RaffleCreated::class,
            ],
        ],
        'RaffleExpired' => [
            'label'   => 'alerts.raffle_expired',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Characters\Slack\RaffleExpired::class,
            ],
        ],
        'RaffleFinished' => [
            'label'   => 'alerts.raffle_finished',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Characters\Slack\RaffleFinished::class,
            ],
        ],
        'ReimbursementMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'ResearchMissionAvailableMsg' => [
            'label'   => 'alerts.research_mission_available',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Characters\Slack\ResearchMissionAvailableMsg::class,
            ],
        ],
        'RetractsWar' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'SeasonalChallengeCompleted' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'SovAllClaimAquiredMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'SovAllClaimLostMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'SovCommandNodeEventStarted' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'SovCorpBillLateMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'SovCorpClaimFailMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'SovDisruptorMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'SovStationEnteredFreeport' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'SovStructureDestroyed' => [
            'label'   => 'alerts.sovereignty_structure_destroyed',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Sovereignties\Mail\SovStructureDestroyed::class,
                'slack' => \Seat\Notifications\Notifications\Sovereignties\Slack\SovStructureDestroyed::class,
            ],
        ],
        'SovStructureReinforced' => [
            'label'   => 'alerts.sovereignty_structure_reinforced',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Sovereignties\Mail\SovStructureReinforced::class,
                'slack' => \Seat\Notifications\Notifications\Sovereignties\Slack\SovStructureReinforced::class,
            ],
        ],
        'SovStructureSelfDestructCancel' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'SovStructureSelfDestructFinished' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'SovStructureSelfDestructRequested' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'SovereigntyIHDamageMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'SovereigntySBUDamageMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'SovereigntyTCUDamageMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'StationAggressionMsg1' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'StationAggressionMsg2' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'StationConquerMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'StationServiceDisabled' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'StationServiceEnabled' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'StationStateChangeMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'StoryLineMissionAvailableMsg' => [
            'label'   => 'alerts.story_line_mission_available',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Characters\Slack\StoryLineMissionAvailableMsg::class,
            ],
        ],
        'StructureAnchoring' => [
            'label'   => 'alerts.structure_anchoring',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureAnchoring::class,
            ],
        ],
        'StructureCourierContractChanged' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'StructureDestroyed' => [
            'label'   => 'alerts.structure_destroyed',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureDestroyed::class,
            ],
        ],
        'StructureFuelAlert' => [
            'label'   => 'alerts.structure_fuel_alert',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Structures\Mail\StructureFuelAlert::class,
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureFuelAlert::class,
            ],
        ],
        'StructureItemsDelivered' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'StructureItemsMovedToSafety' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'StructureLostArmor' => [
            'label'   => 'alerts.structure_lost_armor',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureLostArmor::class,
            ],
        ],
        'StructureLostShields' => [
            'label'   => 'alerts.structure_lost_shield',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureLostShields::class,
            ],
        ],
        'StructureOnline' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'StructureServicesOffline' => [
            'label'   => 'alerts.structure_services_offline',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Structures\Mail\StructureServicesOffline::class,
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureServicesOffline::class,
            ],
        ],
        'StructureUnanchoring' => [
            'label'   => 'alerts.structure_unanchoring',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureUnanchoring::class,
            ],
        ],
        'StructureUnderAttack' => [
            'label'   => 'alerts.structure_under_attack',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Structures\Mail\StructureUnderAttack::class,
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureUnderAttack::class,
            ],
        ],
        'StructureWentHighPower' => [
            'label'   => 'alerts.structure_went_high_power',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureWentHighPower::class,
            ],
        ],
        'StructureWentLowPower' => [
            'label'   => 'alerts.structure_went_low_power',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureWentLowPower::class,
            ],
        ],
        'StructuresJobsCancelled' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'StructuresJobsPaused' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'StructuresReinforcementChanged' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'TowerAlertMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'TowerResourceAlertMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'TransactionReversalMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'TutorialMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'WarAdopted ' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'WarAllyInherited' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'WarAllyOfferDeclinedMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'WarConcordInvalidates' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'WarDeclared' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'WarHQRemovedFromSpace' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'WarInherited' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'WarInvalid' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'WarRetracted' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'WarRetractedByConcord' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'WarSurrenderDeclinedMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
        'WarSurrenderOfferMsg' => [
            'label'   => 'alerts.',
            'handlers' => [],
        ],
    ],
    'corp' => [
        'inactive_member' => [
            'label' => 'alerts.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Corporations\Slack\InActiveCorpMember::class,
            ],
        ],
    ],
];
