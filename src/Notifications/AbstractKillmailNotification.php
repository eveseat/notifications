<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2021 Leon Jacobs
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

use AbstractNotification;

/**
 * Class AbstractKillmailNotification.
 *
 * @package Seat\Notifications\Notifications
 */
class AbstractKillmailNotification extends AbstractNotification
{

    /**
     * Get the Slack representation of the notification.
     *
     * @param $notifiable
     * @return bool
     */
    protected function shouldProcessKillmail($notifiable) {

        $killmailConfig = config('notifications.killmails');
        
        $allianceId = $killmailConfig['alliance_id'];
        $corporationId = $killmailConfig['corporation_id'];
        $processKills = $killmailConfig['process_kills'];
        $processLosses = $killmailConfig['process_losses'];

        $shouldProcess = true;

        if ($allianceId && $corporationId) {
            if ($this->killmail->victim->alliance_id === $allianceId && $this->killmail->victim->corporation_id === $corporationId) {
                $shouldProcess = $processLosses;
            } elseif (in_array($allianceId, $killmail->attackers->pluck('alliance_id')) || in_array($corporationId, $killmail->attackers->pluck('character_id'))) {
                $shouldProcess = $processKills;
            }
        } else if ($allianceId) {
            if ($this->killmail->victim->alliance_id === $allianceId) {
                $shouldProcess = $processLosses;
            }
            else if (in_array($allianceId, $killmail->attackers->pluck('alliance_id'))) {
                $shouldProcess = $processKills;
            }
        } else if ($corporationId) {
            if ($this->killmail->victim->corporation_id === $corporationId) {
                $shouldProcess = $processLosses;
            }
            else if (in_array($corporationId, $killmail->attackers->pluck('character_id'))) {
                $shouldProcess = $processKills;
            }
        }

        return $shouldProcess;
    }
}
