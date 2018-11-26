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

namespace Seat\Notifications;

use Illuminate\Support\ServiceProvider;
use Seat\Notifications\Commands\AlertsRun;

/**
 * Class NotificationsServiceProvider.
 * @package Seat\Notifications
 */
class NotificationsServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $available_commands = [
        AlertsRun::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->commands($this->available_commands);

        $this->add_views();

        // Inform Laravel how to load migrations
        $this->add_migrations();

        $this->add_routes();

        $this->add_translations();
    }

    /**
     * Set the path and namespace for the vies.
     */
    public function add_views()
    {

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'notifications');
    }

    /**
     * Set the path for migrations which should
     * be migrated by laravel. More informations:
     * https://laravel.com/docs/5.5/packages#migrations.
     */
    public function add_migrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');
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

        $this->loadTranslationsFrom(__DIR__ . '/lang', 'notifications');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        // Package specific configuration
        $this->mergeConfigFrom(
            __DIR__ . '/Config/notifications.config.php', 'notifications.config');

        // Registered alerts
        $this->mergeConfigFrom(
            __DIR__ . '/Config/notifications.alerts.php', 'notifications.alerts');

        // Add new permissions
        $this->mergeConfigFrom(
            __DIR__ . '/Config/notifications.permissions.php', 'web.permissions');

        // Include this packages menu items
        $this->mergeConfigFrom(
            __DIR__ . '/Config/package.sidebar.php', 'package.sidebar');
    }
}
