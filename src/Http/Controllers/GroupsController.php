<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018  Leon Jacobs
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

namespace Seat\Notifications\Http\Controllers;

use Seat\Notifications\Http\Validation\Group;
use Seat\Notifications\Http\Validation\GroupAffiliation;
use Seat\Notifications\Http\Validation\GroupAlert;
use Seat\Notifications\Http\Validation\GroupIntegration;
use Seat\Notifications\Models\GroupAffiliation as GroupAffiliationModel;
use Seat\Notifications\Models\GroupAlert as GroupAlertModel;
use Seat\Notifications\Models\Integration;
use Seat\Notifications\Models\NotificationGroup;
use Seat\Services\Repositories\Character\Character;
use Seat\Services\Repositories\Corporation\Corporation;
use Seat\Web\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;

/**
 * Class GroupsController.
 * @package Seat\Notifications\Http\Controllers
 */
class GroupsController extends Controller
{
    use Corporation, Character;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getGroups()
    {

        return view('notifications::groups.list');
    }

    /**
     * @return mixed
     */
    public function getGroupsData()
    {

        return Datatables::of(NotificationGroup::all())
            ->addColumn('alerts', function ($row) {

                return count($row->alerts);
            })
            ->addColumn('integrations', function ($row) {

                return count($row->integrations);
            })
            ->addColumn('affiliations', function ($row) {

                return count($row->affiliations);
            })
            ->addColumn('actions', function ($row) {

                return view('notifications::groups.partials.actions', compact('row'))
                    ->render();
            })
            ->make(true);
    }

    /**
     * @param \Seat\Notifications\Http\Validation\Group $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postNewGroup(Group $request)
    {

        NotificationGroup::create([
            'name' => $request->input('name'),
            'type' => $request->input('type'),
        ]);

        return redirect()->back()
            ->with('success', 'Group created!');
    }

    /**
     * @param int $notification_group_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEditGroup(int $notification_group_id)
    {

        $group = NotificationGroup::with('integrations', 'alerts', 'affiliations')
            ->where('id', $notification_group_id)
            ->first();

        $integrations = Integration::all();

        $all_characters = $this->getAllCharacters();
        $all_corporations = $this->getAllCorporations();

        return view('notifications::groups.edit',
            compact('group', 'integrations', 'all_characters', 'all_corporations'));

    }

    /**
     * @param int $group_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDeleteGroup(int $group_id)
    {

        NotificationGroup::findOrFail($group_id)
            ->delete();

        return redirect()->back()
            ->with('success', 'Group removed!');
    }

    /**
     * @param \Seat\Notifications\Http\Validation\GroupIntegration $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAddIntegrations(GroupIntegration $request)
    {

        $group = NotificationGroup::findOrFail($request->input('id'));

        // Attach the integrations to the group.
        foreach ($request->integrations as $integration_id) {

            $integration = Integration::find($integration_id);

            // Make sure only one integration type is added.
            if ($group->integrations->contains('type', $integration->type))
                return redirect()->back()
                    ->with('warning', 'A ' . $integration->type .
                        ' integration already exists. Please choose another type.');

            // Add the integration
            if (! $group->integrations->contains($integration_id))
                $group->integrations()
                    ->attach(Integration::findOrFail($integration_id));
        }

        return redirect()->back()
            ->with('success', 'Integrations Added!');
    }

    /**
     * @param int $group_id
     * @param int $integration_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDeleteIntegration(int $group_id, int $integration_id)
    {

        NotificationGroup::findOrFail($group_id)
            ->integrations()->detach($integration_id);

        return redirect()->back()
            ->with('success', 'Removed integration!');

    }

    /**
     * @param \Seat\Notifications\Http\Validation\GroupAlert $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAddAlert(GroupAlert $request)
    {

        $group = NotificationGroup::findOrFail($request->input('id'));

        foreach ($request->alerts as $alert)
            if (! $group->alerts->contains('alert', $alert))
                $group->alerts()
                    ->save(new GroupAlertModel(['alert' => $alert]));

        return redirect()->back()
            ->with('success', 'Alerts Added!');

    }

    /**
     * @param int $group_id
     * @param int $alert_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDeleteAlert(int $group_id, int $alert_id)
    {

        NotificationGroup::findOrFail($group_id)
            ->alerts()->findOrFail($alert_id)
            ->delete();

        return redirect()->back()
            ->with('success', 'Alert removed!');
    }

    /**
     * @param \Seat\Notifications\Http\Validation\GroupAffiliation $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAddAffiliation(GroupAffiliation $request)
    {

        $group = NotificationGroup::findOrFail($request->input('id'));

        // Process the corporations
        if ($request->has('corporations'))
            foreach ($request->input('corporations') as $corp)
                if (! $group->affiliations->contains('affiliation_id', $corp))
                    $group->affiliations()->save(new GroupAffiliationModel([
                        'type'           => 'corp',
                        'affiliation_id' => $corp,
                    ]));

        // Process the characters
        if ($request->has('characters'))
            foreach ($request->input('characters') as $character)
                if (! $group->affiliations->contains('affiliation_id', $character))
                    $group->affiliations()->save(new GroupAffiliationModel([
                        'type'           => 'char',
                        'affiliation_id' => $character,
                    ]));

        return redirect()->back()
            ->with('success', 'Affiliations added!');

    }

    /**
     * @param int $group_id
     * @param int $affiliation_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDeleteAffiliation(int $group_id, int $affiliation_id)
    {

        NotificationGroup::findOrFail($group_id)
            ->affiliations()->findOrFail($affiliation_id)
            ->delete();

        return redirect()->back()
            ->with('success', 'Affiliation removed!');
    }
}
