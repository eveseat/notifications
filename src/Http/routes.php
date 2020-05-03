<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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

    Route::get('/', [
        'as'   => 'notifications.list',
        'uses' => 'NotificationController@getNotifications',
    ]);

    Route::group([
        'prefix'     => 'integrations',
        'middleware' => 'can:notifications.setup',
    ], function () {

        Route::get('/', [
            'as'   => 'notifications.integrations.list',
            'uses' => 'IntegrationsController@getIntegrations',
        ]);

        Route::get('/data', [
            'as'   => 'notifications.integrations.list.data',
            'uses' => 'IntegrationsController@getIntegrationsData',
        ]);

        Route::get('/delete/{integration_id}', [
            'as'   => 'notifications.integrations.delete',
            'uses' => 'IntegrationsController@getDeleteIntegration',
        ]);

        // New Integrations

        // Email
        Route::get('/new/email', [
            'as'   => 'notifications.integrations.new.email',
            'uses' => 'IntegrationsController@getNewEmail',
        ]);

        Route::post('/new/email', [
            'as'   => 'notifications.integrations.new.email.add',
            'uses' => 'IntegrationsController@postNewEmail',
        ]);

        // Slack
        Route::get('/new/slack', [
            'as'   => 'notifications.integrations.new.slack',
            'uses' => 'IntegrationsController@getNewSlack',
        ]);

        Route::post('/new/slack', [
            'as'   => 'notifications.integrations.new.slack.add',
            'uses' => 'IntegrationsController@postNewSlack',
        ]);

    });

    Route::group([
        'prefix'     => 'groups',
        'middleware' => 'can:notifications.setup',
    ], function () {

        Route::get('/', [
            'as'   => 'notifications.groups.list',
            'uses' => 'GroupsController@index',
        ]);

        Route::post('/new', [
            'as'   => 'notifications.groups.new.post',
            'uses' => 'GroupsController@store',
        ]);

        Route::get('/delete/{group_id}', [
            'as'   => 'notifications.groups.delete',
            'uses' => 'GroupsController@getDeleteGroup',
        ]);

        Route::get('/edit/{notification_group_id}', [
            'as'   => 'notifications.groups.edit',
            'uses' => 'GroupsController@getEditGroup',
        ]);

        Route::post('/edit/integration/add', [
            'as'   => 'notifications.groups.edit.integration.add',
            'uses' => 'GroupsController@postAddIntegrations',
        ]);

        Route::get('/edit/integration/delete/{group_id}/{integration_id}', [
            'as'   => 'notifications.groups.edit.integration.delete',
            'uses' => 'GroupsController@getDeleteIntegration',
        ]);

        Route::get('/ajax/alerts/', [
            'as'   => 'notifications.ajax.alerts',
            'uses' => 'GroupsController@getAjaxAlerts',
        ]);

        Route::post('/edit/alert/', [
            'as'   => 'notifications.groups.edit.alert.add_all',
            'uses' => 'GroupsController@postAddAllAlerts',
        ]);

        Route::post('/edit/alert/add', [
            'as'   => 'notifications.groups.edit.alert.add',
            'uses' => 'GroupsController@postAddAlert',
        ]);

        Route::get('/edit/alert/delete/{group_id}/{alert_id}', [
            'as'   => 'notifications.groups.edit.alert.delete',
            'uses' => 'GroupsController@getDeleteAlert',
        ]);

        Route::post('/edit/affiliations/add', [
            'as'   => 'notifications.groups.edit.affiliations.add',
            'uses' => 'GroupsController@postAddAffiliation',
        ]);

        Route::get('/edit/affiliation/delete/{group_id}/{affiliation_id}', [
            'as'   => 'notifications.groups.edit.affiliation.delete',
            'uses' => 'GroupsController@getDeleteAffiliation',
        ]);

    });

});
