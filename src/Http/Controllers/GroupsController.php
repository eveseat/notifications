<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
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

use Illuminate\Http\Request;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Notifications\Http\DataTables\NotificationGroupDataTable;
use Seat\Notifications\Http\Validation\CreateGroupMention;
use Seat\Notifications\Http\Validation\Group;
use Seat\Notifications\Http\Validation\GroupAffiliation;
use Seat\Notifications\Http\Validation\GroupAlert;
use Seat\Notifications\Http\Validation\GroupAllAlert;
use Seat\Notifications\Http\Validation\GroupIntegration;
use Seat\Notifications\Models\GroupAffiliation as GroupAffiliationModel;
use Seat\Notifications\Models\GroupAlert as GroupAlertModel;
use Seat\Notifications\Models\GroupMention;
use Seat\Notifications\Models\Integration;
use Seat\Notifications\Models\NotificationGroup;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class GroupsController.
 *
 * @package Seat\Notifications\Http\Controllers
 */
class GroupsController extends Controller
{
    /**
     * @param  \Seat\Notifications\Http\DataTables\NotificationGroupDataTable  $data_table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(NotificationGroupDataTable $data_table)
    {

        return $data_table->render('notifications::groups.list');
    }

    /**
     * @param  \Seat\Notifications\Http\Validation\Group  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Group $request)
    {

        NotificationGroup::create([
            'name' => $request->input('name'),
        ]);

        return redirect()->back()
            ->with('success', 'Group created!');
    }

    /**
     * @param  int  $notification_group_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEditGroup(int $notification_group_id)
    {

        $group = NotificationGroup::with('integrations', 'alerts', 'affiliations')
            ->where('id', $notification_group_id)
            ->first();

        $integrations = Integration::all();

        $all_characters = CharacterInfo::all();
        $all_corporations = CorporationInfo::all();

        return view('notifications::groups.edit',
            compact('group', 'integrations', 'all_characters', 'all_corporations'));

    }

    /**
     * @param  int  $group_id
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
     * @param  \Seat\Notifications\Http\Validation\GroupIntegration  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAddIntegrations(GroupIntegration $request)
    {

        $group = NotificationGroup::findOrFail($request->input('id'));

        // Attach the integrations to the group.
        foreach ($request->integrations as $integration_id) {
            // Add the integration
            if (! $group->integrations->contains($integration_id))
                $group->integrations()
                    ->attach(Integration::findOrFail($integration_id));
        }

        return redirect()->back()
            ->with('success', 'Integrations Added!');
    }

    /**
     * @param  int  $group_id
     * @param  int  $integration_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDeleteIntegration(int $group_id, int $integration_id)
    {

        NotificationGroup::findOrFail($group_id)
            ->integrations()->detach($integration_id);

        return redirect()->back()
            ->with('success', 'Removed integration!');

    }

    public function postAddGroupMention(CreateGroupMention $request) {
        $mention_type = config('notifications.mentions')[$request->mention_type];

        // call the controller for creation. It can either show a page to enter more details or directly create the mention.
        return app()->call($mention_type['creation_controller_method']);
    }

    public function postDeleteGroupMention($mention_id) {
        GroupMention::destroy($mention_id);

        return redirect()->back()
            ->with('success', 'Removed mention!');
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAjaxAlerts(Request $request)
    {
        $keyword = strtolower($request->query('q', ''));
        $alerts = collect(config('notifications.alerts', []));

        // remove all hidden groups
        $alerts = $alerts->filter(function ($alert) {
            $is_visible = $alert['visible'] ?? true;

            return $is_visible;
        });

        if (! empty($keyword)) {
            $alerts = $alerts->filter(function ($alert) use ($keyword) {
                return strpos(strtolower(trans($alert['label'])), $keyword) !== false;
            });
        }

        return response()->json($alerts->map(function ($alert, $key) {
                return [
                    'id' => $key,
                    'label' => trans($alert['label']),
                    'channels' => array_keys($alert['handlers']),
                ];
            })->values()->toArray());
    }

    /**
     * @param  \Seat\Notifications\Http\Validation\GroupAllAlert  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAddAllAlerts(GroupAllAlert $request)
    {
        $group = NotificationGroup::findOrFail($request->input('id'));

        $alerts = array_keys(config('notifications.alerts', []));

        foreach ($alerts as $alert) {
            if (! $group->alerts->contains('alert', $alert))
                $group->alerts()->save(new GroupAlertModel(['alert' => $alert]));
        }

        return redirect()->back()
            ->with('success', 'All alerts has been added!');
    }

    /**
     * @param  \Seat\Notifications\Http\Validation\GroupAlert  $request
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
     * @param  int  $group_id
     * @param  int  $alert_id
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
     * @param  \Seat\Notifications\Http\Validation\GroupAffiliation  $request
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
                        'type' => 'corp',
                        'affiliation_id' => $corp,
                    ]));

        // Process the characters
        if ($request->has('characters'))
            foreach ($request->input('characters') as $character)
                if (! $group->affiliations->contains('affiliation_id', $character))
                    $group->affiliations()->save(new GroupAffiliationModel([
                        'type' => 'char',
                        'affiliation_id' => $character,
                    ]));

        return redirect()->back()
            ->with('success', 'Affiliations added!');

    }

    /**
     * @param  int  $group_id
     * @param  int  $affiliation_id
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
