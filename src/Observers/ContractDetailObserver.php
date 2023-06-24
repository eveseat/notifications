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

namespace Seat\Notifications\Observers;

use Seat\Eveapi\Models\Contracts\ContractDetail;
use Seat\Notifications\Models\NotificationGroup;
use Seat\Notifications\Traits\NotificationDispatchTool;

/**
 * Class CharacterDetailObserver.
 *
 * @package Seat\Notifications\Observers
 */
class ContractDetailObserver
{
    use NotificationDispatchTool;

    /**
     * @param  ContractDetail  $contract
     */
    public function created(ContractDetail $contract)
    {
        // if the contract is old but just got loaded, don't notify
        if(
            $contract->date_expired && carbon($contract->date_expired) < now()->subHours(1)
            || $contract->status === 'finished'
        ) return;

        $this->dispatch($contract);
    }

    public function saved(ContractDetail $contract)
    {
        $this->dispatch($contract);
    }

    /**
     * Queue notification based on User Creation.
     *
     * @param  ContractDetail  $contract
     */
    private function dispatch(ContractDetail $contract)
    {
        //if nothing changed, don't notify
        if(! $contract->isDirty()) return;

        $groups = NotificationGroup::with('alerts', 'affiliations')
            ->whereHas('alerts', function ($query) {
                $query->where('alert', 'contract_created');
            })->whereHas('affiliations', function ($query) use ($contract) {
                $query->where('affiliation_id', $contract->issuer_id);
                $query->orWhere('affiliation_id', $contract->assingee_id);
                $query->orWhere('affiliation_id', $contract->acceptor_id);
                $query->orWhere('affiliation_id', $contract->issuer_corporation_id);
            })->get();

        $this->dispatchNotifications('contract_created', $groups, function ($notificationClass) use ($contract) {
            return new $notificationClass($contract);
        });
    }
}
