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

namespace Seat\Notifications;

use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Corporation\CorporationMemberTracking;
use Seat\Eveapi\Models\Killmails\KillmailDetail;
use Seat\Notifications\Observers\CharacterNotificationObserver;
use Seat\Notifications\Observers\CorporationMemberTrackingObserver;
use Seat\Notifications\Observers\KillmailNotificationObserver;
use Seat\Notifications\Observers\UserObserver;
use Seat\Notifications\Observers\SquadMemberObserver;
use Seat\Notifications\Observers\SquadApplicationObserver;
use Seat\Services\AbstractSeatPlugin;
use Seat\Web\Models\Squads\SquadApplication;
use Seat\Web\Models\Squads\SquadMember;
use Seat\Web\Models\User;

/**
 * Class NotificationsServiceProvider.
 * @package Seat\Notifications
 */
class NotificationsServiceProvider extends AbstractSeatPlugin
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register alerts
        $this->add_alerts();

        $this->add_views();

        // Inform Laravel how to load migrations
        $this->add_migrations();

        $this->add_routes();

        $this->add_translations();

        // Add events listeners
        $this->add_events();
    }

    /**
     * Set the path and namespace for the vies.
     */
    public function add_views()
    {

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'notifications');
    }

    /**
     * Publish alerts configuration file - so user can tweak it.
     */
    private function add_alerts()
    {
        $this->publishes([
            __DIR__ . '/Config/notifications.alerts.php' => config_path('notifications.alerts.php'),
        ], ['config', 'seat']);
    }

    /**
     * Include the routes.
     */
    public function add_routes()
    {

        if (! $this->app->routesAreCached()) {
            include __DIR__ . '/Http/routes.php';
        }
    }

    /**
     * Include the translations and set the namespace.
     */
    public function add_translations()
    {

        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'notifications');
    }

    /**
     * Register custom events that may be fire for this package.
     */
    private function add_events()
    {
        CharacterNotification::observe(CharacterNotificationObserver::class);
        CorporationMemberTracking::observe(CorporationMemberTrackingObserver::class);
        KillmailDetail::observe(KillmailNotificationObserver::class);
        User::observe(UserObserver::class);
        SquadApplication::observe(SquadApplicationObserver::class);
        SquadMember::observe(SquadMemberObserver::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Add new permissions
        $this->registerPermissions(__DIR__ . '/Config/Permissions/notifications.php', 'notifications');

        // Include this packages menu items
        $this->mergeConfigFrom(
            __DIR__ . '/Config/package.sidebar.php', 'package.sidebar');
    }

    /**
     * Set the path for migrations which should
     * be migrated by laravel. More informations:
     * https://laravel.com/docs/5.5/packages#migrations.
     */
    private function add_migrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');
    }

    /**
     * Return the plugin public name as it should be displayed into settings.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'SeAT Notifications';
    }

    /**
     * Return the plugin repository address.
     *
     * @return string
     */
    public function getPackageRepositoryUrl(): string
    {
        return 'https://github.com/eveseat/notifications';
    }

    /**
     * Return the plugin technical name as published on package manager.
     *
     * @return string
     */
    public function getPackagistPackageName(): string
    {
        return 'notifications';
    }

    /**
     * Return the plugin vendor tag as published on package manager.
     *
     * @return string
     */
    public function getPackagistVendorName(): string
    {
        return 'eveseat';
    }
}
