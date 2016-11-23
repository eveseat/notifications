<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace Seat\Notifications\Http\Controllers;

use Seat\Notifications\Http\Validation\EmailIntegration;
use Seat\Notifications\Http\Validation\SlackIntegration;
use Seat\Notifications\Models\Integration;
use Seat\Web\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;

/**
 * Class IntegrationsController
 * @package Seat\Notifications\Http\Controllers
 */
class IntegrationsController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIntegrations()
    {

        return view('notifications::integrations.list');
    }

    /**
     * @return mixed
     */
    public function getIntegrationsData()
    {

        return Datatables::of(Integration::all())
            ->make(true);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getNewEmail()
    {

        return view('notifications::integrations.forms.email');
    }

    /**
     * @param \Seat\Notifications\Http\Validation\EmailIntegration $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postNewEmail(EmailIntegration $request)
    {

        Integration::create([
            'name'     => $request->input('name'),
            'settings' => ['email' => $request->input('email')],
            'type'     => 'mail'
        ]);

        return redirect()->route('notifications.integrations.list')
            ->with('success', 'Mail Integration Added!');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getNewSlack()
    {

        return view('notifications::integrations.forms.slack');
    }

    /**
     * @param \Seat\Notifications\Http\Validation\SlackIntegration $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postNewSlack(SlackIntegration $request)
    {

        Integration::create([
            'name'     => $request->input('name'),
            'settings' => ['url' => $request->input('url')],
            'type'     => 'slack'
        ]);

        return redirect()->route('notifications.integrations.list')
            ->with('success', 'Slack Integration Added!');
    }

}
