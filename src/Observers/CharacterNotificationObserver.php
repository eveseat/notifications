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

namespace Seat\Notifications\Observers;

use Error;
use Illuminate\Notifications\AnonymousNotifiable;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Notifications\Models\NotificationGroup;
use Seat\Notifications\Notifications\Structures\OwnershipTransferred;
use Seat\Notifications\Notifications\Structures\StructureFuelAlert;

/**
 * Class CharacterNotificationObserver
 *
 * @package Seat\Notifications\Observers
 */
class CharacterNotificationObserver
{
    /**
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    public function created(CharacterNotification $notification)
    {
        $this->dispatch($notification);
    }

    /**
     * Queue notification based on notification kind
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function dispatch(CharacterNotification $notification)
    {
        if (! method_exists($this, 'handler' . $notification->type)) {
            logger()->debug('Unsupported notification type.', [
                'type' => $notification->type,
                'sender_type' => $notification->sender_type,
                'notification' => $notification->text,
            ]);

            return;
        }

        // attempt to convert the notification to the related handler
        $handler = $this->{'handler' . $notification->type}($notification);

        // ask Laravel to enqueue the notification
        $manager = new AnonymousNotifiable();

        // retrieve routing candidates for the current notification
        $routes = $this->getRoutingCandidates($notification);

        // in case no routing candidates has been delivered, exit
        if ($routes->isEmpty())
            return;

        // append each routing candidate to the notification process
        $routes->each(function ($integration) use ($manager) {
            $manager->route($integration->channel, $integration->route);
        });

        // enqueue the notification
        $manager->notify($handler);
    }

    /**
     * Provide a unique list of notification channels (including driver and route)
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     * @return \Illuminate\Support\Collection
     */
    private function getRoutingCandidates(CharacterNotification $notification)
    {
        // collect notifications settings related to that notification
        $settings = NotificationGroup::with('alerts', 'affiliations')
            ->whereHas('alerts', function ($query) use ($notification) {
                $query->where('alert', $notification->type);
            })->whereHas('affiliations', function ($query) use ($notification) {
                $query->where('affiliation_id', $notification->character_id);
                $query->orWhere('affiliation_id', $notification->recipient->affiliation->corporation_id);
            })->get();

        // loop over each group candidate and collect available integrations
        $routes = $settings->map(function ($group) {
            return $group->integrations->map(function ($channel) {

                // extract the route value from settings field
                $setting = (array) $channel->settings;
                $key = array_key_first($setting);
                $route = $setting[$key];

                // build a composite object build with channel and route
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

    /**
     * Enqueue a job to notify a war declaration.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerAllWarDeclaredMsg(CharacterNotification $notification)
    {

    }

    /***
     * Enqueue a job to notify a war termination.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerAllWarInvalidatedMsg(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify a corporation application.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerCorpAppNewMsg(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify an entosis start.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerEntosisCaptureStarted(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify a kill.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerKillReportFinalBlow(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify a killed character.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerKillReportVictim(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify an automatic moon chunk fracture.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerMoonminingAutomaticFracture(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify an automatic moon extraction cancellation.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerMoonminingExtractionCancelled(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify an available moon chunk.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerMoonminingExtractionFinished(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify an new moon extraction.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerMoonminingExtractionStarted(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify a structure transfer.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     * @return \Seat\Notifications\Notifications\Structures\OwnershipTransferred
     */
    private function handlerOwnershipTransferred(CharacterNotification $notification)
    {
        return new OwnershipTransferred($notification);
    }

    /**
     * Enqueue a job to notify a sovereignty has been gained by an alliance.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerSovAllClaimAquiredMsg(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify a sovereignty has been loose by an alliance.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerSovAllClaimLostMsg(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify a sovereignty structure has been destroyed.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerSovStructureDestroyed(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify a sovereignty structure has been reinforced.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerSovStructureReinforced(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify a structure service has been turned off.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerStationServiceDisabled(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify a structure service has been turned on.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerStationServiceEnabled(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify a structure is currently anchored.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerStructureAnchoring(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify a structure has been destroyed.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerStructureDestroyed(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify a structure is low in fuel.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     * @return \Seat\Notifications\Notifications\Structures\StructureFuelAlert
     */
    private function handlerStructureFuelAlert(CharacterNotification $notification)
    {
        return new StructureFuelAlert($notification);
    }

    /**
     * Enqueue a job to notify a structure has loose its armor.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerStructureLostArmor(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify a structure has loose its shield.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerStructureLostShields(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify a structure has been put online.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerStructureOnline(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify a structure has loose all its services.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerStructureServicesOffline(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify jobs run by a structure has been paused.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerStructuresJobsPaused(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify a structure has been recovered.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerStructuresReinforcementChanged(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify a structure is unanchored.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerStructureUnanchoring(CharacterNotification $notification)
    {

    }

    /**
     * Enqueue a job to notify a structure is attacked.
     *
     * @param \Seat\Eveapi\Models\Character\CharacterNotification $notification
     */
    private function handlerStructureUnderAttack(CharacterNotification $notification)
    {

    }
}
