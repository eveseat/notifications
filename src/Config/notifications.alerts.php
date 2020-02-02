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
    ],
    'char' => [
        //
        // Esi Character Notifications
        //
        'AcceptedAlly' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'AcceptedSurrender' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'AllAnchoringMsg' => [
            'label'   => 'notifications::alerts.alliance_anchoring',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\AllAnchoringMsg::class,
            ],
        ],
        'AllMaintenanceBillMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'AllStrucInvulnerableMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'AllStructVulnerableMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'AllWarCorpJoinedAllianceMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
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
        'AllWarRetractedMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'AllWarSurrenderMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'AllianceCapitalChanged' => [
            'label'   => 'notifications::alerts.alliance_capital_changed',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Alliances\Slack\AllianceCapitalChanged::class,
            ],
        ],
        'AllianceWarDeclaredV2' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'AllyContractCancelled' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'AllyJoinedWarAggressorMsg' => [
            'label'   => 'notifications::alerts.ally_joined_war_aggressor',
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
        'BattlePunishFriendlyFire' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'BillOutOfMoneyMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'BillPaidCorpAllMsg' => [
            'label'   => 'notifications::alerts.bill_paid_corporation_alliance',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Corporations\Slack\BillPaidCorpAllMsg::class,
            ],
        ],
        'BountyClaimMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'BountyESSShared' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'BountyESSTaken' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'BountyPlacedAlliance' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'BountyPlacedChar' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'BountyPlacedCorp' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'BountyYourBountyClaimed' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'BuddyConnectContactAdd' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CharAppAcceptMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CharAppRejectMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CharAppWithdrawMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CharLeftCorpMsg' => [
            'label'   => 'notifications::alerts.character_left_corporation',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Corporations\Mail\CharLeftCorpMsg::class,
                'slack' => \Seat\Notifications\Notifications\Corporations\Slack\CharLeftCorpMsg::class,
            ],
        ],
        'CharMedalMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CharTerminationMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CloneActivationMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CloneActivationMsg2' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CloneMovedMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CloneRevokedMsg1' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CloneRevokedMsg2' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CombatOperationFinished' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'ContactAdd' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'ContactEdit' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'ContainerPasswordMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpAllBillMsg' => [
            'label'   => 'notifications::alerts.corporation_alliance_bill',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Corporations\Mail\CorpAllBillMsg::class,
                'slack' => \Seat\Notifications\Notifications\Corporations\Slack\CorpAllBillMsg::class,
            ],
        ],
        'CorpAppAcceptMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpAppInvitedMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpAppNewMsg' => [
            'label'   => 'notifications::alerts.corporation_application_new',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Corporations\Slack\CorpAppNewMsg::class,
            ],
        ],
        'CorpAppRejectCustomMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpAppRejectMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpBecameWarEligible' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpDividendMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpFriendlyFireDisableTimerCompleted' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpFriendlyFireDisableTimerStarted' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpFriendlyFireEnableTimerCompleted' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpFriendlyFireEnableTimerStarted' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpKicked' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpLiquidationMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpNewCEOMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpNewsMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpNoLongerWarEligible' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpOfficeExpirationMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpStructLostMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpTaxChangeMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpVoteCEORevokedMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpVoteMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpWarDeclaredMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpWarDeclaredV2' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpWarFightingLegalMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpWarInvalidatedMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpWarRetractedMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CorpWarSurrenderMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'CustomsMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'DeclareWar' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'DistrictAttacked' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'DustAppAcceptedMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'EntosisCaptureStarted' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'FWAllianceKickMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'FWAllianceWarningMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'FWCharKickMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'FWCharRankGainMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'FWCharRankLossMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'FWCharWarningMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'FWCorpJoinMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'FWCorpKickMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'FWCorpLeaveMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'FWCorpWarningMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'FacWarCorpJoinRequestMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'FacWarCorpJoinWithdrawMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'FacWarCorpLeaveRequestMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'FacWarCorpLeaveWithdrawMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'FacWarLPDisqualifiedEvent' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'FacWarLPDisqualifiedKill' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'FacWarLPPayoutEvent' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'FacWarLPPayoutKill' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'GameTimeAdded' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'GameTimeReceived' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'GameTimeSent' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'GiftReceived' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'IHubDestroyedByBillFailure' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'IncursionCompletedMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'IndustryOperationFinished' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'IndustryTeamAuctionLost' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'IndustryTeamAuctionWon' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'InfrastructureHubBillAboutToExpire' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'InsuranceExpirationMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'InsuranceFirstShipMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'InsuranceInvalidatedMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'InsuranceIssuedMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'InsurancePayoutMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'InvasionSystemLogin' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'JumpCloneDeletedMsg1' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'JumpCloneDeletedMsg2' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'KillReportFinalBlow' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'KillReportVictim' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'KillRightAvailable' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'KillRightAvailableOpen' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'KillRightEarned' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'KillRightUnavailable' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'KillRightUnavailableOpen' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'KillRightUsed' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'LocateCharMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'MadeWarMutual' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'MercOfferRetractedMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'MercOfferedNegotiationMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'MissionOfferExpirationMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'MissionTimeoutMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'MoonminingAutomaticFracture' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'MoonminingExtractionCancelled' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
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
        'MoonminingLaserFired' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'MutualWarExpired' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'MutualWarInviteAccepted' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'MutualWarInviteRejected' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'MutualWarInviteSent' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'NPCStandingsGained' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'NPCStandingsLost' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'OfferToAllyRetracted' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'OfferedSurrender' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'OfferedToAlly' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'OldLscMessages' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'OperationFinished' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'OrbitalAttacked' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'OrbitalReinforced' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
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
        'ReimbursementMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'ResearchMissionAvailableMsg' => [
            'label'   => 'notifications::alerts.research_mission_available',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Characters\Slack\ResearchMissionAvailableMsg::class,
            ],
        ],
        'RetractsWar' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'SeasonalChallengeCompleted' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'SovAllClaimAquiredMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'SovAllClaimLostMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'SovCommandNodeEventStarted' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'SovCorpBillLateMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'SovCorpClaimFailMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'SovDisruptorMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'SovStationEnteredFreeport' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
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
        'SovStructureSelfDestructCancel' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'SovStructureSelfDestructFinished' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'SovStructureSelfDestructRequested' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'SovereigntyIHDamageMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'SovereigntySBUDamageMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'SovereigntyTCUDamageMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'StationAggressionMsg1' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'StationAggressionMsg2' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'StationConquerMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'StationServiceDisabled' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'StationServiceEnabled' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'StationStateChangeMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
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
        'StructureCourierContractChanged' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
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
        'StructureItemsDelivered' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'StructureItemsMovedToSafety' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
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
        'StructureOnline' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
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
        'StructuresJobsCancelled' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'StructuresJobsPaused' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'StructuresReinforcementChanged' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'TowerAlertMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'TowerResourceAlertMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'TransactionReversalMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'TutorialMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'WarAdopted ' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'WarAllyInherited' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'WarAllyOfferDeclinedMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'WarConcordInvalidates' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'WarDeclared' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'WarHQRemovedFromSpace' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'WarInherited' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'WarInvalid' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'WarRetracted' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'WarRetractedByConcord' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'WarSurrenderDeclinedMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
        'WarSurrenderOfferMsg' => [
            'label'   => 'notifications::alerts.',
            'handlers' => [],
        ],
    ],
    'corp' => [
        'inactive_member' => [
            'label' => 'notifications::alerts.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Corporations\Slack\InActiveCorpMember::class,
            ],
        ],
    ],
];
