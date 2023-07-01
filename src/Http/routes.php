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

Route::group([
    'namespace'  => 'Seat\Notifications\Http\Controllers',
    'prefix'     => 'notifications',
    'middleware' => ['web', 'auth'],
], function () {
    Route::group([
        'prefix'     => 'integrations',
        'middleware' => 'can:notifications.setup',
    ], function () {

        Route::get('/')
            ->name('seatcore::notifications.integrations.list')
            ->uses('IntegrationsController@getIntegrations');

        Route::get('/data')
            ->name('seatcore::notifications.integrations.list.data')
            ->uses('IntegrationsController@getIntegrationsData');

        Route::get('/delete/{integration_id}')
            ->name('seatcore::notifications.integrations.delete')
            ->uses('IntegrationsController@getDeleteIntegration');

        Route::get('/test/{integration_id}')
            ->name('seatcore::notifications.integrations.test')
            ->uses('IntegrationsController@getTestIntegration');

        // New Integrations

        // Discord
        Route::get('/new/discord')
            ->name('seatcore::notifications.integrations.new.discord')
            ->uses('IntegrationsController@getNewDiscord');

        Route::post('/new/discord')
            ->name('seatcore::notifications.integrations.new.discord.add')
            ->uses('IntegrationsController@postNewDiscord');

        // Email
        Route::get('/new/email')
            ->name('seatcore::notifications.integrations.new.email')
            ->uses('IntegrationsController@getNewEmail');

        Route::post('/new/email')
            ->name('seatcore::notifications.integrations.new.email.add')
            ->uses('IntegrationsController@postNewEmail');

        // Slack
        Route::get('/new/slack')
            ->name('seatcore::notifications.integrations.new.slack')
            ->uses('IntegrationsController@getNewSlack');

        Route::post('/new/slack')
            ->name('seatcore::notifications.integrations.new.slack.add')
            ->uses('IntegrationsController@postNewSlack');

    });

    Route::group([
        'prefix'     => 'groups',
        'middleware' => 'can:notifications.setup',
    ], function () {

        Route::get('/')
            ->name('seatcore::notifications.groups.list')
            ->uses('GroupsController@index');

        Route::post('/new')
            ->name('seatcore::notifications.groups.new.post')
            ->uses('GroupsController@store');

        Route::get('/delete/{group_id}')
            ->name('seatcore::notifications.groups.delete')
            ->uses('GroupsController@getDeleteGroup');

        Route::get('/edit/{notification_group_id}')
            ->name('seatcore::notifications.groups.edit')
            ->uses('GroupsController@getEditGroup');

        Route::post('/edit/integration/add')
            ->name('seatcore::notifications.groups.edit.integration.add')
            ->uses('GroupsController@postAddIntegrations');

        Route::get('/edit/integration/delete/{group_id}/{integration_id}')
            ->name('seatcore::notifications.groups.edit.integration.delete')
            ->uses('GroupsController@getDeleteIntegration');

        Route::post('/edit/mentions/add')
            ->name('seatcore::notifications.groups.edit.mention.add')
            ->uses('GroupsController@postAddGroupMention');

        Route::get('/edit/mentions/delete/{mention_id}')
            ->name('seatcore::notifications.groups.edit.mention.delete')
            ->uses('GroupsController@postDeleteGroupMention');

        Route::post('/edit/mentions/add/discord/role')
            ->name('seatcore::notifications.mentions.edit.discord.role.add')
            ->uses('MentionsController@postCreateDiscordAtRole');

        Route::post('/edit/mentions/add/discord/user')
            ->name('seatcore::notifications.mentions.edit.discord.user.add')
            ->uses('MentionsController@postCreateDiscordAtUser');

        Route::get('/ajax/alerts/')
            ->name('seatcore::notifications.ajax.alerts')
            ->uses('GroupsController@getAjaxAlerts');

        Route::post('/edit/alert/')
            ->name('seatcore::notifications.groups.edit.alert.add_all')
            ->uses('GroupsController@postAddAllAlerts');

        Route::post('/edit/alert/add')
            ->name('seatcore::notifications.groups.edit.alert.add')
            ->uses('GroupsController@postAddAlert');

        Route::get('/edit/alert/delete/{group_id}/{alert_id}')
            ->name('seatcore::notifications.groups.edit.alert.delete')
            ->uses('GroupsController@getDeleteAlert');

        Route::post('/edit/affiliations/add')
            ->name('seatcore::notifications.groups.edit.affiliations.add')
            ->uses('GroupsController@postAddAffiliation');

        Route::get('/edit/affiliation/delete/{group_id}/{affiliation_id}')
            ->name('seatcore::notifications.groups.edit.affiliation.delete')
            ->uses('GroupsController@getDeleteAffiliation');

    });
});
