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

use Illuminate\Support\Facades\Notification;
use Seat\Eveapi\Models\Contracts\ContractDetail;
use Seat\Notifications\Models\NotificationGroup;

/**
 * Class CharacterDetailObserver.
 *
 * @package Seat\Notifications\Observers
 */
class ContractDetailObserver
{
    /**
     * @param  ContractDetail  $contract
     */
    public function created(ContractDetail $contract)
    {
        // if the contract is old but just got loaded, don't notify
        if(
            $contract->date_expired && carbon($contract->date_expired) < now()->subHours(1)
            || $contract->status == "finished"
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
     * @param  ContractDetail $contract
     */
    private function dispatch(ContractDetail $contract)
    {
        //if nothing changed, don't notify
        if(!$contract->isDirty()) return;

        // detect handlers setup for the current notification
        $handlers = config('notifications.alerts.character_contract_created.handlers', []);

        // retrieve routing candidates for the current notification
        $routes = $this->getRoutingCandidates($contract);

        // in case no routing candidates has been delivered, exit
        if ($routes->isEmpty())
            return;

        // attempt to enqueue a notification for each routing candidates
        $routes->each(function ($integration) use ($handlers, $contract) {
            if (array_key_exists($integration->channel, $handlers)) {

                // extract handler from the list
                $handler = $handlers[$integration->channel];

                // enqueue the notification
                Notification::route($integration->channel, $integration->route)
                    ->notify(new $handler($contract));
            }
        });
    }

    /**
     * Provide a unique list of notification channels (including driver and route).
     *
     * @return \Illuminate\Support\Collection
     */
    private function getRoutingCandidates(ContractDetail $detail)
    {
        $settings = NotificationGroup::with('alerts', 'affiliations')
            ->whereHas('alerts', function ($query) {
                $query->where('alert', "character_contract_created");
            })->whereHas('affiliations', function ($query) use ($detail) {
                $query->where('affiliation_id', $detail->issuer_id);
                $query->orWhere('affiliation_id', $detail->assingee_id);
                $query->orWhere('affiliation_id', $detail->acceptor_id);
                $query->orWhere('affiliation_id', $detail->issuer_corporation_id);
            })->get();

        $routes = $settings->map(function ($group) {
            return $group->integrations->map(function ($channel) {

                // extract the route value from settings field
                $settings = (array) $channel->settings;
                $key = array_key_first($settings);
                $route = $settings[$key];

                // build a composite object built with channel and route
                return (object) [
                    'channel' => $channel->type,
                    'route' => $route,
                ];
            });
        });

        return $routes->flatten()->unique(function ($integration) {
            return $integration->channel . $integration->route;
        });
    }
}
