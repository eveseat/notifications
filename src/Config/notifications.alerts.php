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
        'newaccount' => [
            'name'     => 'New SeAT Account',
            'alert'    => \Seat\Notifications\Alerts\Seat\NewAccount::class,
            'notifier' => \Seat\Notifications\Notifications\NewAccount::class,
        ],
    ],
    'char' => [
        //
        // Esi Character Notifications
        //
        'AcceptedAlly' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'AcceptedSurrender' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'AllAnchoringMsg' => [
            'label'   => 'notifications.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\AllAnchoringMsg::class,
            ],
        ],
        'AllMaintenanceBillMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'AllStrucInvulnerableMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'AllStructVulnerableMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'AllWarCorpJoinedAllianceMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'AllWarDeclaredMsg' => [
            'label'   => 'notifications.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Wars\Slack\AllWarDeclaredMsg::class,
            ],
        ],
        'AllWarInvalidatedMsg' => [
            'label'   => 'notifications.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Wars\Slack\AllWarInvalidatedMsg::class,
            ],
        ],
        'AllWarRetractedMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'AllWarSurrenderMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'AllianceCapitalChanged' => [
            'label'   => 'notifications.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Alliances\Slack\AllianceCapitalChanged::class,
            ],
        ],
        'AllianceWarDeclaredV2' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'AllyContractCancelled' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'AllyJoinedWarAggressorMsg' => [
            'label'   => 'notifications.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Wars\Slack\AllyJoinedWarAggressorMsg::class,
            ],
        ],
        'AllyJoinedWarAllyMsg' => [
            'label'   => 'notifications.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Wars\Slack\AllyJoinedWarAllyMsg::class,
            ],
        ],
        'AllyJoinedWarDefenderMsg' => [
            'label'   => 'notifications.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Wars\Slack\AllyJoinedWarDefenderMsg::class,
            ],
        ],
        'BattlePunishFriendlyFire' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'BillOutOfMoneyMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'BillPaidCorpAllMsg' => [
            'label'   => 'notifications.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Corporations\Slack\BillPaidCorpAllMsg::class,
            ],
        ],
        'BountyClaimMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'BountyESSShared' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'BountyESSTaken' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'BountyPlacedAlliance' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'BountyPlacedChar' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'BountyPlacedCorp' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'BountyYourBountyClaimed' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'BuddyConnectContactAdd' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CharAppAcceptMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CharAppRejectMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CharAppWithdrawMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CharLeftCorpMsg' => [
            'label'   => 'notifications.',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Corporations\Mail\CharLeftCorpMsg::class,
                'slack' => \Seat\Notifications\Notifications\Corporations\Slack\CharLeftCorpMsg::class,
            ],
        ],
        'CharMedalMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CharTerminationMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CloneActivationMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CloneActivationMsg2' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CloneMovedMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CloneRevokedMsg1' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CloneRevokedMsg2' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CombatOperationFinished' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'ContactAdd' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'ContactEdit' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'ContainerPasswordMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpAllBillMsg' => [
            'label'   => 'notifications.',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Corporations\Mail\CorpAllBillMsg::class,
                'slack' => \Seat\Notifications\Notifications\Corporations\Slack\CorpAllBillMsg::class,
            ],
        ],
        'CorpAppAcceptMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpAppInvitedMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpAppNewMsg' => [
            'label'   => 'notifications.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Corporations\Slack\CorpAppNewMsg::class,
            ],
        ],
        'CorpAppRejectCustomMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpAppRejectMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpBecameWarEligible' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpDividendMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpFriendlyFireDisableTimerCompleted' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpFriendlyFireDisableTimerStarted' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpFriendlyFireEnableTimerCompleted' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpFriendlyFireEnableTimerStarted' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpKicked' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpLiquidationMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpNewCEOMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpNewsMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpNoLongerWarEligible' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpOfficeExpirationMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpStructLostMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpTaxChangeMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpVoteCEORevokedMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpVoteMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpWarDeclaredMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpWarDeclaredV2' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpWarFightingLegalMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpWarInvalidatedMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpWarRetractedMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CorpWarSurrenderMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'CustomsMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'DeclareWar' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'DistrictAttacked' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'DustAppAcceptedMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'EntosisCaptureStarted' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'FWAllianceKickMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'FWAllianceWarningMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'FWCharKickMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'FWCharRankGainMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'FWCharRankLossMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'FWCharWarningMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'FWCorpJoinMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'FWCorpKickMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'FWCorpLeaveMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'FWCorpWarningMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'FacWarCorpJoinRequestMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'FacWarCorpJoinWithdrawMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'FacWarCorpLeaveRequestMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'FacWarCorpLeaveWithdrawMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'FacWarLPDisqualifiedEvent' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'FacWarLPDisqualifiedKill' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'FacWarLPPayoutEvent' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'FacWarLPPayoutKill' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'GameTimeAdded' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'GameTimeReceived' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'GameTimeSent' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'GiftReceived' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'IHubDestroyedByBillFailure' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'IncursionCompletedMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'IndustryOperationFinished' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'IndustryTeamAuctionLost' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'IndustryTeamAuctionWon' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'InfrastructureHubBillAboutToExpire' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'InsuranceExpirationMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'InsuranceFirstShipMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'InsuranceInvalidatedMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'InsuranceIssuedMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'InsurancePayoutMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'InvasionSystemLogin' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'JumpCloneDeletedMsg1' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'JumpCloneDeletedMsg2' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'KillReportFinalBlow' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'KillReportVictim' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'KillRightAvailable' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'KillRightAvailableOpen' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'KillRightEarned' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'KillRightUnavailable' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'KillRightUnavailableOpen' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'KillRightUsed' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'LocateCharMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'MadeWarMutual' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'MercOfferRetractedMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'MercOfferedNegotiationMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'MissionOfferExpirationMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'MissionTimeoutMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'MoonminingAutomaticFracture' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'MoonminingExtractionCancelled' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'MoonminingExtractionFinished' => [
            'label'   => 'notifications.',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Structures\Mail\MoonMiningExtractionFinished::class,
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\MoonMiningExtractionFinished::class,
            ],
        ],
        'MoonminingExtractionStarted' => [
            'label'   => 'notifications.',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Structures\Mail\MoonMiningExtractionStarted::class,
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\MoonMiningExtractionStarted::class,
            ],
        ],
        'MoonminingLaserFired' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'MutualWarExpired' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'MutualWarInviteAccepted' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'MutualWarInviteRejected' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'MutualWarInviteSent' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'NPCStandingsGained' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'NPCStandingsLost' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'OfferToAllyRetracted' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'OfferedSurrender' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'OfferedToAlly' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'OldLscMessages' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'OperationFinished' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'OrbitalAttacked' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'OrbitalReinforced' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'OwnershipTransferred' => [
            'label'   => 'notifications.',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Structures\Mail\OwnershipTransferred::class,
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\OwnershipTransferred::class,
            ],
        ],
        'RaffleCreated' => [
            'label'   => 'notifications.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Characters\Slack\RaffleCreated::class,
            ],
        ],
        'RaffleExpired' => [
            'label'   => 'notifications.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Characters\Slack\RaffleExpired::class,
            ],
        ],
        'RaffleFinished' => [
            'label'   => 'notifications.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Characters\Slack\RaffleFinished::class,
            ],
        ],
        'ReimbursementMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'ResearchMissionAvailableMsg' => [
            'label'   => 'notifications.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Characters\Slack\ResearchMissionAvailableMsg::class,
            ],
        ],
        'RetractsWar' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'SeasonalChallengeCompleted' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'SovAllClaimAquiredMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'SovAllClaimLostMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'SovCommandNodeEventStarted' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'SovCorpBillLateMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'SovCorpClaimFailMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'SovDisruptorMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'SovStationEnteredFreeport' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'SovStructureDestroyed' => [
            'label'   => 'notifications.',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Sovereignties\Mail\SovStructureDestroyed::class,
                'slack' => \Seat\Notifications\Notifications\Sovereignties\Slack\SovStructureDestroyed::class,
            ],
        ],
        'SovStructureReinforced' => [
            'label'   => 'notifications.',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Sovereignties\Mail\SovStructureReinforced::class,
                'slack' => \Seat\Notifications\Notifications\Sovereignties\Slack\SovStructureReinforced::class,
            ],
        ],
        'SovStructureSelfDestructCancel' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'SovStructureSelfDestructFinished' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'SovStructureSelfDestructRequested' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'SovereigntyIHDamageMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'SovereigntySBUDamageMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'SovereigntyTCUDamageMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'StationAggressionMsg1' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'StationAggressionMsg2' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'StationConquerMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'StationServiceDisabled' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'StationServiceEnabled' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'StationStateChangeMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'StoryLineMissionAvailableMsg' => [
            'label'   => 'notifications.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Characters\Slack\StoryLineMissionAvailableMsg::class,
            ],
        ],
        'StructureAnchoring' => [
            'label'   => 'notifications.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureAnchoring::class,
            ],
        ],
        'StructureCourierContractChanged' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'StructureDestroyed' => [
            'label'   => 'notifications.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureDestroyed::class,
            ],
        ],
        'StructureFuelAlert' => [
            'label'   => 'notifications.',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Structures\Mail\StructureFuelAlert::class,
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureFuelAlert::class,
            ],
        ],
        'StructureItemsDelivered' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'StructureItemsMovedToSafety' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'StructureLostArmor' => [
            'label'   => 'notifications.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureLostArmor::class,
            ],
        ],
        'StructureLostShields' => [
            'label'   => 'notifications.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureLostShields::class,
            ],
        ],
        'StructureOnline' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'StructureServicesOffline' => [
            'label'   => 'notifications.',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Structures\Mail\StructureServicesOffline::class,
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureServicesOffline::class,
            ],
        ],
        'StructureUnanchoring' => [
            'label'   => 'notifications.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureUnanchoring::class,
            ],
        ],
        'StructureUnderAttack' => [
            'label'   => 'notifications.',
            'handlers' => [
                'mail' => \Seat\Notifications\Notifications\Structures\Mail\StructureUnderAttack::class,
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureUnderAttack::class,
            ],
        ],
        'StructureWentHighPower' => [
            'label'   => 'notifications.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureWentHighPower::class,
            ],
        ],
        'StructureWentLowPower' => [
            'label'   => 'notifications.',
            'handlers' => [
                'slack' => \Seat\Notifications\Notifications\Structures\Slack\StructureWentLowPower::class,
            ],
        ],
        'StructuresJobsCancelled' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'StructuresJobsPaused' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'StructuresReinforcementChanged' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'TowerAlertMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'TowerResourceAlertMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'TransactionReversalMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'TutorialMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'WarAdopted ' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'WarAllyInherited' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'WarAllyOfferDeclinedMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'WarConcordInvalidates' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'WarDeclared' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'WarHQRemovedFromSpace' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'WarInherited' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'WarInvalid' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'WarRetracted' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'WarRetractedByConcord' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'WarSurrenderDeclinedMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
        ],
        'WarSurrenderOfferMsg' => [
            'label'   => 'notifications.',
            'handlers' => [],
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
    ],
];
