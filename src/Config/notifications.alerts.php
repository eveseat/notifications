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
    // Esi Character Notifications
    //
    'AcceptedAlly' => [
        'label'   => 'notifications::alerts.accepted_ally',
        'handlers' => [],
    ],
    'AcceptedSurrender' => [
        'label'   => 'notifications::alerts.accepted_surrender',
        'handlers' => [],
    ],
    'AllAnchoringMsg' => [
        'label'   => 'notifications::alerts.alliance_anchoring',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Structures\Slack\AllAnchoringMsg::class,
        ],
    ],
    'AllMaintenanceBillMsg' => [
        'label'   => 'notifications::alerts.alliance_maintenance_bill_message',
        'handlers' => [],
    ],
    'AllStrucInvulnerableMsg' => [
        'label'   => 'notifications::alerts.alliance_structure_invulnerable_message',
        'handlers' => [],
    ],
    'AllStructVulnerableMsg' => [
        'label'   => 'notifications::alerts.alliance_structure_vulnerable_message',
        'handlers' => [],
    ],
    'AllWarCorpJoinedAllianceMsg' => [
        'label'   => 'notifications::alerts.alliance_war_corp_joined_alliance_message',
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
        'label'   => 'notifications::alerts.alliance_war_retraced_message',
        'handlers' => [],
    ],
    'AllWarSurrenderMsg' => [
        'label'   => 'notifications::alerts.alliance_war_surrender_message',
        'handlers' => [],
    ],
    'AllianceCapitalChanged' => [
        'label'   => 'notifications::alerts.alliance_capital_changed',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Alliances\Slack\AllianceCapitalChanged::class,
        ],
    ],
    'AllianceWarDeclaredV2' => [
        'label'   => 'notifications::alerts.alliance_war_declared_v2',
        'handlers' => [],
    ],
    'AllyContractCancelled' => [
        'label'   => 'notifications::alerts.ally_contract_cancelled',
        'handlers' => [],
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
    'BattlePunishFriendlyFire' => [
        'label'   => 'notifications::alerts.battle_punish_friendly_fire',
        'handlers' => [],
    ],
    'BillOutOfMoneyMsg' => [
        'label'   => 'notifications::alerts.bill_out_of_money_message',
        'handlers' => [],
    ],
    'BillPaidCorpAllMsg' => [
        'label'   => 'notifications::alerts.bill_paid_corporation_alliance',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Corporations\Slack\BillPaidCorpAllMsg::class,
        ],
    ],
    'BountyClaimMsg' => [
        'label'   => 'notifications::alerts.bounty_claim_message',
        'handlers' => [],
    ],
    'BountyESSShared' => [
        'label'   => 'notifications::alerts.bounty_ess_shared',
        'handlers' => [],
    ],
    'BountyESSTaken' => [
        'label'   => 'notifications::alerts.bounty_ess_taken',
        'handlers' => [],
    ],
    'BountyPlacedAlliance' => [
        'label'   => 'notifications::alerts.bounty_placed_alliance',
        'handlers' => [],
    ],
    'BountyPlacedChar' => [
        'label'   => 'notifications::alerts.bounty_placed_character',
        'handlers' => [],
    ],
    'BountyPlacedCorp' => [
        'label'   => 'notifications::alerts.bounty_placed_corporation',
        'handlers' => [],
    ],
    'BountyYourBountyClaimed' => [
        'label'   => 'notifications::alerts.bounty_your_bounty_claimed',
        'handlers' => [],
    ],
    'BuddyConnectContactAdd' => [
        'label'   => 'notifications::alerts.buddy_connect_contact_add',
        'handlers' => [],
    ],
    'CharAppAcceptMsg' => [
        'label'   => 'notifications::alerts.character_application_accept_message',
        'handlers' => [],
    ],
    'CharAppRejectMsg' => [
        'label'   => 'notifications::alerts.character_application_reject_message',
        'handlers' => [],
    ],
    'CharAppWithdrawMsg' => [
        'label'   => 'notifications::alerts.character_application_withdraw_message',
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
        'label'   => 'notifications::alerts.character_medal_message',
        'handlers' => [],
    ],
    'CharTerminationMsg' => [
        'label'   => 'notifications::alerts.character_termination_message',
        'handlers' => [],
    ],
    'CloneActivationMsg' => [
        'label'   => 'notifications::alerts.clone_activation_message',
        'handlers' => [],
    ],
    'CloneActivationMsg2' => [
        'label'   => 'notifications::alerts.clone_activation_message_2',
        'handlers' => [],
    ],
    'CloneMovedMsg' => [
        'label'   => 'notifications::alerts.clone_moved_message',
        'handlers' => [],
    ],
    'CloneRevokedMsg1' => [
        'label'   => 'notifications::alerts.clone_revoked_message_1',
        'handlers' => [],
    ],
    'CloneRevokedMsg2' => [
        'label'   => 'notifications::alerts.clone_revoked_message_2',
        'handlers' => [],
    ],
    'CombatOperationFinished' => [
        'label'   => 'notifications::alerts.combat_operation_finished',
        'handlers' => [],
    ],
    'ContactAdd' => [
        'label'   => 'notifications::alerts.contact_add',
        'handlers' => [],
    ],
    'ContactEdit' => [
        'label'   => 'notifications::alerts.contact_edit',
        'handlers' => [],
    ],
    'ContainerPasswordMsg' => [
        'label'   => 'notifications::alerts.container_password_message',
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
        'label'   => 'notifications::alerts.corporation_application_accept_message',
        'handlers' => [],
    ],
    'CorpAppInvitedMsg' => [
        'label'   => 'notifications::alerts.corporation_application_invited_message',
        'handlers' => [],
    ],
    'CorpAppNewMsg' => [
        'label'   => 'notifications::alerts.corporation_application_new',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Corporations\Slack\CorpAppNewMsg::class,
        ],
    ],
    'CorpAppRejectCustomMsg' => [
        'label'   => 'notifications::alerts.corporation_application_reject_custom_message',
        'handlers' => [],
    ],
    'CorpAppRejectMsg' => [
        'label'   => 'notifications::alerts.corporation_application_reject_message',
        'handlers' => [],
    ],
    'CorpBecameWarEligible' => [
        'label'   => 'notifications::alerts.corporation_became_war_eligible',
        'handlers' => [],
    ],
    'CorpDividendMsg' => [
        'label'   => 'notifications::alerts.corporation_dividend_message',
        'handlers' => [],
    ],
    'CorpFriendlyFireDisableTimerCompleted' => [
        'label'   => 'notifications::alerts.corporation_friendly_fire_disable_timer_completed',
        'handlers' => [],
    ],
    'CorpFriendlyFireDisableTimerStarted' => [
        'label'   => 'notifications::alerts.corporation_friendly_fire_disable_timer_started',
        'handlers' => [],
    ],
    'CorpFriendlyFireEnableTimerCompleted' => [
        'label'   => 'notifications::alerts.corporation_friendly_fire_enable_timer_completed',
        'handlers' => [],
    ],
    'CorpFriendlyFireEnableTimerStarted' => [
        'label'   => 'notifications::alerts.corporation_friendly_fire_enable_timer_started',
        'handlers' => [],
    ],
    'CorpKicked' => [
        'label'   => 'notifications::alerts.corporation_kicked',
        'handlers' => [],
    ],
    'CorpLiquidationMsg' => [
        'label'   => 'notifications::alerts.corporation_liquidation_message',
        'handlers' => [],
    ],
    'CorpNewCEOMsg' => [
        'label'   => 'notifications::alerts.corporation_new_ceo_message',
        'handlers' => [],
    ],
    'CorpNewsMsg' => [
        'label'   => 'notifications::alerts.corporation_new_message',
        'handlers' => [],
    ],
    'CorpNoLongerWarEligible' => [
        'label'   => 'notifications::alerts.corporation_no_longer_war_eligible',
        'handlers' => [],
    ],
    'CorpOfficeExpirationMsg' => [
        'label'   => 'notifications::alerts.corporation_office_expiration_message',
        'handlers' => [],
    ],
    'CorpStructLostMsg' => [
        'label'   => 'notifications::alerts.corporation_structure_lost_message',
        'handlers' => [],
    ],
    'CorpTaxChangeMsg' => [
        'label'   => 'notifications::alerts.corporation_tax_change_message',
        'handlers' => [],
    ],
    'CorpVoteCEORevokedMsg' => [
        'label'   => 'notifications::alerts.corporation_vote_ceo_revoked_message',
        'handlers' => [],
    ],
    'CorpVoteMsg' => [
        'label'   => 'notifications::alerts.corporation_vote_message',
        'handlers' => [],
    ],
    'CorpWarDeclaredMsg' => [
        'label'   => 'notifications::alerts.corporation_war_declared_message',
        'handlers' => [],
    ],
    'CorpWarDeclaredV2' => [
        'label'   => 'notifications::alerts.corporation_war_declared_v2',
        'handlers' => [],
    ],
    'CorpWarFightingLegalMsg' => [
        'label'   => 'notifications::alerts.corporation_war_fighting_legal_message',
        'handlers' => [],
    ],
    'CorpWarInvalidatedMsg' => [
        'label'   => 'notifications::alerts.corporation_war_invalidated_message',
        'handlers' => [],
    ],
    'CorpWarRetractedMsg' => [
        'label'   => 'notifications::alerts.corporation_war_retracted_message',
        'handlers' => [],
    ],
    'CorpWarSurrenderMsg' => [
        'label'   => 'notifications::alerts.corporation_war_surrender_message',
        'handlers' => [],
    ],
    'CustomsMsg' => [
        'label'   => 'notifications::alerts.custom_message',
        'handlers' => [],
    ],
    'DeclareWar' => [
        'label'   => 'notifications::alerts.declare_war',
        'handlers' => [],
    ],
    'DistrictAttacked' => [
        'label'   => 'notifications::alerts.district_attacked',
        'handlers' => [],
    ],
    'DustAppAcceptedMsg' => [
        'label'   => 'notifications::alerts.dust_application_accepted_message',
        'handlers' => [],
    ],
    'EntosisCaptureStarted' => [
        'label'   => 'notifications::alerts.entosis_capture_started',
        'handlers' => [],
    ],
    'FWAllianceKickMsg' => [
        'label'   => 'notifications::alerts.factional_warfare_alliance_kick_message',
        'handlers' => [],
    ],
    'FWAllianceWarningMsg' => [
        'label'   => 'notifications::alerts.factional_warfare_alliance_warning_message',
        'handlers' => [],
    ],
    'FWCharKickMsg' => [
        'label'   => 'notifications::alerts.factional_warfare_kick_message',
        'handlers' => [],
    ],
    'FWCharRankGainMsg' => [
        'label'   => 'notifications::alerts.factional_warfare_character_rank_gain_message',
        'handlers' => [],
    ],
    'FWCharRankLossMsg' => [
        'label'   => 'notifications::alerts.factional_warfare_character_rank_loss_message',
        'handlers' => [],
    ],
    'FWCharWarningMsg' => [
        'label'   => 'notifications::alerts.factional_warfare_character_warning_message',
        'handlers' => [],
    ],
    'FWCorpJoinMsg' => [
        'label'   => 'notifications::alerts.factional_warfare_corporation_join_message',
        'handlers' => [],
    ],
    'FWCorpKickMsg' => [
        'label'   => 'notifications::alerts.factional_warfare_corporation_kick_message',
        'handlers' => [],
    ],
    'FWCorpLeaveMsg' => [
        'label'   => 'notifications::alerts.factional_warfare_corporation_leave_message',
        'handlers' => [],
    ],
    'FWCorpWarningMsg' => [
        'label'   => 'notifications::alerts.factional_warfare_corporation_warning_message',
        'handlers' => [],
    ],
    'FacWarCorpJoinRequestMsg' => [
        'label'   => 'notifications::alerts.factional_warfare_corporation_join_request_message',
        'handlers' => [],
    ],
    'FacWarCorpJoinWithdrawMsg' => [
        'label'   => 'notifications::alerts.factional_warfare_corporation_withdraw_message',
        'handlers' => [],
    ],
    'FacWarCorpLeaveRequestMsg' => [
        'label'   => 'notifications::alerts.factional_warfare_corporation_leave_request_message',
        'handlers' => [],
    ],
    'FacWarCorpLeaveWithdrawMsg' => [
        'label'   => 'notifications::alerts.factional_warfare_corporation_leave_withdraw_message',
        'handlers' => [],
    ],
    'FacWarLPDisqualifiedEvent' => [
        'label'   => 'notifications::alerts.factional_warfare_loyalty_point_disqualified_event',
        'handlers' => [],
    ],
    'FacWarLPDisqualifiedKill' => [
        'label'   => 'notifications::alerts.factional_warfare_loyalty_point_disqualified_kill',
        'handlers' => [],
    ],
    'FacWarLPPayoutEvent' => [
        'label'   => 'notifications::alerts.factional_warfare_loyalty_point_payout_event',
        'handlers' => [],
    ],
    'FacWarLPPayoutKill' => [
        'label'   => 'notifications::alerts.factional_warfare_loyalty_point_payout_kill',
        'handlers' => [],
    ],
    'GameTimeAdded' => [
        'label'   => 'notifications::alerts.game_time_added',
        'handlers' => [],
    ],
    'GameTimeReceived' => [
        'label'   => 'notifications::alerts.game_time_received',
        'handlers' => [],
    ],
    'GameTimeSent' => [
        'label'   => 'notifications::alerts.game_time_sent',
        'handlers' => [],
    ],
    'GiftReceived' => [
        'label'   => 'notifications::alerts.gift_received',
        'handlers' => [],
    ],
    'IHubDestroyedByBillFailure' => [
        'label'   => 'notifications::alerts.infrastructure_hub_destroyed_by_bill_failure',
        'handlers' => [],
    ],
    'IncursionCompletedMsg' => [
        'label'   => 'notifications::alerts.incursion_completed_message',
        'handlers' => [],
    ],
    'IndustryOperationFinished' => [
        'label'   => 'notifications::alerts.industry_operation_finished',
        'handlers' => [],
    ],
    'IndustryTeamAuctionLost' => [
        'label'   => 'notifications::alerts.industry_team_auction_lost',
        'handlers' => [],
    ],
    'IndustryTeamAuctionWon' => [
        'label'   => 'notifications::alerts.industry_team_auction_won',
        'handlers' => [],
    ],
    'InfrastructureHubBillAboutToExpire' => [
        'label'   => 'notifications::alerts.infrastructure_hub_bill_about_to_expire',
        'handlers' => [],
    ],
    'InsuranceExpirationMsg' => [
        'label'   => 'notifications::alerts.insurance_expiration_message',
        'handlers' => [],
    ],
    'InsuranceFirstShipMsg' => [
        'label'   => 'notifications::alerts.insurance_first_ship_message',
        'handlers' => [],
    ],
    'InsuranceInvalidatedMsg' => [
        'label'   => 'notifications::alerts.insurance_invalidated_message',
        'handlers' => [],
    ],
    'InsuranceIssuedMsg' => [
        'label'   => 'notifications::alerts.insurance_issued_message',
        'handlers' => [],
    ],
    'InsurancePayoutMsg' => [
        'label'   => 'notifications::alerts.insurance_payout_message',
        'handlers' => [],
    ],
    'InvasionSystemLogin' => [
        'label'   => 'notifications::alerts.invasion_system_login',
        'handlers' => [],
    ],
    'JumpCloneDeletedMsg1' => [
        'label'   => 'notifications::alerts.jump_clone_deleted_message_1',
        'handlers' => [],
    ],
    'JumpCloneDeletedMsg2' => [
        'label'   => 'notifications::alerts.jump_clone_deleted_message_2',
        'handlers' => [],
    ],
    'KillReportFinalBlow' => [
        'label'   => 'notifications::alerts.kill_report_final_blow',
        'handlers' => [],
    ],
    'KillReportVictim' => [
        'label'   => 'notifications::alerts.kill_report_victim',
        'handlers' => [],
    ],
    'KillRightAvailable' => [
        'label'   => 'notifications::alerts.kill_right_available',
        'handlers' => [],
    ],
    'KillRightAvailableOpen' => [
        'label'   => 'notifications::alerts.kill_right_available_open',
        'handlers' => [],
    ],
    'KillRightEarned' => [
        'label'   => 'notifications::alerts.kill_right_earned',
        'handlers' => [],
    ],
    'KillRightUnavailable' => [
        'label'   => 'notifications::alerts.kill_right_unavailable',
        'handlers' => [],
    ],
    'KillRightUnavailableOpen' => [
        'label'   => 'notifications::alerts.kill_right_unavailable_open',
        'handlers' => [],
    ],
    'KillRightUsed' => [
        'label'   => 'notifications::alerts.kill_right_used',
        'handlers' => [],
    ],
    'LocateCharMsg' => [
        'label'   => 'notifications::alerts.located_character_message',
        'handlers' => [],
    ],
    'MadeWarMutual' => [
        'label'   => 'notifications::alerts.made_war_mutual',
        'handlers' => [],
    ],
    'MercOfferRetractedMsg' => [
        'label'   => 'notifications::alerts.mercenary_offer_retracted_message',
        'handlers' => [],
    ],
    'MercOfferedNegotiationMsg' => [
        'label'   => 'notifications::alerts.mercenary_offer_negotiation_message',
        'handlers' => [],
    ],
    'MissionOfferExpirationMsg' => [
        'label'   => 'notifications::alerts.mission_offer_expiration_message',
        'handlers' => [],
    ],
    'MissionTimeoutMsg' => [
        'label'   => 'notifications::alerts.mission_timeout_message',
        'handlers' => [],
    ],
    'MoonminingAutomaticFracture' => [
        'label'   => 'notifications::alerts.moon_mining_automatic_fracture',
        'handlers' => [],
    ],
    'MoonminingExtractionCancelled' => [
        'label'   => 'notifications::alerts.moon_mining_extraction_cancelled',
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
        'label'   => 'notifications::alerts.moon_mining_laser_fired',
        'handlers' => [],
    ],
    'MutualWarExpired' => [
        'label'   => 'notifications::alerts.mutual_war_expired',
        'handlers' => [],
    ],
    'MutualWarInviteAccepted' => [
        'label'   => 'notifications::alerts.mutual_war_invite_accepted',
        'handlers' => [],
    ],
    'MutualWarInviteRejected' => [
        'label'   => 'notifications::alerts.mutual_war_invite_rejected',
        'handlers' => [],
    ],
    'MutualWarInviteSent' => [
        'label'   => 'notifications::alerts.mutual_war_invite_sent',
        'handlers' => [],
    ],
    'NPCStandingsGained' => [
        'label'   => 'notifications::alerts.npc_standings_gained',
        'handlers' => [],
    ],
    'NPCStandingsLost' => [
        'label'   => 'notifications::alerts.npc_standing_lost',
        'handlers' => [],
    ],
    'OfferToAllyRetracted' => [
        'label'   => 'notifications::alerts.offer_to_ally_retracted',
        'handlers' => [],
    ],
    'OfferedSurrender' => [
        'label'   => 'notifications::alerts.offered_surrender',
        'handlers' => [],
    ],
    'OfferedToAlly' => [
        'label'   => 'notifications::alerts.offered_to_ally',
        'handlers' => [],
    ],
    'OldLscMessages' => [
        'label'   => 'notifications::alerts.old_lsc_messages',
        'handlers' => [],
    ],
    'OperationFinished' => [
        'label'   => 'notifications::alerts.operation_finished',
        'handlers' => [],
    ],
    'OrbitalAttacked' => [
        'label'   => 'notifications::alerts.orbital_attacked',
        'handlers' => [],
    ],
    'OrbitalReinforced' => [
        'label'   => 'notifications::alerts.orbital_reinforced',
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
        'label'   => 'notifications::alerts.reimbursement_message',
        'handlers' => [],
    ],
    'ResearchMissionAvailableMsg' => [
        'label'   => 'notifications::alerts.research_mission_available',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Characters\Slack\ResearchMissionAvailableMsg::class,
        ],
    ],
    'RetractsWar' => [
        'label'   => 'notifications::alerts.retracts_war',
        'handlers' => [],
    ],
    'SeasonalChallengeCompleted' => [
        'label'   => 'notifications::alerts.seasonal_challenge_completed',
        'handlers' => [],
    ],
    'SovAllClaimAquiredMsg' => [
        'label'   => 'notifications::alerts.sovereignty_alliance_claim_acquired_message',
        'handlers' => [],
    ],
    'SovAllClaimLostMsg' => [
        'label'   => 'notifications::alerts.sovereignty_alliance_claim_lost_message',
        'handlers' => [],
    ],
    'SovCommandNodeEventStarted' => [
        'label'   => 'notifications::alerts.sovereignty_command_node_event_started',
        'handlers' => [],
    ],
    'SovCorpBillLateMsg' => [
        'label'   => 'notifications::alerts.sovereignty_corporation_bill_late_message',
        'handlers' => [],
    ],
    'SovCorpClaimFailMsg' => [
        'label'   => 'notifications::alerts.sovereignty_corporation_claim_fail_message',
        'handlers' => [],
    ],
    'SovDisruptorMsg' => [
        'label'   => 'notifications::alerts.sovereignty_disruptor_message',
        'handlers' => [],
    ],
    'SovStationEnteredFreeport' => [
        'label'   => 'notifications::alerts.sovereignty_station_entered_freeport',
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
        'label'   => 'notifications::alerts.sovereignty_structure_self_destruct_cancel',
        'handlers' => [],
    ],
    'SovStructureSelfDestructFinished' => [
        'label'   => 'notifications::alerts.sovereignty_structure_self_destruct_finished',
        'handlers' => [],
    ],
    'SovStructureSelfDestructRequested' => [
        'label'   => 'notifications::alerts.sovereignty_structure_self_destruct_requested',
        'handlers' => [],
    ],
    'SovereigntyIHDamageMsg' => [
        'label'   => 'notifications::alerts.sovereignty_infrastructure_hub_damage_message',
        'handlers' => [],
    ],
    'SovereigntySBUDamageMsg' => [
        'label'   => 'notifications::alerts.sovereignty_blockade_unit_damage_message',
        'handlers' => [],
    ],
    'SovereigntyTCUDamageMsg' => [
        'label'   => 'notifications::alerts.sovereignty_territorial_claim_unit_damage_message',
        'handlers' => [],
    ],
    'StationAggressionMsg1' => [
        'label'   => 'notifications::alerts.station_aggression_message_1',
        'handlers' => [],
    ],
    'StationAggressionMsg2' => [
        'label'   => 'notifications::alerts.station_aggression_message_2',
        'handlers' => [],
    ],
    'StationConquerMsg' => [
        'label'   => 'notifications::alerts.station_conquer_message',
        'handlers' => [],
    ],
    'StationServiceDisabled' => [
        'label'   => 'notifications::alerts.station_service_disabled',
        'handlers' => [],
    ],
    'StationServiceEnabled' => [
        'label'   => 'notifications::alerts.station_service_enabled',
        'handlers' => [],
    ],
    'StationStateChangeMsg' => [
        'label'   => 'notifications::alerts.station_state_change_message',
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
        'label'   => 'notifications::alerts.structure_courier_contract_changed',
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
        'label'   => 'notifications::alerts.structure_items_delivered',
        'handlers' => [],
    ],
    'StructureItemsMovedToSafety' => [
        'label'   => 'notifications::alerts.structure_items_moved_to_safety',
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
        'label'   => 'notifications::alerts.structure_online',
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
        'label'   => 'notifications::alerts.structures_jobs_cancelled',
        'handlers' => [],
    ],
    'StructuresJobsPaused' => [
        'label'   => 'notifications::alerts.structures_jobs_paused',
        'handlers' => [],
    ],
    'StructuresReinforcementChanged' => [
        'label'   => 'notifications::alerts.structures_reinforcement_changed',
        'handlers' => [],
    ],
    'TowerAlertMsg' => [
        'label'   => 'notifications::alerts.tower_alert_message',
        'handlers' => [],
    ],
    'TowerResourceAlertMsg' => [
        'label'   => 'notifications::alerts.tower_resource_alert_message',
        'handlers' => [],
    ],
    'TransactionReversalMsg' => [
        'label'   => 'notifications::alerts.transaction_reversal_message',
        'handlers' => [],
    ],
    'TutorialMsg' => [
        'label'   => 'notifications::alerts.tutorial_message',
        'handlers' => [],
    ],
    'WarAdopted ' => [
        'label'   => 'notifications::alerts.war_adopted',
        'handlers' => [],
    ],
    'WarAllyInherited' => [
        'label'   => 'notifications::alerts.war_ally_inherited',
        'handlers' => [],
    ],
    'WarAllyOfferDeclinedMsg' => [
        'label'   => 'notifications::alerts.war_ally_offer_declined_message',
        'handlers' => [],
    ],
    'WarConcordInvalidates' => [
        'label'   => 'notifications::alerts.war_concord_invalidates',
        'handlers' => [],
    ],
    'WarDeclared' => [
        'label'   => 'notifications::alerts.war_declared',
        'handlers' => [],
    ],
    'WarHQRemovedFromSpace' => [
        'label'   => 'notifications::alerts.war_headquarter_removed_from_space',
        'handlers' => [],
    ],
    'WarInherited' => [
        'label'   => 'notifications::alerts.war_inherited',
        'handlers' => [],
    ],
    'WarInvalid' => [
        'label'   => 'notifications::alerts.war_invalid',
        'handlers' => [],
    ],
    'WarRetracted' => [
        'label'   => 'notifications::alerts.war_retracted',
        'handlers' => [],
    ],
    'WarRetractedByConcord' => [
        'label'   => 'notifications::alerts.war_retraced_by_concord',
        'handlers' => [],
    ],
    'WarSurrenderDeclinedMsg' => [
        'label'   => 'notifications::alerts.war_surrender_declined_message',
        'handlers' => [],
    ],
    'WarSurrenderOfferMsg' => [
        'label'   => 'notifications::alerts.war_surrender_offer_message',
        'handlers' => [],
    ],
    'inactive_member' => [
        'label' => 'notifications::alerts.war_inactive_member',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Corporations\Slack\InActiveCorpMember::class,
        ],
    ],
];
