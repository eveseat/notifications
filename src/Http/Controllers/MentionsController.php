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

use Seat\Notifications\Http\Validation\CreateDiscordRoleGroupMention;
use Seat\Notifications\Http\Validation\CreateDiscordUserGroupMention;
use Seat\Notifications\Http\Validation\CreateGroupMention;
use Seat\Notifications\Models\GroupMention;
use Seat\Notifications\Models\NotificationGroup;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class IntegrationsController.
 *
 * @package Seat\Notifications\Http\Controllers
 */
class MentionsController extends Controller
{
    public function createDiscordAtEveryone(CreateGroupMention $request) {
        $group = NotificationGroup::find($request->id);

        $mention = new GroupMention();
        $mention->type = 'discord_everyone';
        $mention->data = [];

        $group->mentions()->save($mention);

        return redirect()
            ->route('seatcore::notifications.groups.edit', ['notification_group_id' => $request->id])
            ->with('success', trans('notifications::notifications.successfully_created_mention'));
    }

    public function createDiscordAtHere(CreateGroupMention $request) {
        $group = NotificationGroup::find($request->id);

        $mention = new GroupMention();
        $mention->type = 'discord_here';
        $mention->data = [];

        $group->mentions()->save($mention);

        return redirect()
            ->route('seatcore::notifications.groups.edit', ['notification_group_id' => $request->id])
            ->with('success', trans('notifications::notifications.successfully_created_mention'));
    }

    public function createDiscordAtRole(CreateGroupMention $request) {
        return view('notifications::mentions.discord.create_role', ['group_id' => $request->id]);
    }

    public function postCreateDiscordAtRole(CreateDiscordRoleGroupMention $request) {
        $group = NotificationGroup::find($request->group_id);

        $mention = new GroupMention();
        $mention->type = 'discord_role';
        $mention->data = ['role' => (int) $request->role_id];

        $group->mentions()->save($mention);

        return redirect()
            ->route('seatcore::notifications.groups.edit', ['notification_group_id' => $request->group_id])
            ->with('success', trans('notifications::notifications.successfully_created_mention'));
    }

    public function createDiscordAtUser(CreateGroupMention $request) {
        return view('notifications::mentions.discord.create_user', ['group_id' => $request->id]);
    }

    public function postCreateDiscordAtUser(CreateDiscordUserGroupMention $request) {
        $group = NotificationGroup::find($request->group_id);

        $mention = new GroupMention();
        $mention->type = 'discord_user';
        $mention->data = ['user' => (int) $request->user_id];

        $group->mentions()->save($mention);

        return redirect()
            ->route('seatcore::notifications.groups.edit', ['notification_group_id' => $request->group_id])
            ->with('success', trans('notifications::notifications.successfully_created_mention'));
    }
}
