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

return [

    'group' => '通知グループ',
    'new_group' => '新たな通知グループ',
    'edit_group' => 'グループの編集',
    'group_name' => 'グループ名',
    'group_type' => 'グループタイプ',

    'add' => '追加',
    'name' => '名前',
    'type' => 'タイプ',
    'alert' => 'アラート',
    'integration' => '外部サービス連携 | 一覧',
    'affiliation' => '加入者 | 一覧',
    'add_all_alerts' => 'すべてのアラートを追加',

    'no_affiliation_notice' => 'このグループに紐づけられている加入者は ' .
        'まだいません. この設定ではマッチしたアラートによる全ての通知が ' .
        'フィルターなしで送信されます。',

    'new_integration' => '新規外部サービス連携',
    'new_integration_message' => '新規外部サービス連携',
    'new_discord' => 'New Discord Integration',
    'new_email' => '新たなE-メールの統合',
    'new_slack' => '新たなslackの統合',
    'configured_integrations' => '外部サービスの連携設定',
    'settings' => '設定',

    'setup_label' => '通知の設定',
    'setup_description' => 'ユーザーが通知チャンネルとサブスクリプションを定義できるようにします。',

    'test_integration' => 'Test',

    'mention' => 'Mention|Mentions',
    'data' => 'Data',
    'actions' => 'Actions',
    'successfully_created_mention' => 'Successfully created a new mention!',
    'create_mention' => 'Create Mention',
    'create_discord_role_mention' => 'New Discord Role Mention',
    'discord_role_id' => 'Discord Role ID',
    'discord_role_id_help' => 'Enter the ID of the discord role to ping. Make sure to enable discord\'s developer mode in the \'Advanced\' section of your settings. Afterwards, open the server settings, open the \'Roles\' section, click the role and select \'Copy Role ID\'.',
    'create_discord_user_mention' => 'New Discord User Mention',
    'discord_user_id' => 'Discord User ID',
    'discord_user_id_help' => 'Enter the ID of the discord user to ping. Make sure to enable discord\'s developer mode in the \'Advanced\' section of your settings. Afterwards, click on the avatar in the member list and select \'Copy User ID\'.',
];
