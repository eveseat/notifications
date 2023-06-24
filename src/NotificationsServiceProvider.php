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

namespace Seat\Notifications;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Contracts\ContractDetail;
use Seat\Eveapi\Models\Corporation\CorporationMemberTracking;
use Seat\Eveapi\Models\Killmails\KillmailDetail;
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Notifications\AbstractMailNotification;
use Seat\Notifications\Notifications\AbstractSlackNotification;
use Seat\Notifications\Observers\CharacterNotificationObserver;
use Seat\Notifications\Observers\ContractDetailObserver;
use Seat\Notifications\Observers\CorporationMemberTrackingObserver;
use Seat\Notifications\Observers\KillmailNotificationObserver;
use Seat\Notifications\Observers\SquadApplicationObserver;
use Seat\Notifications\Observers\SquadMemberObserver;
use Seat\Notifications\Observers\UserObserver;
use Seat\Services\AbstractSeatPlugin;
use Seat\Services\Settings\Profile;
use Seat\Web\Models\Squads\SquadApplication;
use Seat\Web\Models\Squads\SquadMember;
use Seat\Web\Models\User;

/**
 * Class NotificationsServiceProvider.
 *
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
        // Register settings
        $this->register_settings();

        // Register alerts
        $this->add_alerts();

        $this->add_views();

        // Inform Laravel how to load migrations
        $this->add_migrations();

        $this->add_routes();

        $this->add_translations();

        // Add events listeners
        $this->add_events();

        // rate limiting for slack/discord
        $this->add_rate_limiters();
    }

    /**
     * Set the path and namespace for the vies.
     */
    public function add_views()
    {

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'notifications');
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
        ContractDetail::observe(ContractDetailObserver::class);
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
     * Register default settings value for user profile.
     */
    private function register_settings()
    {
        // Notifications
        Profile::define('email_notifications', 'no');
        Profile::define('email_address', '');
    }

    private function add_rate_limiters() {
        // https://api.slack.com/docs/rate-limits
        RateLimiter::for(AbstractSlackNotification::RATE_LIMIT_KEY, function (object $job) {
            return Limit::perMinute(AbstractSlackNotification::RATE_LIMIT);
        });

        // just make usre we don't spam the mail server
        RateLimiter::for(AbstractMailNotification::RATE_LIMIT_KEY, function (object $job) {
            return Limit::perMinute(AbstractMailNotification::RATE_LIMIT);
        });

        // https://discord.com/developers/docs/topics/rate-limits#global-rate-limit
        RateLimiter::for(AbstractDiscordNotification::RATE_LIMIT_KEY, function (object $job) {
            return Limit::perMinute(AbstractDiscordNotification::RATE_LIMIT);
        });
    }
}
