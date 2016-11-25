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

namespace Seat\Notifications\Commands;

use Illuminate\Console\Command;

/**
 * Class AlertsRun
 * @package Seat\Notifications\Commands
 */
class AlertsRun extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the Alerts for notifications';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        foreach (config('notifications.alerts') as $category => $alerts) {

            $this->line('Processing Category: ' . $category);

            foreach ($alerts as $name => $classes) {

                $this->comment('Handling: ' . $classes['alert']);

                (new $classes['alert'])->handle();
            }

        }
    }
}
